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
$setmax         = isset($_GET['l']) ? $_GET['l'] : "no";
$env            = isset($_GET['env']) ? $_GET['env'] : $conf['graphite_default_env'];
$cluster        = isset($_GET['c']) ? $_GET['c'] : "*";
$host           = isset($_GET['h']) ? $_GET['h'] : $conf['cluster_hostname'];
$dn             = isset($_GET['dn']) ? $_GET['dn'] : "";
$graph          = isset($_GET['g']) ? sanitize($_GET['g']) : "metric";
$height         = isset($_GET['height']) ? $_GET['height'] : $conf['graph_sizes'][$size]['height'];
$width          = isset($_GET['width']) ? $_GET['width'] : $conf['graph_sizes'][$size]['width'];
$graphlot       = isset($_GET['graphlot']) ? $_GET['graphlot'] : NULL;

$graph_arguments = NULL;
$pos = strpos($graph, ",");
if ($pos !== FALSE) {
    $graph_report = substr($graph, 0, $pos);
    $graph_arguments = substr($graph, $pos + 1);
    $graph = $graph_report;
}

if ($sourcetime) {
    $start = "-" . $sourcetime;
}
elseif ($from) {
    $start = sanitize_datetime($from);
}
else {
    $start = $conf['default_time_range'];
}

if ($until) {
    $end = sanitize_datetime($until);
}
else {
    $end = "now";
}

$start = urlencode($start);
$end = urlencode($end);

// Add hostname to report graphs' title in host view
$title_prefix = "";
$title_prefix_array = array();
if (isset($_GET['dn'])) {
    $title_prefix_array[] = $_GET['dn'];
}
elseif ($host != "*" && $host != "") {
    $title_prefix_array[] = $cluster;
    $title_prefix_array[] = $host;
}
elseif ($cluster != "*" && $cluster != "") {
    $title_prefix_array[] = $cluster;
}
elseif ($env != "*" && $env != "") {
    $title_prefix_array[] = $env;
}

if (sizeof($title_prefix_array) > 0 && $title_prefix_array[0] != "") {
    $title_prefix = implode(" - ", $title_prefix_array) . " - ";
}

if (isset($_GET['s'])) {
    $service_name = sanitize($_GET['s']);

    $json_templates = glob($conf['graph_template_dir'] . "/*.json");
    foreach ( $json_templates as $json_template) {
        $template = json_decode(file_get_contents($json_template), TRUE);
        if ( $template['service_name'] == $service_name) {
            $service_report_name = $template['report_name'];
        }
    }
}

$host_cluster = $env . "." . $cluster . "." . $host;

if (isset($_GET['g']) or isset($service_report_name)) {
    $report_name = isset($service_report_name) ? sanitize($service_report_name) : sanitize($_GET['g']);
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

        $title = $title_prefix . $graph_config['title'];
    }
    else {
        error_log("Configuration file to $report_name exists however it doesn't appear it's a valid JSON file");
        exit(1);
    }
}
elseif ( isset($metric_name) ) {
    if ( isset($host) && ($host != "*") && ($setmax == "yes") ) {
        $max = find_limits($env, $cluster, $metric_name, $start, $end);
    }
    else {
        $max = "";
    }
    // It's a simple metric graph
    $target = "target=legendValue(alias(sumSeries(" . $conf['graphite_prefix'] . "$host_cluster.$metric_name),''),'last','max','min','si')&vtitle=" . urlencode($vlabel) . "&areaMode=all&colorList=". $conf['theme_color'];
    $title = "$title_prefix $metric_name";
}
else {
  error_log("I don't know what to do");
}

if ($sourcetime) $title = "$title last " . str_replace(" ago","",$sourcetime);

$graphite_url_args = "/render?width=$width&height=$height&" . $target . "&from=" . $start . "&until=" . $end . "&yMin=" . $min . "&yMax=" . $max . "&bgcolor=" . $conf['default_background_color'] . "&fgcolor=" . $conf['default_foreground_color'] . "&areaAlpha=0.7&title=" . urlencode($title);

if ( isset($graphlot) ) {
    $graphlot_url = graphite_server($env) . "/graphlot" . $graphite_url_args;
    header ("Location: $graphlot_url");
}
else {
    $format = "svg";
    $content_type = "image/svg+xml";
    if ( isset($conf['graphite_use_png']) or strpos($graphite_url_args,'graphType=pie') ) {
        $format = "png";
        $content_type = "image/png";
    }
    $graphite_url = graphite_server($env) . $graphite_url_args . "&format=" . $format;
    header ("Expires: Mon, 26 Jul 1997 05:00:00 GMT");   // Date in the past
    header ("Last-Modified: " . gmdate("D, d M Y H:i:s") . " GMT"); // always modified
    header ("Cache-Control: no-cache, must-revalidate");   // HTTP/1.1
    header ("Pragma: no-cache");                     // HTTP/1.0
    header ("Content-type: " . $content_type);

    ob_clean(); flush();
    if ( readfile( $graphite_url ) === False ) {
        error_log( "Image creation error, Graphite URL $graphite_url" );
    }
}

