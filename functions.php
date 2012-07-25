<?php

#------------------------------------------------------------------------------
# Return a version of the string which is safe for display on a web page.
# Potentially dangerous characters are converted to HTML entities.  
# Resulting string is not URL-encoded.
function clean_string( $string ) {
    return htmlentities( $string );
}
#------------------------------------------------------------------------------
function sanitize ( $string ) {
    return escapeshellcmd( clean_string( rawurldecode( $string ) ) ) ;
}

#------------------------------------------------------------------------------
# If arg is a valid number, return it.  Otherwise, return null.
function clean_number( $value ) {
    return is_numeric( $value ) ? $value : null;
}

#------------------------------------------------------------------------------
# Function to return graph  domainname.
function get_graph_domainname() {
    global $conf;

    if ($conf['use_random_graph_domainname'])
        return str_replace("//", "//img" . rand(10,80) . ".", $conf['graph_domainname']);

    return $conf['graph_domainname'];
}

#------------------------------------------------------------------------------
# Function to build a Graphite url based on a json report.
function build_graphite_series( $config, $host_cluster = "" ) {
    $targets = array();
    $colors = array();
    $function = array();
    // Keep track of stacked items
    $stacked = 0;
  
    foreach( $config[ 'series' ] as $item ) {
        if ( $item['type'] == "stack" )
            $stacked++;
        if ( isset($item['functions']) )
            $functions = $item['functions'];
        else
            $functions[] = "sumSeries";
        if ( isset($item['hostname']) && isset($item['clustername']) )
            $host_cluster = $item['clustername'] . "." . str_replace(".","_", $item['hostname']);
        $metric = "$host_cluster.${item['metric']}";
        foreach( $functions as $function ) {
            $metric = "$function($metric)";
        }

        $targets[] = "target=". urlencode( "alias($metric,'${item['label']}')" );
        $colors[] = $item['color'];
    }

    $output = implode( $targets, '&' );
    $output .= "&colorList=" . implode( $colors, ',' );
    $output .= "&vtitle=" . urlencode( isset($config[ 'vertical_label' ]) ? $config[ 'vertical_label' ] : "" );

    if ( isset($config['units']) )
        $output .= "&yUnitSystem=" . $config['units'];

    // Do we have any stacked elements. We assume if there is only one element
    // that is stacked that rest of it is line graphs
    if ( $stacked > 0 ) {
        if ( $stacked > 1 )
            $output .= "&areaMode=stacked";
        else
            $output .= "&areaMode=first";
    }

    return $output;
}

#------------------------------------------------------------------------------
# Finds the max over a set of metric graphs.
function find_limits($environment, $cluster, $metricname, $start, $end) {
    global $conf;

    $max=0;
    $target = $conf['graphite_prefix'] . "$environment.$cluster.*." . $metricname;
    $raw_data = file_get_contents($conf['graphite_render_url'] . "?target=$target&from=$start&until=$end&format=json");
    $data = json_decode($raw_data, TRUE);
    $maxdatapoints = array();
    foreach ( $data as $data_target ) {
        $highestMaxDatapoints = $data_target['datapoints'];
        foreach ( $highestMaxDatapoints as $datapoint ) {
            array_push($maxdatapoints, $datapoint[0]);
        }
    }
    sort($maxdatapoints);
    $max = round(max($maxdatapoints) * 1.1);
    return $max;
}

function find_dashboards($environment, $cluster="") {
    global $conf;

    $graph_reports = array();
    $dash_config = json_decode(file_get_contents($conf['dashboard_config']), TRUE);
    foreach ($dash_config['dashboards'] as $dash) {
        if (! preg_match($dash['environments'], $environment) ) {
            continue;
        }
        if ($cluster) {
            if (! preg_match($dash['clusters'], $cluster)){
                continue;
            }
        }
        foreach ($dash['included_reports'] as $dashboard) {
            array_push($graph_reports, $dashboard);
        }
    }
    return $graph_reports;
}

function find_metrics($search_string, $group_depth=0) {
    global $conf;

    $metrics = array();
    $search_url = $conf['graphite_url_base'] . "/metrics/expand/?leavesOnly=1";
    $search_prefix = quotemeta($conf['graphite_prefix'] . $search_string);
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
    curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 5);
    $search_query_array = array();
    $search_query_wildcard = "";
    $i = 0;
    while ($i <= 10) {
        $search_query_wildcard = $search_query_wildcard . ".*";
        $search_query_item = "&query=" . $search_prefix . $search_query_wildcard;
        array_push($search_query_array, $search_query_item);
        $i++;
    }
    $search_query = implode("", $search_query_array);
    curl_setopt($ch, CURLOPT_URL, $search_url . $search_query);
    $results = json_decode(curl_exec($ch), TRUE);
    foreach ($results['results'] as $metric) {
        $metric_string = preg_replace("/^$search_prefix\./", "", $metric);
        $arr = explode('.',trim($metric_string));
        $metric_group = join(".", array_slice($arr, 0, $group_depth));
        if (!isset($metrics[$metric_group]) )
            $metrics[$metric_group] = array();
        array_push($metrics[$metric_group], $metric_string);
    }

    curl_close($ch);
    return $metrics;
}

?>
