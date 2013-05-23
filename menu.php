      <div id="menu"><div class="menu_row">
        <form name="opts" method="get" action="index.php">
          <div class="menu_cell left">
            <div class="menu_cell">
              <div class="select"><select name="env" onchange="document.opts.c.value = ''; document.opts.h.value = ''; document.opts.submit()">
<?php
$environment_search = json_decode(file_get_contents($conf['graphite_search_url'] . $conf['graphite_prefix'] . "*"), TRUE);
$environments = $environment_search['results']; natsort($environments);
$environments = str_replace($conf['graphite_prefix'], "", $environments);
$cluster_search = json_decode(file_get_contents($conf['graphite_search_url'] . $conf['graphite_prefix'] . "$env.*"), TRUE);
$clusters = $cluster_search['results']; natsort($clusters);
$clusters = str_replace($conf['graphite_prefix'] . "$env.", "", $clusters);
$host_search = json_decode(file_get_contents($conf['graphite_search_url'] . $conf['graphite_prefix'] . "$env.$c.*"), TRUE);
$hosts = $host_search['results']; natsort($hosts);
$hosts = str_replace($conf['graphite_prefix'] . "$env.$c.", "", $hosts);

print print_dropdown_menus($environments, $env, "Select environment");
?>
              </select></div>
            </div>
            <div class="menu_cell">&gt;</div>
            <div class="menu_cell">
              <div class="select"><select name="c" onchange="document.opts.h.value = ''; document.opts.submit()">
<?php
print print_dropdown_menus($clusters, $c, "All clusters");
?>
              </select></div>
            </div>
            <div class="menu_cell">&gt;</div>
            <div class="menu_cell">
              <div class="select"><select name="h" onchange="document.opts.submit()">
<?php
print print_dropdown_menus($hosts, $h, "All hosts");
?>
              </select></div>
            </div>
<?php
if (isset($m)) { $onchange = "document.opts.m.value = ''; document.opts.submit();"; }
else { $onchange = "document.opts.submit();"; }
?>
            <div class="menu_cell">&gt;</div>
            <div class="menu_cell">
              <div class="select"><select name="g" onchange="<?php echo $onchange; ?>">
<?php
print print_dropdown_menus(find_dashboards($env, $c), $g, "All reports");
?>
              </select></div>
            </div>
<?php
if (isset($h)) {
?>
            <div class="menu_cell">&gt;</div>
            <div class="menu_cell">
              <div class="select"><select onchange="location = this.options[this.selectedIndex].value;">
<?php
$host_metrics=array_keys(find_metrics("$env.$c.$h", $conf['host_metric_group_depth']));
print print_dropdown_menus($host_metrics, "", "Go to metricgroup");
?>
              </select></div>
            </div>
<?php
}
elseif (isset($c)) {
?>
            <div class="menu_cell">&gt;</div>
            <div class="menu_cell">
              <div class="select"><select name="m" onchange="document.opts.g.value = ''; document.opts.submit()">
<?php
$all_metrics=array_keys(find_metrics("$env.$c.*", "10"));
print print_dropdown_menus($all_metrics, $m, "All metrics");
?>
              </select></div>
            </div>
<?php
}
?>
          </div>
          <div class="menu_cell right">
            <div class="menu_cell">from</div>
            <div class="menu_cell">
              <input name="from" value="<?php print $gs; ?>" id="from_calendar"/>
            </div>
            <div class="menu_cell">until</div>
            <div class="menu_cell">
              <input name="until" value="<?php print $ge; ?>" id="until_calendar"/>
            </div>
            <div class="menu_cell"><button type="submit">Go</button></div>
            <div class="menu_cell">
              <a href="javascript:;" class="small_menu_button"><img src="img/small_menu_holo.png" width="16" height="20"/></a>
              <div id="small_menu">
                <div class="smell_menu_cell small_menu_title">
                  Graph settings
                </div>
                <hr />
                <div class="smell_menu_cell">
                  size:
                </div>
                <div class="smell_menu_cell">
                  <div class="select"><select name="z" onchange="document.opts.submit()">
<?php
print print_dropdown_menus(array_keys($conf['graph_sizes']), $z, "");
?>
                  </select></div>
                </div>
                </div>
              </div>
          </div>
        </form>
      </div></div>
