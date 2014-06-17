<?php
require_once "./include_conf.php";
include_once "./functions.php";

$graph       = isset($_GET['g'])  ? "&g=" . $_GET['g'] : "";
$metric      = isset($_GET['m']) ? "&m=" . $_GET['m'] : "";
$sourcetime  = isset($_GET['st']) ? sanitize($_GET['st']) : NULL;
$env         = isset($_GET['env']) ? $_GET['env'] : $conf['graphite_default_env'];

$c           = isset($_GET['c']) ? $_GET['c'] : "*";
$realcluster = isset($_GET['c']) ? $_GET['c'] : "";

$h           = isset($_GET['h']) ? $_GET['h'] : $conf['cluster_hostname'];
$realhost    = isset($_GET['h']) ? $_GET['h'] : "";

$graph_args = "env=$env&c=$c&h=$h$graph$metric";
$graph_args_real = "env=$env&c=$realcluster&h=$realhost$graph$metric";

include_once "./header.php";

print "
    <div id=\"top_menu\">
      <a href=\"/\"><div id=\"menu_logo\"></div></a>
        <div id==\"title_menu\" class=\"left\">
          <div class=\"title_menu_cell\">
            <a href=\"/?$graph_args_real\">Go to $env $realcluster $realhost overview</a>
          </div>
        </div>
      </div>
      <div id=\"main\">
        <div class=\"block_title\">Timeperiod overview</div>
        <div class=\"graph_block\">
";

foreach ($conf["graph_all_periods_timeframes"] as $tf) {
    print print_period_graph($graph_args, $tf);
}

print "</div></div>";
include_once "./footer.php";
