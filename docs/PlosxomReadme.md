plosxom - a filesystem based blog tool
======================================

INTRODUCTION
------------

Plosxom is a blogsoftware written in PHP. It is a rewrite of phposxom. Blogposts are stored in textfiles, categories are simple directories. Plosxom has a comprehensive plugin api, which allows to add almost any functionality or to replace existing functionality.

Phposxom were itself a php rewrite of blosxom, but the developer has abandoned the project. I am using it, because I cannot run perl where my site is hosted and because it is small. However, the sourcecode of phposxom were nearly unmaintanable, inefficient and bad styled (sorry Robert). Since I use it on an every day basis, I decided to completely rewrite it. Plosxom was born.

Many of the blosxom forks out there have been abandoned too, especially the php ports (there are lots of it). None of them seemed to be a usefull base for me. Most of them justify their decision that if it were developed further it would become too far to existing projects like wordpress and they didn't want to re-invent the wheel.

While I share this view in general, I also see a lot of disadvantages with wordpress: its storage backend can't be replaced. It depends on MySQL, which is the worst database system on the planet. With plosxom you could write a sqlite backend or whatever you like, even a mysql backend. Wordpress is also a "large biest", it contains a lot of source code and thus a lot of problems, especially from the security point of view.

Speaking of security - plosxom were developed with strong security in mind. Since there is almost no cgi input being used in the software, this goal were not difficult to achive. Everything which gets into the script will be filtered for bogus characters. Future attacks are hopefully prevented using this technique.

There is no administration backend delivered with plosxom, because you write textfiles (or upload them) - that's all. Without admin backend, the blog can't be hijacked like most other blogs could be.

The plosxom core engine is very small. In fact, I wrote it in just two days. This sounds like a hell of a crap, but it isn't. Much functionality is provided by plugins, even core functions, such as text file management, is coded as a plugin. Since we are using smarty as our template engine there are endless possibilities to enhance plosxom. You can write a simple plugin along with a simple smarty function and that's it.

For now, plosxom doesn't provide built-in comment support due to security reasons. Instead comments can be outsourced to Haloscan. However, perhaps someone will write a comment plugin some day, who knows.

INSTALLATION
------------

### DOWNLOADING

Download the latest tarball of plosxom. Copy it to your webspace and unpack it:

You may also unpack it at home and then upload it recursively.

Make sure your webserver is able to access all the files. Read permissions are sufficient if you don't intend to use the admin webinterface.

For the graphical installation and the admin webinterface to work you have to make sure the following directories are writable for the webserver user:

If unsure how to do it, use the following command:

### GRAPHICAL INSTALLATION

This is the recommended way to configure plosxom. Point your browser to the website where you installed plosxom and call 'install.php'. Eg. say you installed it on <http://mysite.com/> then call <http://mysite.com/install.php> in your browser.

The installer will create a basic config for you which should fullfil all requirements of plosxom. It will also check read and write permissions.

Finally you have to assign a password for the 'admin' user, there is no default password or anything. So, if you leave this step you will not be able to access the admin backend.

If you are finished you have to remove the file 'install.php'.

### MANUAL CONFIGURATION

Copy the file 'etc/plosxom.conf.dist' to 'etc/plosxom.conf'. Next edit the configuration file.

Here is an explanation of the variables:

template\_path

`   Where are the templates located. This directory must be visible to the webserver, because templates provide css stylesheets, which are required.`

`   Example:`

`    template_path = /path/to/webspaceplosxom/templates`

tmp\_path

`   The directory where pre-compiled templates can be stored. Must be writable for the webserver.`

`   Example:`

`    tmp_path = /tmp`

data\_path

`   Here are the postings stored. You can create subdirectories, which will then considered as categories. Only files with a .txt extension will be indexed. To hide a file from the blog, change its permissions so that the webserver can't read it anymore. Consider this as "draft posting" feature.`

`   Example:`

`    data_path = /path/to/webspaceplosxom/data`

plugin\_path

`   Where plugins are located.`

`   Example:`

`    plugin_path = /path/to/webspace/plosxom/plugins`

image\_path

`   Where images are located, must be accessible from web.`

`   Example:`

`    image_path = /path/to/webspaceplosxom/images`

template

`   The name of the template to use. Each template is a directory beneath the template_path.`

`   Example:`

`    template = default`

blog\_name

