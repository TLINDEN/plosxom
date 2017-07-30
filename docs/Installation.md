Installation
============

[Download](http://code.google.com/p/plosxom/downloads/list) the latest tarball of plosxom. Copy it to your webspace and unpack it

Make sure your webserver is able to access all the files. Read permissions are sufficient.

Manual Configuration
====================

-   Please note: remove the automated installer 'install.php' bevor starting with manual installation\*

Next, edit the plosxom configuration file \*etc/plosxom.conf\*. Here is an explanation of the variables.

Where are the templates located. This directory must be visible to the webserver, because templates provide css stylesheets, which are required.

-   template\_path = //path/to/webspaceplosxom/templates\*

The directory where pre-compiled templates can be stored. Must be writable for the webserver.

-   tmp\_path = /tmp\*

Here are the postings stored. You can create subdirectories, which will then considered as categories. Only files with a .txt extension will be indexed. To hide a file from the blog, change its permissions so that the webserver can't read it anymore. Consider this as "draft posting" feature.

-   data\_path = /path/to/webspaceplosxom/data\*

Where plugins are located.

-   plugin\_path = /path/to/webspace/plosxom/plugins\*

The name of the template. Each template is a directory beneath the template\_path.

-   template = default\*

The overall name of your blog.

-   blog\_name = BLOGNAME\*

Add a short description, a oneliner is far enogh.

-   blog\_title = BLOGDESCRIPTION\*

The number of postings to be displayed on the blog front page.

-   postings = 10\*

Your name.

-   author = yourname\*

Add a link to your homepage or something which will be used to underline the author name.

-   author\_link = <http://yourpage/>\*

This is the http url of the plosxom blog.

-   whoami = <http://yourpage/plosxom/plosxom.php>\*

This is the base url of the blog, without the plosxom.php file. Will be used to create image or stylesheet links.

-   baseurl = <http://yourpage/plosxom/>\*

Automated Installation
======================

After unpacking point your browser to your site and call 'install.php'. It will guide you through the process of the installation and cry about missing permissions. You can also create an initial admin account from it.

Posting
=======

Now you are done. Start blogging by creating txt files under the \*data\_path\* directory. The first line will be used as the posting title.

Plugins
=======

To install a plugin, download it from the [Plugin](http://code.google.com/p/plosxom/wiki/Plugins) page. Copy the \*.php\* file to the \*plugin\_path\* directory. If the plugin contains further installation instructions follow it precisely. That's it. Plugin will be loaded automatically by plosxom.

In most cases you'll have to add some code snippet to your template. How to do this will be documented in the plugin.

Templates
=========

To install a template, download it from the [Templates](http://code.google.com/p/plosxom/wiki/Templates) page. Uninstall the \*.zip\* file inside the \*template\_path\* directory. This will create a new directory with the name of the template. Add this name to your configfile by replacing the variable \*template\* with it.

Update
======

To update your installation, go to the [Download](http://code.google.com/p/plosxom/downloads/list) page and download the latest \*patch\* file. Unpack this patch in your plosxom directory. A patch tarball only contains the changed files and will not overwrite any configuration files or templates. If the default template has changed, a \*.updated\* file will be installed instead. You may then adjust your current template with the changes or just replace it with the new version.

Problems
========

If you encounter problems, file a [Bugreport](http://code.google.com/p/plosxom/issues/list) if you think it is a bug.

Send me an email if you need help installing or configuring plosxom: \*nobrain AT bk DOT ru\*.

Or post questions or suggestions to the [Forum](http://groups.google.com/group/plosxom-discuss).

Thanks
======

Thanks for using plosxom and keeping the opensource movement alive!
