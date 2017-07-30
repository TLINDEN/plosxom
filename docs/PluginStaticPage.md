Introduction
============

Add static pages to your blog.

Download
========

[version 1.01](http://plosxom.googlecode.com/files/page-1.01.zip)

Install
=======

1. copy page.php and plugin\_admin\_page.php to your plugins directory.

2. copy page.conf to your etc directory and configure it,

`  you only have to assign it a directory which holds static`
`  pages (.txt files too).`

3. copy main\_page.tpl, menu\_page.tpl and submenu\_page.tpl to templates/shared/

4. create the pages/ directory configured in page.conf, make sure it is accessible.

5. edit your index template. Look for this context:

6. before the {else} add this clause:

(change template name accordingly)

7. create .txt files in your pages directory.

8. to link to a static page, add this (eg. to your menu):

(here we are linking to an about page)

That's it
