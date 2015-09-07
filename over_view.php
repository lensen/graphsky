<a class="anchor" id="clusters">&nbsp;</a>
<div class="block_title"><a href="#envs">Environments</a></div>

<?php
if (sizeof($environments) > 0) {
    foreach ($environments as $env) {
        $env_name = str_replace($conf['graphite_prefix'], "", $env);
        $graph_reports = array();
        if (isset($g)) { $graph_reports = array($g); }
        else { $graph_reports = find_dashboards($env_name); }

        if (!isset($g)) { print "<div class=\"graph_block_title\"><a href=\"?env=$env_name&from=$gs&until=$ge\">$env_name</div></a><div class=\"graph_block\">"; }
        foreach ($graph_reports as $graph_report) {
            print print_graph("env=$env_name", "g=$graph_report", $z, $from, $until);
        }
        if (!isset($g)){ print "</div>"; }
    }
}

?>
