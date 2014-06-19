<a class="anchor" id="reports">&nbsp;</a>
<div class="block_title"><a href="#reports">Reports</a></div>

<div class="graph_block">
<?php
$graph_args = "env=$env&c=$c&h=$h";
if (isset($dn)) { $graph_args = "$graph_args&dn=$dn"; }

if (isset($g)) { $graph_reports = array($g); }
else { $graph_reports = find_dashboards($env, $c); }

foreach ($graph_reports as $graph_report) {
    print print_zoom_graph($graph_args, "g=$graph_report", $z, $from, $until);
}
?>
</div>
<a class="anchor" id="metrics">&nbsp;</a>
<div class="block_title"><a href="#metrics">Metrics</a></div>

<?php
if (isset($g)) { $metrics = find_report_metrics($graph_report); }
else { $metrics = find_metrics("$env.$c.$h", $conf['host_metric_group_depth']); }

foreach ($metrics as $metric_group => $metric_array) {
    print "<a class=\"anchor\" id=\"$metric_group\">&nbsp;</a>";
    print "<a href=\"#$metric_group\"><div class=\"banner_text\">$metric_group</div></a>";
    print "<div class=\"graph_block\">";
    foreach ($metric_array as $metric) {
        print print_zoom_graph($graph_args . "&dn=", "m=$metric", $z, $from, $until);
    }
    print "</div>";
}
?>
