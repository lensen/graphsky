<?php
$name = "Graphsky";
if (isset($env))            { $title = "| $env"; }
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
    <link href="js/jquery-ui-1.10.2.custom.min.css" rel="stylesheet" type="text/css">
    <link href="img/favicon.ico" rel="icon" type="text/x-icon">
    <link href="img/favicon.ico" rel="shortcut icon" type="text/x-icon">
    <script type="text/javascript" src="js/jquery-1.9.1.min.js"></script>
    <script type="text/javascript" src="js/jquery-ui-1.10.2.custom.min.js"></script>
    <script type="text/javascript" src="js/jquery-ui-timepicker-addon.js"></script>
    <script type="text/javascript" charset="utf-8">
    Image1= new Image(24,24)
    Image1.src = "img/calendar_holo_24.png"
    $(function(){
        $('#from_calendar').datetimepicker({
            timeFormat: "HH:mm",
            dateFormat: "yy-mm-dd",
            showOn: "button",
            constrainInput: false,
            buttonImage: "img/calendar_holo_24.png",
            buttonImageOnly: true
        });
        $('#until_calendar').datetimepicker({
            timeFormat: "HH:mm",
            dateFormat: "yy-mm-dd",
            showOn: "button",
            constrainInput: false,
            buttonImage: "img/calendar_holo_24.png",
            buttonImageOnly: true
        });

    });
  </script>

    <title><?php print "$name $title" ?></title>
  </head>
<body>
  <div id="header">
    <div id="header_title">
      <?php print "<a href=\"/\">$name</a> $title" ?>
    </div>
  </div>

