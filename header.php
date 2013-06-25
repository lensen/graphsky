<?php
$name = "Graphsky";
$title_array = array($env,$c,$h,$g);
$title = implode(" > ", array_filter($title_array));
?>
<!DOCTYPE html>
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
    <script type="text/javascript" src="js/jquery-ui-timepicker-addon.min.js"></script>
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
        $(document).ready(function(){
            $('a.small_menu_button').click(function() {
                $("#small_menu").toggleClass("show");
            });
        });
    });
    </script>
    <title><?php print "$name | $title" ?></title>
  </head>
  <body>
    <div id="container">
      <div id="header">
        <div class="header_text left">
          <?php print "<a href=\"/\">$name</a>" ?>
        </div>
      </div>
