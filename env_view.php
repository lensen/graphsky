<a class="anchor" name="overview">&nbsp;</a>
<div class="block_title"><a href="#overview">Environment overview</a></div>

<div class="graph_block">
<?php
$graph_args = "env=$env";

if (isset($g)) { $graph_reports = array($g); }
elseif (isset($m)) { $metric_graph = $m; }
else { $graph_reports = find_dashboards($env); }

if (isset($m)) {
    print print_zoom_graph($graph_args, "m=$metric_graph", $z, $from, $until);
}
elseif (isset($graph_reports)) {
    foreach ($graph_reports as $graph_report) {
        print print_zoom_graph($graph_args, "g=$graph_report", $z, $from, $until);
    }
}
?>
</div>
<a class="anchor" name="clusters">&nbsp;</a>
<div class="block_title"><a href="#clusters">Clusters</a></div>

<?php
$cluster_search = json_decode(file_get_contents($conf['graphite_search_url'] . $conf['graphite_prefix'] . "$env.*"), TRUE);
$clusters = $cluster_search['results'];
natsort($clusters);
if (isset($g)) { print "<div class=\"graph_block\">"; }
foreach ($clusters as $cluster) {
    $cluster_name = str_replace($conf['graphite_prefix'] . "$env.", "", $cluster);
    $graph_reports = array();
    if (isset($g)) { $graph_reports = array($g); }
    else { $graph_reports = find_dashboards($env, $cluster_name); }

        if (!isset($g)) { print "<div class=\"graph_block_title\"><a href=\"?$graph_args&c=$cluster_name&from=$gs&until=$ge\">$cluster_name</div></a><div class=\"graph_block\">"; }
    foreach ($graph_reports as $graph_report) {
        if ( show_on_dashboard($graph_report, $env, $cluster_name) ) {
            $current_graph_args = "$graph_args&c=$cluster_name";
            print print_graph($current_graph_args, "g=$graph_report", $z, $from, $until);
        }
    }
    if (!isset($g)){ print "</div>"; }
}
if (isset($g)) { print "</div>"; }
?>
