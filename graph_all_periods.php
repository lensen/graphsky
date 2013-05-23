<?php
require_once "./include_conf.php";
include_once "./functions.php";

$graph      = isset($_GET['g'])  ? "&g=" . $_GET['g'] : "";
$metric     = isset($_GET['m']) ? "&m=" . $_GET['m'] : "";
$sourcetime = isset($_GET['st']) ? sanitize($_GET['st']) : NULL;
$env        = isset($_GET['env']) ? $_GET['env'] : $conf['graphite_default_env'];

$h        = isset($_GET['h']) ? $_GET['h'] : $conf['cluster_hostname'];
$realhost = isset($_GET['h']) ? $_GET['h'] : "";
if ( $h == "\*" )
    $h = "*";

$c           = isset($_GET['c']) ? sanitize($_GET['c']) : "*";
$realcluster = isset($_GET['c']) ? $_GET['c'] : "";
if ( $c == "\*" )
    $c = "*";

$graph_args = "env=$env&c=$realcluster&h=$realhost";

include_once "./header.php";

print "
<div id=\"container\"><div id=\"menu\"><div id=\"menu_row\">
    <div id=\"menu_cell\">
        <a href=\"?$graph_args\">Go to $realcluster $realhost overview</a>
    </div>
</div></div></div>
";

foreach ($conf["graph_all_periods_timeframes"] as $tf) {
    $graph_args = get_graph_domainname() . "/graph.php?$graph$metric&env=$env&h=$h&c=$c&st=$tf+ago";
    print print_period_graph($graph_args, "$graph$metric", $tf);
}

include_once "./footer.php";
?>
