<a name="clusters"></a>
<a href="#clusters">
    <h2>Clusters</h2>
</a>

<?php
$graph_args = "env=$env&z=$z";

$height = $conf['graph_sizes'][$z]['height'];
$width = $conf['graph_sizes'][$z]['width'];

$cluster_search = json_decode(file_get_contents($conf['graphite_search_url'] . $conf['graphite_prefix'] . "$env.*"), TRUE);
$clusters = $cluster_search['results'];
natsort($clusters);
foreach ($clusters as $cluster) {
    $cluster_name = str_replace($conf['graphite_prefix'] . "$env.", "", $cluster);

    $graph_reports = array();
    if (isset($g)) { $graph_reports = array($g); }
    else { $graph_reports = find_dashboards($env, $cluster_name); }

    if (!isset($g)) { print "<a href=\"/?$graph_args&c=$cluster_name&from=$gs&until=$ge\"><h3>$cluster_name</h3></a>"; }
    foreach ($graph_reports as $graph_report) {
        $current_graph_args = "$graph_args&c=$cluster_name";
        print print_graph($current_graph_args, "g=$graph_report", $width, $height, $from, $until);
    }
    if (!isset($g)){ print "<br /><br />"; }
}

?>
