<?php
$graph_args = "env=$env&z=$z&c=$c";
if (isset($dn)) { $graph_args = "$graph_args&dn=$dn"; }

print "<a name=\"reports\"></a><a href=\"#reports\"><h2>Reports:</h2></a>";

if (isset($g)) { $graph_reports = array($g); }
else { $graph_reports = find_dashboards($env, $c); }

$height = $conf['graph_sizes'][$z]['height'];
$width = $conf['graph_sizes'][$z]['width'];

foreach ($graph_reports as $graph_report) {
    $host_graph_args = $graph_args . "&g=$graph_report&h=$h";
    print "<a name=\"$graph_report\" />";
    print "<a href=\"/graph_all_periods.php?$host_graph_args\"><img src=\"". get_graph_domainname() . "/graph.php?$host_graph_args&from=$from&until=$until\" /></a>";
}

print "<a name=\"metrics\"></a><a href=\"#metrics\"><h2>Metrics:</h2></a>";
$metrics = find_metrics("$env.$c.$h", $conf['host_metric_group_depth']);
foreach ($metrics as $metric_group => $metric_array) {
    print "<a name=\"$metric_group\"></a><a href=\"#$metric_group\"><h3>$metric_group</h3></a>";
    foreach ($metric_array as $metric) {
        $host_graph_args = $graph_args . "&m=$metric&h=$h&dn=";
        print "<a href=\"/graph_all_periods.php?$host_graph_args\"><img width=\"$width\" height=\"$height\" class=\"lazy\" src=\"img/blank.gif\" data-original=\"". get_graph_domainname() . "/graph.php?$host_graph_args&from=$from&until=$until\" /></a>";
    }
}

?>
