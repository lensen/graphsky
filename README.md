Graphsky
========

Dashboard for Graphite.

Assumes you have your metrics stored in a hierarchy:
 - [optional_prefix].environment.cluster.hostname.metric

It uses json based templates to specify report graphs (similar to Ganglia-web).
In dashboards.json you can specify which report you want to see for which cluster(s).

Works best with Collectd 5.1 and the write_graphite plugin with the following options:
- Prefix "collectd."
- EscapeCharacter "."
- SeparateInstances true
- StoreRates true
- AlwaysAppendDS true

![Sample dashboard](https://github.com/hyves-org/graphsky/raw/master/img/Graphsky%20screenshot.png)

## Nagios/Icinga/Icinga-web integration

Integrate your graphs in your monitoring tool by including a graph.php link:
 - http://[domainname]/graph.php?s={service_name}&c={hostgroup_name}&h={host_name}&width=1000&height=600&from=-1%20hour

Make sure you specify a "service_name" matching the service_name given by your monitoring solution in your json graph template.