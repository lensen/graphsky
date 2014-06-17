Graphsky
========

Dashboard for Graphite.

Assumes you have your metrics stored in a hierarchy:
 - [optional_prefix].environment.cluster.hostname.metric

It uses json based templates to specify report graphs (similar to Ganglia-web).
In dashboards.json you can specify which report you want to see for which evironment(s) / cluster(s).

Works best with Collectd 5.x and the [write_graphite](https://collectd.org/wiki/index.php/Plugin:Write_Graphite) plugin with the following options:
- Prefix "collectd."
- EscapeCharacter "."
- SeparateInstances true
- StoreRates true
- AlwaysAppendDS true

Web interface:
![Sample dashboard](https://raw.github.com/hyves-org/graphsky/master/img/Graphsky%20screenshot.png)

Mobile interface:
![Mobile dashboard](https://raw.github.com/hyves-org/graphsky/master/img/Graphsky%20screenshot%20mobile.png)
![Mobile dashboard menu](https://raw.github.com/hyves-org/graphsky/master/img/Graphsky%20screenshot%20mobile%20menu.png)

## Configuration

Just copy conf_default.php to conf.php or create a new file in which you only override the settings you want/need to change.
* **dashboard_domainname**: Specify the URL under which you will be running Graphsky here
* **use_random_graph_domainname**: This will cause Graphsky to use random graph domainnames (for example: http://img18.graphsky.example.com) to speed up image loading in browsers. To disable this, set it to False. Note: If you are going to use this, make sure you have a wildcard record in your DNS for this.
* **graphite_url_base**: Specify the base URL for you Graphite webinterface here.
* **graphite_prefix**: Use this setting to specify a generic prefix of the metrics you'd like to graph. In case of Collectd metrics, this would be the same as the 'Prefix' option of the write_grapghite plugin for Collectd.

## Nagios/Icinga/Icinga-web integration

Integrate your graphs in your monitoring tool by including a graph.php link:
 - http://[domainname]/graph.php?s={service_name}&c={hostgroup_name}&h={host_name}&width=1000&height=600&from=-1%20hour

Make sure you specify a "service_name" matching the service_name given by your monitoring solution in your json graph template.  

***
Button icons are based on [IKONS by Piotr Kwiatkowski](http://ikons.piotrkwiatkowski.co.uk/)
