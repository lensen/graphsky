<a class="anchor" id="overview">&nbsp;</a>
<div class="block_title"><a href="#overview">Cluster overview</a></div>

<div class="graph_block">
<?php
$graph_args = "env=$env&c=$c&l=$l";

if (isset($g)) { $graph_reports = array($g); }
elseif (isset($m)) { $metric_graph = $m; }
else { $graph_reports = find_dashboards($env, $c); }

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
<a class="anchor" id="hosts">&nbsp;</a>
<div class="block_title"><a href="#hosts">Hosts</a></div>

<?php
$host_search = json_decode(file_get_contents($conf['graphite_search_url'] . $conf['graphite_prefix'] . "$env.$c.*"), TRUE);
$hosts = $host_search['results'];
natsort($hosts);
if (isset($g) or isset($m)) { print "<div class=\"graph_block\">"; }
foreach ($hosts as $host) {
    $host_name = str_replace($conf['graphite_prefix'] . "$env.$c.", "", $host);
    if ($host_name == $conf['cluster_hostname']) { continue; }
    if (isset($graph_reports)) {
        if (!isset($g)) { print "<div class=\"graph_block_title\"><a href=\"?$graph_args&h=$host_name&from=$gs&until=$ge\">$host_name</a></div><div class=\"graph_block\">"; }
        foreach ($graph_reports as $graph_report) {
            $current_graph_args = $graph_args . "&h=$host_name";
            print print_graph($current_graph_args, "g=$graph_report", $z, $from, $until);
        }
        if (!isset($g)) { print "</div>"; }
    }
    elseif (isset($m)) {
        $depth = $conf['host_metric_group_depth'] + 1;
        $metric_group_elements = explode(".", $metric_graph, $depth);
        array_pop($metric_group_elements);
        $metric_group_name = implode(".", $metric_group_elements);
        $current_graph_args = $graph_args . "&h=$host_name&dn=$host_name";
        print print_graph($current_graph_args, "m=$metric_graph", $z, $from, $until);
    }
}
if (isset($g)) { print "</div>"; }
?>
