<a name="overview"></a>
<a href="#overview">
	<h2>Overview</h2>
</a>

<?php
$graph_args = "env=$env&z=$z&c=$c&l=$l";

if (isset($g)) { $graph_reports = array($g); }
elseif (isset($m)) { $metric_graph = $m; }
else { $graph_reports = find_dashboards($env, $c); }

$height = $conf['graph_sizes'][$z]['height'];
$width = $conf['graph_sizes'][$z]['width'];

if (isset($m)) {
	print print_zoom_graph($graph_args, "m=$metric_graph", $width, $height, $from, $until);
}
elseif (isset($graph_reports)) {
    foreach ($graph_reports as $graph_report) {
		print print_zoom_graph($graph_args, "g=$graph_report", $width, $height, $from, $until);
    }
}
?>

<a name="hosts"></a>
<a href="#hosts">
	<h2>Hosts</h2>
</a>

<?php
$host_search = json_decode(file_get_contents($conf['graphite_search_url'] . $conf['graphite_prefix'] . "$env.$c.*"), TRUE);
$hosts = $host_search['results'];
natsort($hosts);
foreach ($hosts as $host) {
    $host_name = str_replace($conf['graphite_prefix'] . "$env.$c.", "", $host);
    if ($host_name == $conf['cluster_hostname']) { continue; }
    if (isset($graph_reports)) {
        if (!isset($g)) { print "<a href=\"/?$graph_args&h=$host_name&from=$gs&until=$ge\"><h3>$host_name</h3></a>"; }
        foreach ($graph_reports as $graph_report) {
			$current_graph_args = $graph_args . "&h=$host_name";
			print print_graph($current_graph_args, "g=$graph_report", $width, $height, $from, $until);
        }
        if (!isset($g)) { print "<br /><br />"; }
    }
    elseif (isset($m)) {
        $depth = $conf['host_metric_group_depth'] + 1;
        $metric_group_elements = explode(".", $metric_graph, $depth);
        array_pop($metric_group_elements);
        $metric_group_name = implode(".", $metric_group_elements);
        $current_graph_args = $graph_args . "&h=$host_name&dn=$host_name";
		print print_graph($current_graph_args, "m=$metric_graph", $width, $height, $from, $until);
    }
}

?>
