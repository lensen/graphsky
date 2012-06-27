<?php
$graph_args = "env=$env&z=$z&c=$c&l=$l";

print "<a name=\"overview\"></a><a href=\"#overview\"><h2>Overview:</h2></a>";

if (isset($g)) { $graph_reports = array($g); }
else { $graph_reports = find_dashboards($env, $c); }

foreach ($graph_reports as $graph_report) {
    $cluster_graph_args = "$graph_args&g=$graph_report";
    print "<a href=\"/graph_all_periods.php?$cluster_graph_args\"><img src=\"". get_graph_domainname() . "/graph.php?$cluster_graph_args&from=$from&until=$until\" /></a>";
}

print "<a name=\"hosts\"></a><a href=\"#hosts\"><h2>Hosts:</h2></a>";
$host_search = json_decode(file_get_contents($conf['graphite_search_url'] . $conf['graphite_prefix'] . "$env.$c.*"), TRUE);
foreach ($host_search['results'] as $host) {
    $host_name = str_replace($conf['graphite_prefix'] . "$env.$c.", "", $host);
    if ($host_name == $conf['cluster_hostname']) { continue; }
    if (!isset($g)) { print "<a href=\"/?$graph_args&h=$host_name&from=$gs&until=$ge\"><h3>$host_name</h3></a>"; }
    foreach ($graph_reports as $graph_report) {
        $host_graph_args = "$graph_args&g=$graph_report&h=$host_name";
        print "<a href=\"/graph_all_periods.php?$host_graph_args\"><img src=\"". get_graph_domainname() . "/graph.php?$host_graph_args&from=$from&until=$until\" /></a>";
    }
    if (!isset($g)){ print "<br /><br />"; }
}

?>
