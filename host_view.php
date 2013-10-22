<span class="anchor" id="reports"></span>
<div class="block_title"><a href="#reports">Reports</a></div>

<div class="graph_block">
<?php
$graph_args = "env=$env&c=$c";
if (isset($dn)) { $graph_args = "$graph_args&dn=$dn"; }

if (isset($g)) { $graph_reports = array($g); }
else { $graph_reports = find_dashboards($env, $c); }

foreach ($graph_reports as $graph_report) {
    $current_graph_args = $graph_args . "&h=$h";
    print print_zoom_graph($current_graph_args, "g=$graph_report", $z, $from, $until);
}
?>
</div>
<span class="anchor" id="metrics"></span>
<div class="block_title"><a href="#metrics">Metrics</a></div>

<?php
$metrics = find_metrics("$env.$c.$h", $conf['host_metric_group_depth']);
foreach ($metrics as $metric_group => $metric_array) {
    print "<span class=\"anchor\" id=\"$metric_group\"></span>";
    print "<a href=\"#$metric_group\"><div class=\"banner_text\">$metric_group</div></a>";
    print "<div class=\"graph_block\">";
    foreach ($metric_array as $metric) {
        $current_graph_args = $graph_args . "&h=$h&dn=";
        print print_zoom_graph($current_graph_args, "m=$metric", $z, $from, $until);
    }
    print "</div>";
}

?>
