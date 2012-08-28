<?php
$graph_args = "env=$env&z=$z&c=$c&l=$l";

print "<a name=\"overview\"></a><a href=\"#overview\"><h2>Overview</h2></a>";

if (isset($g)) { $graph_reports = array($g); }
elseif (isset($m)) { $metric_graph = $m; }
else { $graph_reports = find_dashboards($env, $c); }

$height = $conf['graph_sizes'][$z]['height'];
$width = $conf['graph_sizes'][$z]['width'];

if (isset($m)) {
    $cluster_graph_args = $graph_args . "&m=$metric_graph";
    print "<a href=\"/graph_all_periods.php?$cluster_graph_args\"><img width=\"$width\" height=\"$height\" src=\"". get_graph_domainname() . "/graph.php?$cluster_graph_args&from=$from&until=$until\" /></a>";
}
elseif (isset($graph_reports)) {
    foreach ($graph_reports as $graph_report) {
        $cluster_graph_args = "$graph_args&g=$graph_report";
        print "<a href=\"/graph_all_periods.php?$cluster_graph_args\"><img width=\"$width\" height=\"$height\" src=\"". get_graph_domainname() . "/graph.php?$cluster_graph_args&from=$from&until=$until\" /></a>";
    }
}
print "<a name=\"hosts\"></a><a href=\"#hosts\"><h2>Hosts</h2></a>";
$host_search = json_decode(file_get_contents($conf['graphite_search_url'] . $conf['graphite_prefix'] . "$env.$c.*"), TRUE);
foreach ($host_search['results'] as $host) {
    $host_name = str_replace($conf['graphite_prefix'] . "$env.$c.", "", $host);
    if ($host_name == $conf['cluster_hostname']) { continue; }
    if (isset($graph_reports)) {
        if (!isset($g)) { print "<a href=\"/?$graph_args&h=$host_name&from=$gs&until=$ge\"><h3>$host_name</h3></a>"; }
        foreach ($graph_reports as $graph_report) {
            $host_graph_args = "$graph_args&g=$graph_report&h=$host_name";
            print "<a href=\"/?$graph_args&h=$host_name&from=$gs&until=$ge\"><img width=\"$width\" height=\"$height\" class=\"lazy\" src=\"img/blank.gif\" data-original=\"". get_graph_domainname() . "/graph.php?$host_graph_args&from=$from&until=$until\"/></a>";
        }
        if (!isset($g)) { print "<br /><br />"; }
    }
    elseif (isset($m)) {
        $depth = $conf['host_metric_group_depth'] + 1;
        $metric_group_elements = explode(".", $metric_graph, $depth);
        array_pop($metric_group_elements);
        $metric_group_name = implode(".", $metric_group_elements);
        $host_graph_args = $graph_args . "&m=$metric_graph&h=$host_name&dn=$host_name";
        print "<a href=\"/?$graph_args&h=$host_name&from=$gs&until=$ge#$metric_group_name\"><img width=\"$width\" height=\"$height\" class=\"lazy\" src=\"img/blank.gif\" data-original=\"". get_graph_domainname() . "/graph.php?$host_graph_args&from=$from&until=$until\" /></a>";
    }
}

?>
