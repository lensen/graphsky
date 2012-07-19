<div id="container"><div id="menu">

<div id="menu_row">
  <div id="menu_cell">
  &nbsp;
  <form name="opts" method="get" action="index.php">
    &nbsp;Environment:&nbsp;
    <div id="select"><select name="env" onchange="document.opts.c.value = ''; document.opts.h.value = ''; document.opts.submit()">
      <option value="">Select environment</option>
<?php
$environment_search = json_decode(file_get_contents($conf['graphite_search_url'] . $conf['graphite_prefix'] . "*"), TRUE);
foreach ($environment_search['results'] as $environment) {
    $environment_name = str_replace($conf['graphite_prefix'], "", $environment);
    if ($environment_name == $env) { $selected = "selected=\"selected\""; }
    else { $selected = ""; }
    print "<option value=\"$environment_name\" $selected>$environment_name</option>\n";
}
?>
    </select></div>
    &nbsp;Cluster:&nbsp;
    <div id="select"><select name="c" onchange="document.opts.h.value = ''; document.opts.submit()">
<?php
$cluster_search = json_decode(file_get_contents($conf['graphite_search_url'] . $conf['graphite_prefix'] . "$env.*"), TRUE);
if (sizeof($cluster_search['results']) < 1) { $default = "None"; }
else { $default = "All"; }
print "<option value=\"\">$default</option>";
foreach ($cluster_search['results'] as $cluster) {
    $cluster_name = str_replace($conf['graphite_prefix'] . "$env.", "", $cluster);
    if ($cluster_name == $c) { $selected = "selected=\"selected\""; }
    else { $selected = ""; }
    print "<option value=\"$cluster_name\" $selected>$cluster_name</option>\n";
}
?>
    </select></div>
    &nbsp;Host:&nbsp;
    <div id="select"><select name="h" onchange="document.opts.submit()">
<?php
$host_search = json_decode(file_get_contents($conf['graphite_search_url'] . $conf['graphite_prefix'] . "$env.$c.*"), TRUE);
if (sizeof($host_search['results']) < 1) { $default = "None"; }
else { $default = "All"; }
print "<option value=\"\">$default</option>";
foreach ($host_search['results'] as $host) {
    $host_name = str_replace($conf['graphite_prefix'] . "$env.$c.", "", $host);
    if ($host_name == $h) { $selected = "selected=\"selected\""; }
    else { $selected = ""; }
    print "<option value=\"$host_name\" $selected>$host_name</option>\n";
}
?>
    </select></div>
<?php
if (isset($m)) {
    $onchange = "document.opts.m.value = ''; document.opts.submit();";
} else {
    $onchange = "document.opts.submit();";
}
?>
    &nbsp;Report:&nbsp;
    <div id="select"><select name="g" onchange="<?php echo $onchange; ?>">
      <option value="" selected="selected">All</option>
<?php
    foreach (find_dashboards($env, $c) as $graph_report) {
        if ($graph_report == $g) { $selected = "selected=\"selected\""; }
        else { $selected = ""; }
        print "<option value=\"$graph_report\" $selected>$graph_report</option>\n";
    }
?>
    </select></div>
<?php
if (isset($h)) {
?>
    &nbsp;Jump to:&nbsp;
    <div id="select"><select onchange="location = this.options[this.selectedIndex].value;">
      <option value="#reports" selected="selected">Metric group</option>
<?php
    foreach (find_metrics("$env.$c.$h", $conf['host_metric_group_depth']) as $metric_group => $metric_array) {
        print "<option value=\"#$metric_group\">$metric_group</option>\n";
    }
    print "</select></div>";
}
elseif (isset($c)) {
?>
    &nbsp;Metrics:&nbsp;
    <div id="select"><select name="m" onchange="document.opts.g.value = ''; document.opts.submit()">
      <option value="" selected="selected">None</option>
<?php
    foreach (find_metrics("$env.$c.*", "10") as $metric => $metric_array) {
        if ($metric == $m) { $selected = "selected=\"selected\""; }
        else { $selected = ""; }
        print "<option value=\"$metric\" $selected>$metric</option>\n";
    }
    print "</select></div>";
}
?>
  </div>
  <div id="menu_cell" style="width:250px; height:55px;">
    <div id="menu_row">
      <div id="menu_cell">Graph size:</div>
      <div id="menu_cell">
        <div id="select"><select name="z" onchange="document.opts.submit()">
<?php
foreach (array_keys($conf['graph_sizes']) as $graph_size) {
    if ($graph_size == $z) { $selected = "selected=\"selected\""; }
    else { $selected = ""; }
    print "<option value=\"$graph_size\" $selected>$graph_size</option>\n";
}
?>
        </select></div>
      </div>
    </div>
    <div id="menu_row">
      <div id="menu_cell">Graph scaling:</div>
      <div id="menu_cell">
        <div id="select"><select name="l" onchange="document.opts.submit()">
<?php
    if ($h == "" & $g != "") {
        if ($l == "no") { $selected = "selected=\"selected\""; }
        else { $selected = ""; }
        print "<option value=\"no\" $selected>No</option>\n";
        if ($l == "yes") { $selected = "selected=\"selected\""; }
        else { $selected = ""; }
        print "<option value=\"yes\" $selected>Yes</option>\n";
    }
    else { print "<option value=\"no\" selected=\"selected\">No</option>\n"; }
?>
        </select></div>
      </div>
    </div>
  </div>
  <div id="menu_cell" style="width:200px; height:55px;">
    <div id="menu_row">
      <div id="menu_cell" style="width: 40px;">Start:</div>
      <div id="menu_cell" style="width: 160px;">
      <input name="from" value="<?php print $gs; ?>" style="width: 120px;"><a class="calendar" href="javascript:void(0)" onclick="if(self.gfPop)gfPop.fPopCalendar(document.opts.from);return false;" ></a>
      </div>
    </div>
    <div id="menu_row">
      <div id="menu_cell" style="width: 40px;">End:</div>
      <div id="menu_cell" style="width: 160px;">
      <input name="until" value="<?php print $ge; ?>" style="width: 120px;"><a class="calendar" href="javascript:void(0)" onclick="if(self.gfPop)gfPop.fPopCalendar(document.opts.until);return false;" ></a>
      </div>
    </div>
  </div>

  <div id="menu_cell" style="width:75px; text-align:right;"><button type="submit">Go</button></div>
  </form>
</div>

</div></div>
<iframe width=188 height=166 name="gToday:datetime:agenda.js:gfPop:plugins_time.js" id="gToday:datetime:agenda.js:gfPop:plugins_time.js" src="calendar/ipopeng.htm" scrolling="no" frameborder="0" style="visibility:visible; z-index:999; position:absolute; top:-500px; left:-500px;">
</iframe>
