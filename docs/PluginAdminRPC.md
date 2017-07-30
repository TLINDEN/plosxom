Introduction
============

Admin-Plugin to issue [XML-RPC Pings](http://de.wikipedia.org/wiki/XML-RPC) to various sites (configurable) to announce your blog so that others know you've updated your blog.

This plugin is distributed with plosxom.

Download
========

[admin\_rpc 1.00](http://plosxom.googlecode.com/files/admin_rpc-1.00.zip)

Installation
============

`* Copy the files 'plugin_admin_rpc.php' and 'admin_rpc.nfo' to your plugins/ directory`
`* Copy all tpl files to the directory 'templates/shared/'`
`* Copy the file 'rpcsites.conf' to your etc/ directory`
`* Copy the file 'IXR_Library.inc.php' to your lib/ directory`

Configuration
=============

Edit the file etc/rpcsites.conf either manually or via the admin webinterface and add or remove rpc ping sites.

A comprehensive list of sites available for RPC Ping can be found here:

`* `[`Updateservice` `page` `@wp`](http://codex.wordpress.org/Update_Services)
`* `[`PHPArrow's` `list`](http://www.phparrow.com/Current_Ping_List)
`* you may also google for 'rpc ping list'`
