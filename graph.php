<?php
require_once "./include_conf.php";
include_once "./functions.php";

$size           = isset($_GET['z']) && in_array($_GET[ 'z' ], $conf['graph_sizes_keys']) ? $_GET['z'] : "default";
$vlabel         = isset($_GET['vl']) ? sanitize($_GET['vl'])  : NULL;
$metric_name    = isset($_GET['m']) ? $_GET['m'] : NULL;
$max            = isset($_GET['x']) && is_numeric($_GET['x']) ? $_GET['x'] : NULL;
$min            = isset($_GET['n']) && is_numeric($_GET['n']) ? $_GET['n'] : 0;
$sourcetime     = isset($_GET['st']) ? sanitize($_GET['st']) : NULL;
$from           = isset($_GET['from']) ? sanitize($_GET['from']) : NULL;
$until          = isset($_GET['until']) ? sanitize($_GET['until']) : NULL;
$puppetrun      = isset($_GET['pr']) ? sanitize($_GET['pr']) : NULL;
$setmax         = isset($_GET['l']) ? $_GET['l'] : "no";
$env            = isset($_GET['env']) ? $_GET['env'] : $conf['graphite_default_env'];
$host           = isset($_GET['h']) ? $_GET['h'] : $conf['cluster_hostname'];
$graph = isset($_GET['g'])  ?  sanitize ( $_GET['g'] )   : "metric";

$graph_arguments = NULL;
$pos = strpos($graph, ",");
if ($pos !== FALSE) {
    $graph_report = substr($graph, 0, $pos);
    $graph_arguments = substr($graph, $pos + 1);
    $graph = $graph_report;
}

if ( $host == "\*" ) { $host = "*"; }
$clustername = isset($_GET['c']) ? sanitize($_GET['c']) : "*";

$graphite_url = '';

if (isset($_GET['height']))
    $height = $_GET['height'];
else
    $height  = $conf['graph_sizes'][$size]['height'];

if (isset($_GET['width']))
    $width =  $_GET['width'];
else
    $width = $conf['graph_sizes'][$size]['width'];

if ($sourcetime) {
    $start = "-" . $sourcetime;
} elseif ($from) {
    $start = sanitize_datetime($from);
} else {
    $start = $conf['default_time_range'];
}

if ($until) {
    $end = sanitize_datetime($until);
} else {
    $end = "now";
}

// Add hostname to report graphs' title in host view
if (isset($_GET['dn']))
    $title_prefix = $_GET['dn'];
elseif ($clustername != "*" && $clustername != "") {
    $title_prefix = $clustername;
    if ($host != "*" && $host != "")
        $title_prefix .= " - $host";
}
elseif ($host != "*" && $host != "")
    $title_prefix = $host;
else
    $title_prefix = "";

if ( isset($_GET['s'])) {
    $service_name = sanitize($_GET['s']);
  
    $json_templates = glob($conf['graph_template_dir'] . "/*.json");
    foreach ( $json_templates as $json_template) {
        $template = json_decode(file_get_contents($json_template), TRUE);
        if ( $template['service_name'] == $service_name)
            $report_name = $template['report_name'];
    }
}

if ( isset($_GET['g']) )
    $report_name = sanitize($_GET['g']);

$host_cluster = $env . "." . $clustername . "." . $host;
if ( isset($report_name) ) {
    $report_definition_file = $conf['graph_template_dir'] . "/" . $report_name . ".json";
    // Check whether report is defined in graph.d directory
    if ( is_file($report_definition_file) ) {
        $graph_config = json_decode(file_get_contents($report_definition_file), TRUE);
    }
    else {
        error_log("There is no JSON config file specifying $report_name.");
        exit(1);
    }

    if ( isset($graph_config) ) {
        if ( isset($graph_config['report_type']) ) {
            if ( $graph_config['report_type'] == "template" ) {
                $target = str_replace("HOST_CLUSTER", $host_cluster, $graph_config['graphite']);
            }
        }
        else {
                    $target = build_graphite_series( $graph_config, $conf['graphite_prefix'] . $host_cluster );
        }

        $title = $title_prefix . " - " . $graph_config['title'];
    }
    else {
        error_log("Configuration file to $report_name exists however it doesn't appear it's a valid JSON file");
        exit(1);
    }
}
elseif ( isset($metric_name) ) {
    if ( isset($host) && ($host != "*") && ($setmax == "yes") ) { 
        $max = find_limits($env, $clustername, $metric_name, $start, $end);
    }
    else {
        $max = "";
    }
    // It's a simple metric graph
    $target = "target=alias(sumSeries(" . $conf['graphite_prefix'] . "$host_cluster.$metric_name),'$metric_name')&vtitle=" . urlencode($vlabel) . "&areaMode=all&colorList=". $conf['default_metric_color'];
    $title = "$title_prefix - $metric_name";
}
else {
  error_log("I don't know what to do");
}

if ($sourcetime) $title = "$title last " . str_replace(" ago","",$sourcetime);
if ($puppetrun == "yes" && isset( $_GET['h']) && $host != "*") $target = "target=alias(color(drawAsInfinite(" .$conf['graphite_puppet_prefix'] . $env . "." . $host . "_*),'FF00FFAA'),'puppetrun')&" . $target;

$graphite_url = $conf['graphite_render_url'] . "?width=$width&height=$height&" . $target . "&from=" . urlencode($start) . "&until=" . urlencode($end) . "&yMin=" . $min . "&yMax=" . $max . "&bgcolor=" . $conf['default_background_color'] . "&fgcolor=" . $conf['default_foreground_color'] . "&title=" . urlencode($title);

header ("Expires: Mon, 26 Jul 1997 05:00:00 GMT");   // Date in the past
header ("Last-Modified: " . gmdate("D, d M Y H:i:s") . " GMT"); // always modified
header ("Cache-Control: no-cache, must-revalidate");   // HTTP/1.1
header ("Pragma: no-cache");                     // HTTP/1.0
header ("Content-type: image/png");

ob_clean(); flush();
if ( readfile( $graphite_url ) === False ) {
    error_log( "Image creation error, Graphite URL $graphite_url" );
}
