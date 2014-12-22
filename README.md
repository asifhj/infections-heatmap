Infections-heatmap
==================

Private IPs plotting over Google maps

**How does it work?**

**ip_subnet_match.php** will match your private IPs with the subnets and gets the corresponding city and country, based on these details we get the lat and lon for plotting for city and country wise.

To get exact location pointer you have to customise the lookups in your subnet mappings.

CSV files are the source for the markers plotting.

If you have Splunk as the source of data then use and make changes to **getips.php** accordingly.

## FAQ
How do I use this?

Copy and paste the source in any webserver where PHP is enabled.


## Screenshots

![Screenshot 1](https://raw.github.com/asifhj/infections-heatmap/master/Screenshot.png)

![Screenshot 2](https://raw.github.com/asifhj/infections-heatmap/master/Screenshot-1.png)

