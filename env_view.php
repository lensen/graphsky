<?php
$graph_args = "env=$env&z=$z";

$height = $conf['graph_sizes'][$z]['height'];
$width = $conf['graph_sizes'][$z]['width'];

print "<a name=\"clusters\"></a><a href=\"#clusters\"><h2>Clusters</h2></a>";

$cluster_search = json_decode(file_get_contents($conf['graphite_search_url'] . $conf['graphite_prefix'] . "$env.*"), TRUE);
foreach ($cluster_search['results'] as $cluster) {
    $cluster_name = str_replace($conf['graphite_prefix'] . "$env.", "", $cluster);
    
    $graph_reports = array();
    if (isset($g)) { $graph_reports = array($g); }
    else { $graph_reports = find_dashboards($env, $cluster_name); }

    if (!isset($g)) { print "<a href=\"/?$graph_args&c=$cluster_name&from=$gs&until=$ge\"><h3>$cluster_name</h3></a>"; }
    foreach ($graph_reports as $graph_report) {
        $cluster_graph_args = "$graph_args&g=$graph_report&c=$cluster_name&from=$from&until=$until";
        print "<a href=\"/?$cluster_graph_args\"><img width=\"$width\" height=\"$height\" class=\"lazy\" src=\"img/blank.gif\" data-original=\"". get_graph_domainname() . "/graph.php?$cluster_graph_args\" /></a>";
    }
    if (!isset($g)){ print "<br /><br />"; }
}

?>
