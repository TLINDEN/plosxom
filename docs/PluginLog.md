Introduction
============

A plugin to add access logging capabilities to the blog. This is usefull if your webserver doesn't log or if you don't have access to the log (like me).

Download
========

[version 1.00](http://plosxom.googlecode.com/files/log-1.00.zip)

Install
=======

1. copy log.php to your plugins directory.

2. copy log.conf to your etc directory and configure it. the config contains hints what can be changed. Most important is "logdir", see below

3. create the log directory (configured as "logdir" in log.conf, make sure, the webserver has write access. If your user/group membership differs from weberserver, add the setguid bit, eg:

This way, at least the group ownerships of files created there in will be preserved so you can still maintain the logs.
