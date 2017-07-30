Introduction
============

Add links to your blog using simple config files. Multiple configs can be used, eg. one for personal links, one for blogroll, etc.

Download
========

[version 1.00](http://plosxom.googlecode.com/files/links-1.00.zip)

Install
=======

1. copy links.php to your plugins directory.

2. create one or more link configs in your etc directory. In your template you can then loop over links by config. This makes it possible to maintain page links separately from blogroll or the like.

Format is simple:

for example:

Empty lines and lines starting with \# will be ignored as always in in configs.

3. add some code to your template to loop over the links of a particular links config file, eg:

The template function "links" exported by the links module requires 2 parameters:

`* config   - the link config file you are referring to`
`* template - how the link shall appear. %1 will be used for the url and %2 for the link name.`

That's it - now add links to your link configs as you like!
