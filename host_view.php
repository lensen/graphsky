<a name="reports"></a>
<a href="#reports">
    <h2>Reports</h2>
</a>

<?php
$graph_args = "env=$env&z=$z&c=$c";
if (isset($dn)) { $graph_args = "$graph_args&dn=$dn"; }

if (isset($g)) { $graph_reports = array($g); }
else { $graph_reports = find_dashboards($env, $c); }

$height = $conf['graph_sizes'][$z]['height'];
$width = $conf['graph_sizes'][$z]['width'];

foreach ($graph_reports as $graph_report) {
    $current_graph_args = $graph_args . "&h=$h";
    print print_zoom_graph($current_graph_args, "g=$graph_report", $width, $height, $from, $until);
}
?>

<a name="metrics"></a>
<a href="#metrics">
    <h2>Metrics</h2>
</a>

<?php
$metrics = find_metrics("$env.$c.$h", $conf['host_metric_group_depth']);
foreach ($metrics as $metric_group => $metric_array) {
    print "<a name=\"$metric_group\"></a><a href=\"#$metric_group\"><h3>$metric_group</h3></a>";
    foreach ($metric_array as $metric) {
        $current_graph_args = $graph_args . "&h=$h&dn=";
        print print_zoom_graph($current_graph_args, "m=$metric", $width, $height, $from, $until);
    }
}

?>
