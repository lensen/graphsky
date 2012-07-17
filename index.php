<?php
require_once "./include_conf.php";
include_once "./functions.php";

$env    = isset($_GET['env']) ? $_GET['env'] : $conf['graphite_default_env'];
$c      = (isset($_GET['c']) && $_GET['c'] != "") ? $_GET['c'] : NULL;
$m      = (isset($_GET['m']) && $_GET['m'] != "") ? $_GET['m'] : NULL;
$h      = (isset($_GET['h']) && $_GET['h'] != "") ? $_GET['h'] : NULL;
$g      = (isset($_GET['g']) && $_GET['g'] != "") ? $_GET['g'] : NULL;
$l      = (isset($_GET['l']) && $_GET['l'] != "") ? $_GET['l'] : "no";
$dn     = (isset($_GET['dn']) && $_GET['dn'] != "") ? $_GET['dn'] : NULL;
$gs     = isset($_GET['from']) ? $_GET['from'] : $conf['default_time_range'];
$ge     = isset($_GET['until']) ? $_GET['until'] : "-30 seconds";
$z      = isset($_GET['z']) && in_array($_GET[ 'z' ], $conf['graph_sizes_keys']) ? $_GET['z'] : "default";
$view   = NULL;

#$from   = "-" . $gs;
#$until  = ($ge == "now") ? $ge : "-" . $ge;
$from   = $gs;
$until  = ($ge == "now") ? $ge : $ge;

if ($env)     { $view = "env_view"; }
if ($c)       { $view = "cluster_view"; }
if ($c && $h) { $view = "host_view"; }

include_once "./header.php";
include_once "./menu.php";

print "<div id=\"container\"><div id=\"main\">";

if ($view)
    include_once "./$view.php";

print "</div></div>";
include_once "./footer.php";
?>

