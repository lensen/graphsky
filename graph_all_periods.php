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

$graph_args = "env=$env&c=$c&h=$h$graph$metric";

include_once "./header.php";

print "
      <div id=\"menu\"><div class=\"menu_row\">
        <div class=\"menu_cell\">
          <a href=\"/?$graph_args\">Go to $realcluster $realhost overview</a>
        </div>
      </div></div>
      <div id=\"main\">
";

foreach ($conf["graph_all_periods_timeframes"] as $tf) {
    print print_period_graph($graph_args, $tf);
}

print "</div>";
include_once "./footer.php";
?>
