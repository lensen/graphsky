<form name="opts" method="get" action="index.php">
 <div id="menu">
  <input id="menu-checkbox" type="checkbox"/>
  <label for="menu-checkbox"></label>
  <div id="overlay"></div>
  <div id="top_menu">
    <a href="/"><div id="menu_logo"></div></a>
    <div id="selection_menu" class="left">
<?php
$environments=array();
foreach(array_keys( $conf['graphite_servers'] ) as $server) {
    $environment_search = json_decode(file_get_contents($conf['graphite_servers'][$server] . $conf['graphite_search_path'] . $conf['graphite_prefix'] . "*"), TRUE);
    $environments = array_merge($environments,$environment_search['results']);
}
if (sizeof($environments) > 0) { natsort($environments); }
$environments = array_unique(str_replace($conf['graphite_prefix'], "", $environments));
?>
      <!-- Environments -->
      <div class="selection_menu_cell">
        <div class="select"><select name="env" title="Select environment" onchange="if (typeof document.opts.c != 'undefined') { document.opts.c.value = '' }; if (typeof document.opts.h != 'undefined') { document.opts.h.value = '' }; document.opts.submit()">
<?php
print print_dropdown_menus($environments, $env, "All environments");
?>
        </select></div>
      </div>
      <!-- /Environments -->
<?php
if (isset($env) && $env != "") {
    $cluster_search = json_decode(file_get_contents(graphite_server($env) . $conf['graphite_search_path'] . $conf['graphite_prefix'] . "$env.*"), TRUE);
    $clusters = $cluster_search['results']; if (sizeof($clusters) > 1) { natsort($clusters); }
    $cluster_names = str_replace($conf['graphite_prefix'] . "$env.", "", $clusters);
?>
      <!-- Clusters -->
      <div class="selection_menu_cell">
        <div class="select"><select name="c" title="Select cluster" onchange="if (typeof document.opts.h != 'undefined') { document.opts.h.value = '' }; document.opts.submit()">
<?php
print print_dropdown_menus($cluster_names, $c, "All clusters");
?>
        </select></div>
      </div>
      <!-- /Clusters -->
<?php
}
if (isset($c)) {
    $host_search = json_decode(file_get_contents(graphite_server($env) . $conf['graphite_search_path'] . $conf['graphite_prefix'] . "$env.$c.*"), TRUE);
    $hosts = $host_search['results']; if (sizeof($hosts) > 1) { natsort($hosts); }
    $host_names = str_replace($conf['graphite_prefix'] . "$env.$c.", "", $hosts);
?>
      <!-- Hosts -->
      <div class="selection_menu_cell">
        <div class="select"><select name="h" title="Select host" onchange="document.opts.submit()">
<?php
print print_dropdown_menus($host_names, $h, "All hosts");
?>
        </select></div>
      </div>
      <!-- /Hosts -->
<?php
}
?>
    </div>
    <div id="settings_menu" class="right">
      <div class="datetime_menu_cell">
        <input name="from" value="<?php print $gs; ?>" id="from_calendar"/>
      </div>
      <div id="datetime_sep_menu_cell"> - </div>
      <div id="datetime_nm_menu_cell">to</div>
      <div class="datetime_menu_cell">
        <input name="until" value="<?php print $ge; ?>" id="until_calendar"/>
      </div>
      <div id="button_menu_cell"><button id="go_button" type="submit"></button></div>
    </div>
  </div>
  <div id="graph_menu" class="graph_menu-down">
    <!-- Reports -->
<?php
if (isset($m)) { $onchange = "document.opts.m.value = ''; document.opts.submit();"; }
else { $onchange = "document.opts.submit();"; }
?>
    <div class="graph_menu_cell left">
      <div class="select_name_menu_cell">Report:</div>
      <div class="select"><select name="g" title="Select graph report" onchange="<?php echo $onchange; ?>">
<?php
print print_dropdown_menus(find_dashboards($env, $c), $g, "All");
?>
      </select></div>
    </div>
    <!-- /Reports -->
<?php
if (isset($h)) {
?>
      <!-- Metrics -->
    <div class="graph_menu_cell left">
      <div class="select_name_menu_cell">Metric:</div>
      <div class="select"><select title="Jump to a metric group" onchange="location = this.options[this.selectedIndex].value;">
        <option value="#reports" selected="selected">Jump to...</option>
<?php
$host_metrics=find_metrics($env, "$c.$h", $conf['host_metric_group_depth']);
foreach (array_keys($host_metrics) as $metric_group) {
    print "             <option value=\"#$metric_group\">$metric_group</option>\n";
}
?>
      </select></div>
    </div>
    <!-- /Metrics -->
<?php
}
elseif (isset($c)) {
?>
    <!-- Metrics -->
    <div class="graph_menu_cell left">
      <div class="select_name_menu_cell">Metric:</div>
      <div class="select"><select name="m" title="Select a single metric" onchange="document.opts.g.value = ''; document.opts.submit()">
<?php
$all_metrics=find_metrics($env, "$c.*", "10");
print print_dropdown_menus(array_keys($all_metrics), $m, "Select...");
?>
      </select></div>
    </div>
    <!-- /Metrics -->
<?php
}
?>
    <!-- Graph size -->
    <div class="graph_menu_cell right">
      <div class="select_name_menu_cell">Graph size:</div>
      <div class="select"><select name="z" onchange="document.opts.submit()">
<?php
print print_dropdown_menus(array_keys($conf['graph_sizes']), $z, "");
?>
      </select></div>
    </div>
    <!-- /Graph size -->
<?php
if (isset($m)) {
  if ($l == "yes" ) { $checked = "checked"; } else { $checked = ""; }
?>
    <!-- Scale option -->
    <div class="graph_menu_cell right">
      <div class="select_name_menu_cell">Scale metric graphs</div>
      <div class="select"><select name="l" onchange="document.opts.submit()">
<?php
print print_dropdown_menus(array("yes"), $l, "no");
?>
      </select></div>
    </div>
    <!-- /Scale option -->
<?php
}
?>
    <div class="graph_menu_cell right" style="height: 50px;">
      <div id="graph_menu_dropdown_button" onclick="$('.graph_menu_cell').css({'height':'50px'}); $('#graph_menu_dropdown_button').css({'display':'none'}); $('#graph_menu_slideup_button').css({'display':'table'});">&nspb;</div>
      <div id="graph_menu_slideup_button" onclick="$('.graph_menu_cell').css({'height':'0px'}); $('#graph_menu_slideup_button').css({'display':'none'}); $('#graph_menu_dropdown_button').css({'display':'table'});">&nspb;</div>
    </div>
  </div>
 </div>
</form>
