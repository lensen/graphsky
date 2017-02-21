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
<!-- Icons by Google: https://github.com/google/material-design-icons -->
<html>
  <head>
    <meta http-equiv="Content-Type" content="text/html;charset=UTF-8">
    <meta http-equiv="refresh" content="<?php print $conf['dashboard_refresh_interval']; ?>" >
    <meta name="viewport" content="initial-scale=1,width=device-width,height=device-height,user-scalable=no" />
    <meta name="mobile-web-app-capable" content="yes">
    <meta name="theme-color" content="#<?php print $conf['theme_color']; ?>" >
    <!--link rel="stylesheet" type="text/css" href="stylesheet.css"-->
    <link rel="stylesheet" type="text/css" href="stylesheet.php">
    <link rel="stylesheet" type="text/css" href="js/jquery-ui.min.css">
    <link rel="stylesheet" type="text/css" href="js/jquery-ui.structure.min.css">
    <link rel="stylesheet" type="text/css" href="js/jquery-ui.theme.min.css">
    <link rel="stylesheet" type="text/css" href="js/jquery-ui-timepicker-addon.min.css">
    <link rel="shortcut icon" href="img/favicon.php?c=<?php print $conf['theme_color']; ?>" sizes="196x196">
    <!--link rel="shortcut icon" href="img/icon.png" sizes="196x196"-->
    <link rel="apple-touch-icon" href="img/apple-touch-icon.png" sizes="196x196">
    <link rel="apple-touch-icon-precomposed" href="img/apple-touch-icon.png" sizes="196x196">
    <script type="text/javascript" src="js/jquery-1.11.3.min.js"></script>
    <script type="text/javascript" src="js/jquery-ui.min.js"></script>
    <script type="text/javascript" src="js/jquery-ui-timepicker-addon.min.js"></script>
    <script type="text/javascript" charset="utf-8">
    var didScroll;
    var lastScrollTop = 0;
    var delta = 5;
    var navbarHeight = $('#graph_menu').outerHeight();

    $(window).scroll(function(event){
        didScroll = true;
    });

    setInterval(function() {
        if (didScroll) {
            hasScrolled();
            didScroll = false;
        }
    }, 250);

    function hasScrolled() {
        var st = $(this).scrollTop();

        if(Math.abs(lastScrollTop - st) <= delta)
            return;
        if (st > lastScrollTop && st > navbarHeight){
            $('#graph_menu').removeClass('graph_menu-down').addClass('graph_menu-up');
        } else {
            if(st + $(window).height() < $(document).height()) {
                $('#graph_menu').addClass('graph_menu-down').removeClass('graph_menu-up');
            }
        }
        lastScrollTop = st;
    }

    $(function(){
        $.datepicker.setDefaults({
            timeFormat: "HH:mm",
            dateFormat: "yy-mm-dd",
            showOn: "button",
            showTime: false,
            constrainInput: false,
            buttonImage: "img/calendar.svg",
            buttonImageOnly: true,
            controlType: "select",
            oneLine: true
        });
        $('#from_calendar').datetimepicker({
            onClose: function(dateText, inst) { $(this).attr("disabled", false); },
            beforeShow: function(dateText, inst) { $(this).attr("disabled", true); }
        });
        $('#until_calendar').datetimepicker({
            onClose: function(dateText, inst) { $(this).attr("disabled", false); },
            beforeShow: function(dateText, inst) { $(this).attr("disabled", true); }
        });
    });

    </script>
    <title><?php print "$name | $title" ?></title>
  </head>
  <body>
    <div id="container">