`   The overall name of your blog.`

`   Example:`

`    blog_name = BLOGNAME`

blog\_title

`   Add a short description, a oneliner is far enogh.`

`   Example:`

`    blog_title = BLOGDESCRIPTION`

postings

`   The number of postings to be displayed on the blog front page.`

`   postings = 10 `

author

`   Your name. `

author\_link

`   Add a link to your homepage or something which will be used to underline the author name. `

whoami

`   This is the http url of the plosxom blog.`

`   Example:`

`    whoami = `[`http://yourpage/plosxom/plosxom.php`](http://yourpage/plosxom/plosxom.php)

baseurl

`   This is the base url of the blog, without the plosxom.php file. Will be used to create image or stylesheet links.`

`   Example:`

`    baseurl = `[`http://yourpage/plosxom/`](http://yourpage/plosxom/)

imgurl

`   This is the base url to display images.`

`   Example:`

`    imgurl = `[`http://yourpage/plosxom/images`](http://yourpage/plosxom/images)

image\_normal\_width

`   The width of normalized images in pixel. If you upload an image using the admin webinterface, smaller images will be generated so that they fit into your blog design.`

`   Example:`

`    image_normal_width = 400`

USING PLOSXOM
=============

USING THE ADMIN WEBINTERFACE
----------------------------

### LOGGING IN AS ADMIN

Access the url configured in your config as whoami plus /admin, eg:

Some templates also contain a link to the admin interface, the installer displays a link to the webinterface too, you may bookmark it.

### WRITING NEW BLOG POSTING

In the admin webinterface you'll see a list of all you postings available. If it's the first time you are using plosxom this listing may be empty though.

Just click the New Posting link, a visual text editor will appear, now just enter some text, assign it a title, which is required and assign the posting to a category.

If there are no categories just enter a new one into the category field, otherwise click on one of the existing categories listed below.

Note: a category is just a directory under your data/ path. Therefore a category will be removed if it doesn't contain any files (which are postings) anymore.

Finally click the save button and you are done.

### IMAGES

From the Media Manger you can upload and remove images. To upload a new image just click the \*Upload media file\* link and follow the instructions.

### BLOG CONFIGURATION

From the admin webinterface you can also edit all configuration files residing in your etc directory.

You can also install new plugins or templates, activate plugins, choose a template as the current one.

Some plugins are providing their own admin webinterface hook, which may be available from the \*Extras\* menu or they may create their own main menu entry.

MANUALLY USAGE
--------------

### CREATING A NEW BLOG POSTING

Start blogging by creating txt files under the data\_path directory. The first line will be used as the posting title. Subdirectories under the data directory will be considered as category.

### INSTALLING PLUGINS

To install a plugin, download it from the Plugin page:

[1](http://code.google.com/p/plosxom/wiki/Plugins)

Copy the .php file to the plugin\_path directory. If the plugin contains further installation instructions follow it precisely. That's it. Plugin will be loaded automatically by plosxom.

In most cases you'll have to add some code snippet to your template. How to do this will be documented in the plugin.

### INSTALLING TEMPLATES

To install a template, download it from the Templates page:

[2](http://code.google.com/p/plosxom/wiki/Templates)

Uninstall the .zip file inside the template\_path directory. This will create a new directory with the name of the template. Add this name to your configfile by replacing the variable template with it.

UPDATING PLOSXOM
----------------

To update your installation, go to the Download page and download the latest patch file. Unpack this patch in your plosxom directory. A patch tarball only contains the changed files and will not overwrite any configuration files or templates. If the default template has changed, a .updated file will be installed instead. You may then adjust your current template with the changes or just replace it with the new version. PROBLEMS

If you encounter problems, file a Bugreport if you think it is a bug:

[3](http://code.google.com/p/plosxom/issues/list)

Send me an email if you need help installing or configuring plosxom: <nobrain AT bk DOT ru>.

THANKS
======

Thanks for using plosxom and keeping the opensource movement alive!

COPYRIGHT AND LICENSE
=====================

Plosxom is copyright (c) 2007-2008 Pali Dhar <nobrain@bk.ru>.

Licensed under the terms of the "Artistic License" (see LICENSE).

Smarty is copyright (c) 2001-2005 New Digital Group, Inc. Licensed under the terms of the GNU Lesser General Public License 2.1 For more details see: [4](http://smarty.php.net/)
