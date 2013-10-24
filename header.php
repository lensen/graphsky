<?php
$name = "Graphsky";
$env  = isset($_GET['env']) ? $_GET['env'] : $conf['graphite_default_env'];
$c    = (isset($_GET['c']) && $_GET['c'] != "") ? $_GET['c'] : NULL;
$m    = (isset($_GET['m']) && $_GET['m'] != "") ? $_GET['m'] : NULL;
$h    = (isset($_GET['h']) && $_GET['h'] != "") ? $_GET['h'] : NULL;
$g    = (isset($_GET['g']) && $_GET['g'] != "") ? $_GET['g'] : NULL;
$title_array = array($env,$c,$h,$g);
$title = implode(" > ", array_filter($title_array));
?>
<!DOCTYPE html>
<html>
  <head>
    <meta http-equiv="Content-Type" content="text/html;charset=UTF-8">
    <meta http-equiv="refresh" content="<?php print $conf['dashboard_refresh_interval']; ?>" >
    <meta name="viewport" content="initial-scale=0.8,minimum-scale=0.8,maximum-scale=0.8,width=device-width,height=device-height,target-densitydpi=device-dpi,user-scalable=yes" />
    <meta name="mobile-web-app-capable" content="yes">
    <link href="stylesheet.css" rel="stylesheet" type="text/css">
    <link href="js/jquery-ui-1.10.2.custom.min.css" rel="stylesheet" type="text/css">
    <link href="img/favicon.ico" rel="icon" type="text/x-icon">
    <link href="img/logo.png" rel="shortcut icon" sizes="196x196">
    <link href="img/logo.png" rel="apple-touch-icon" sizes="196x196">
    <script type="text/javascript" src="js/jquery-1.9.1.min.js"></script>
    <script type="text/javascript" src="js/jquery-ui-1.10.2.custom.min.js"></script>
    <script type="text/javascript" src="js/jquery-ui-timepicker-addon.min.js"></script>
    <script type="text/javascript" charset="utf-8">
    Image1 = new Image(24,24)
    Image1.src = "img/calendar_holo_24.png"
    $(function(){
        $('#from_calendar').datetimepicker({
            timeFormat: "HH:mm",
            dateFormat: "yy-mm-dd",
            showOn: "button",
            showTime: false,
            constrainInput: false,
            buttonImage: "img/calendar_holo_24.png",
            buttonImageOnly: true,
            controlType: "select"
        });
        $('#until_calendar').datetimepicker({
            timeFormat: "HH:mm",
            dateFormat: "yy-mm-dd",
            showOn: "button",
            showTime: false,
            constrainInput: false,
            buttonImage: "img/calendar_holo_24.png",
            buttonImageOnly: true,
            controlType: "select"
        });
        $(document).ready(function(){
            $('a.small_menu_button').click(function() {
                $("#small_menu").toggleClass("show");
            });
        });
    });
    $(document).ready(function() {
        var stickyNavTop = $('#menu').offset().top;
        var stickyNav = function(){
            var scrollTop = $(window).scrollTop();
            if (scrollTop > stickyNavTop) {
                $('#menu').addClass('sticky');
            } else {
                $('#menu').removeClass('sticky');
            }
        };
        stickyNav();
        $(window).scroll(function() {
            stickyNav();
        });
    });
    </script>
    <title><?php print "$name | $title" ?></title>
  </head>
  <body>
    <div id="container">
      <div id="top">
        <div id="header">
          <div class="header_text">
            <?php print "<a href=\"/\">$name</a>" ?>
          </div>
        </div>
