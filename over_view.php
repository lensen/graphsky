<a class="anchor" id="clusters">&nbsp;</a>
<div class="block_title"><a href="#envs">Environments</a></div>

<?php
$env_search = json_decode(file_get_contents($conf['graphite_search_url'] . $conf['graphite_prefix'] . "*"), TRUE);
$envs = $env_search['results'];
natsort($envs);
foreach ($envs as $env) {
    $env_name = str_replace($conf['graphite_prefix'], "", $env);
    $graph_reports = array();
    if (isset($g)) { $graph_reports = array($g); }
    else { $graph_reports = find_dashboards($env_name); }

    if (!isset($g)) { print "<a href=\"?env=$env_name&from=$gs&until=$ge\"><div class=\"banner_text\">$env_name</div></a><div class=\"graph_block\">"; }
    foreach ($graph_reports as $graph_report) {
        print print_graph("env=$env_name", "g=$graph_report", $z, $from, $until);
    }
    if (!isset($g)){ print "</div>"; }
}

?>
