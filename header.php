<?php
$name = "Graphsky";
if (isset($env))            { $title = ":: $env"; }
if (isset($c))              { $title = "$title - $c"; }
if (isset($c) && isset($h)) {
  if (isset($dn)) { $title = "$title - $dn"; }
  else            { $title = "$title - $h"; }
}
if (isset($g))              { $title = "$title ($g)"; }
?>

<html>
  <head>
    <meta http-equiv="content-type" content="text/html; charset=UTF-8">
    <meta http-equiv="refresh" content="<?php print $conf['dashboard_refresh_interval']; ?>" >
    <link href="stylesheet.css" rel="stylesheet" type="text/css">
    <link href="/img/favicon.ico" rel="icon" type="text/x-icon">
    <link href="/img/favicon.ico" rel="shortcut icon" type="text/x-icon">
    <title><?php print "$name $title" ?></title>
  </head>
<body>
  <div id="header">
    <div id="header_title">
      <?php print "<a href=\"/\">$name</a> $title" ?>
    </div>
  </div>

