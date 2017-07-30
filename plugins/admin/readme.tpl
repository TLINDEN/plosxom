
<p><a name="__index__"></a></p>
<!-- INDEX BEGIN -->

<ul>

	<li><a href="#name">NAME</a></li>
	<li><a href="#introduction">INTRODUCTION</a></li>
	<li><a href="#installation">INSTALLATION</a></li>
	<ul>

		<li><a href="#downloading">DOWNLOADING</a></li>
		<li><a href="#graphical_installation">GRAPHICAL INSTALLATION</a></li>
		<li><a href="#manual_configuration">MANUAL CONFIGURATION</a></li>
	</ul>

	<li><a href="#search_engine_friendly_urls">SEARCH ENGINE FRIENDLY URLS</a></li>
	<li><a href="#using_plosxom">USING PLOSXOM</a></li>
	<ul>

		<li><a href="#using_the_admin_webinterface">USING THE ADMIN WEBINTERFACE</a></li>
		<ul>

			<li><a href="#logging_in_as_admin">LOGGING IN AS ADMIN</a></li>
			<li><a href="#writing_new_blog_posting">WRITING NEW BLOG POSTING</a></li>
			<li><a href="#images">IMAGES</a></li>
			<li><a href="#blog_configuration">BLOG CONFIGURATION</a></li>
		</ul>

		<li><a href="#manually_usage">MANUALLY USAGE</a></li>
		<ul>

			<li><a href="#creating_a_new_blog_posting">CREATING A NEW BLOG POSTING</a></li>
			<li><a href="#installing_plugins">INSTALLING PLUGINS</a></li>
			<li><a href="#installing_templates">INSTALLING TEMPLATES</a></li>
		</ul>

	</ul>

	<li><a href="#updating_plosxom">UPDATING PLOSXOM</a></li>
	<li><a href="#problems">PROBLEMS</a></li>
	<li><a href="#thanks">THANKS</a></li>
	<li><a href="#copyright_and_license">COPYRIGHT AND LICENSE</a></li>
</ul>
<!-- INDEX END -->

<hr />
<p>
</p>
<h1><a name="name">NAME</a></h1>
<p>plosxom - a filesystem based php blog tool</p>
<p>
</p>
<hr />
<h1><a name="introduction">INTRODUCTION</a></h1>
<p>Plosxom is a blogsoftware written in PHP. It is a rewrite of phposxom.
Blogposts are stored in textfiles, categories are simple directories.
Plosxom has a comprehensive plugin api, which allows to add almost any
functionality or to replace existing functionality.</p>
<p>Phposxom were itself a php rewrite of blosxom, but the developer has
abandoned the project. I am using it, because I cannot run perl where
my site is hosted and because it is small. However, the sourcecode of
phposxom were nearly unmaintanable, inefficient and bad styled (sorry
Robert). Since I use it on an every day basis, I decided to completely
rewrite it. Plosxom was born.</p>
<p>Many of the blosxom forks out there have been abandoned too, especially
the php ports (there are lots of it). None of them seemed to be a
usefull base for me. Most of them justify their decision that if it
were developed further it would become too far to existing projects
like wordpress and they didn't want to re-invent the wheel.</p>
<p>While I share this view in general, I also see a lot of disadvantages
with wordpress: its storage backend can't be replaced. It depends on
MySQL, which is the worst database system on the planet. With plosxom
you could write a sqlite backend or whatever you like, even a mysql
backend. Wordpress is also a ``large biest'', it contains a lot of
source code and thus a lot of problems, especially from the security
point of view. Even wordpress templates are in fact just plain PHP
files, they contain executable code, they can't be edited with a
HTML editor, look weird and are difficult to maintain.</p>
<p>Speaking of security - plosxom were developed with strong security in
mind. Since there is almost no cgi input being used in the software,
this goal were not difficult to achive. Everything which gets into
the script will be filtered for bogus characters. Future attacks are
hopefully prevented using this technique.</p>
<p>There is now a administration backend delivered with plosxom which is
enabled by default. See <a href="#using_the_admin_webinterface">USING THE ADMIN WEBINTERFACE</a> for further
details how to use it. Login to the admin interface is provided as
<strong>HTTP Basic Authentication</strong>, which is of course not neccessarily
more secure than CGI login using form variables and session cookies.
However, it makes it more difficult for attackers to bypass the
login process using screwed CGI variable input or something. In the
future we will also support <strong>HTTP Digest authentication</strong> which
actually IS more secure, because no cleartext password will be transmitted
over the wire.</p>
<p>If you still feel unsecure with the admin backend and are happy
with unix commandline control of the blog (which in fact just consists
of creating textfiles, directories and uploading images), then you
may want to disable the admin backend. Just create a file <strong>admin.disabled</strong>
in your plugins directory (where the file <strong>admin.php</strong> resides).</p>
<p>The plosxom core engine is very small. In fact, I wrote it in just
two days. This sounds like a hell of a crap, but it isn't. Much
functionality is provided by plugins, even core functions, such as
text file management, is coded as a plugin. Since we are using smarty
as our template engine there are endless possibilities to enhance
plosxom. You can write a simple plugin along with a simple smarty
function and that's it.</p>
<p>For now, plosxom doesn't provide built-in comment support due to
security reasons. Instead comments can be outsourced to Haloscan.
However, perhaps someone will write a comment plugin some day,
who knows.</p>
<p>
</p>
<hr />
<h1><a name="installation">INSTALLATION</a></h1>
<p>
</p>
<h2><a name="downloading">DOWNLOADING</a></h2>
<p>Download the latest tarball of plosxom. Copy it to your webspace
and unpack it:</p>
<p>% tar xvfz plosxom-core-x.xx.tar.gz</p>
<p>You may also unpack it at home and then upload it recursively.</p>
<p>Make sure your webserver is able to access all the files. Read
permissions are sufficient if you don't intend to use the admin
webinterface.</p>
<p>For the graphical installation and the admin webinterface to
work you have to make sure the following directories are writable
for the webserver user:</p>
<pre>
 etc/
 templates/
 plugins/
 images/</pre>
<p>If unsure how to do it, use the following command:</p>
<pre>
 chmod 777 etc templates plugins images</pre>
<p>
</p>
<h2><a name="graphical_installation">GRAPHICAL INSTALLATION</a></h2>
<p>This is the recommended way to configure plosxom. Point your
browser to the website where you installed plosxom and call
'install.php'. Eg. say you installed it on <a href="http://mysite.com/">http://mysite.com/</a>
then call <a href="http://mysite.com/install.php">http://mysite.com/install.php</a> in your browser.</p>
<p>The installer will create a basic config for you which should
fullfil all requirements of plosxom. It will also check read
and write permissions.</p>
<p>Finally you have to assign a password for the 'admin' user,
there is no default password or anything. So, if you leave this
step you will not be able to access the admin backend.</p>
<p>If you are finished you have to remove the file 'install.php'.</p>
<p>
</p>
<h2><a name="manual_configuration">MANUAL CONFIGURATION</a></h2>
<p>Copy the file 'etc/plosxom.conf.dist' to 'etc/plosxom.conf'.
Next edit the configuration file.</p>
<p>Here is an explanation of the variables:</p>
<dl>
<dt><strong><a name="item_template_path"><strong>template_path</strong></a></strong>

<dd>
<p>Where are the templates located. This directory must be visible
to the webserver, because templates provide css stylesheets, which
are required.</p>
</dd>
<dd>
<p>Example:</p>
</dd>
<dd>
<pre>
 template_path = /path/to/webspaceplosxom/templates</pre>
</dd>
</li>
<dt><strong><a name="item_tmp_path"><strong>tmp_path</strong></a></strong>

<dd>
<p>The directory where pre-compiled templates can be stored. Must
be writable for the webserver.</p>
</dd>
<dd>
<p>Example:</p>
</dd>
<dd>
<pre>
 tmp_path = /tmp</pre>
</dd>
</li>
<dt><strong><a name="item_data_path"><strong>data_path</strong></a></strong>

<dd>
<p>Here are the postings stored. You can create subdirectories, which
will then considered as categories. Only files with a .txt extension
will be indexed. To hide a file from the blog, change its permissions
so that the webserver can't read it anymore. Consider this as
``draft posting'' feature.</p>
</dd>
<dd>
<p>Example:</p>
</dd>
<dd>
<pre>
 data_path = /path/to/webspaceplosxom/data</pre>
</dd>
</li>
<dt><strong><a name="item_plugin_path"><strong>plugin_path</strong></a></strong>

<dd>
<p>Where plugins are located.</p>
</dd>
<dd>
<p>Example:</p>
</dd>
<dd>
<pre>
 plugin_path = /path/to/webspace/plosxom/plugins</pre>
</dd>
</li>
<dt><strong><a name="item_image_path"><strong>image_path</strong></a></strong>

<dd>
<p>Where images are located, must be accessible from web.</p>
</dd>
<dd>
<p>Example:</p>
</dd>
<dd>
<pre>
 image_path = /path/to/webspaceplosxom/images</pre>
</dd>
</li>
<dt><strong><a name="item_template"><strong>template</strong></a></strong>

<dd>
<p>The name of the template to use. Each template is a directory beneath
the template_path.</p>
</dd>
<dd>
<p>Example:</p>
</dd>
<dd>
<pre>
 template = default</pre>
</dd>
</li>
<dt><strong><a name="item_blog_name"><strong>blog_name</strong></a></strong>

<dd>
<p>The overall name of your blog.</p>
</dd>
<dd>
<p>Example:</p>
</dd>
<dd>
<pre>
 blog_name = BLOGNAME</pre>
</dd>
</li>
<dt><strong><a name="item_blog_title"><strong>blog_title</strong></a></strong>

<dd>
<p>Add a short description, a oneliner is far enogh.</p>
</dd>
<dd>
<p>Example:</p>
</dd>
<dd>
<pre>
 blog_title = BLOGDESCRIPTION</pre>
</dd>
</li>
<dt><strong><a name="item_postings"><strong>postings</strong></a></strong>

<dd>
<p>The number of postings to be displayed on the blog front page.</p>
</dd>
<dd>
<p>postings = 10</p>
</dd>
</li>
<dt><strong><a name="item_author"><strong>author</strong></a></strong>

<dd>
<p>Your name.</p>
</dd>
</li>
<dt><strong><a name="item_author_link"><strong>author_link</strong></a></strong>

<dd>
<p>Add a link to your homepage or something which will be used to
underline the author name.</p>
</dd>
</li>
<dt><strong><a name="item_whoami"><strong>whoami</strong></a></strong>

<dd>
<p>This is the http url of the plosxom blog.</p>
</dd>
<dd>
<p>Example:</p>
</dd>
<dd>
<pre>
 whoami = <a href="http://yourpage/plosxom/plosxom.php">http://yourpage/plosxom/plosxom.php</a></pre>
</dd>
</li>
<dt><strong><a name="item_baseurl"><strong>baseurl</strong></a></strong>

<dd>
<p>This is the base url of the blog, without the plosxom.php file.
Will be used to create image or stylesheet links.</p>
</dd>
<dd>
<p>Example:</p>
</dd>
<dd>
<pre>
 baseurl = <a href="http://yourpage/plosxom/">http://yourpage/plosxom/</a></pre>
</dd>
</li>
<dt><strong><a name="item_imgurl"><strong>imgurl</strong></a></strong>

<dd>
<p>This is the base url to display images.</p>
</dd>
<dd>
<p>Example:</p>
</dd>
<dd>
<pre>
 imgurl = <a href="http://yourpage/plosxom/images">http://yourpage/plosxom/images</a></pre>
</dd>
</li>
<dt><strong><a name="item_image_normal_width"><strong>image_normal_width</strong></a></strong>

<dd>
<p>The width of normalized images in pixel. If you upload an image using the
admin webinterface, smaller images will be generated so that they
fit into your blog design.</p>
</dd>
<dd>
<p>Example:</p>
</dd>
<dd>
<pre>
 image_normal_width = 400</pre>
</dd>
</li>
</dl>
<p>
</p>
<hr />
<h1><a name="search_engine_friendly_urls">SEARCH ENGINE FRIENDLY URLS</a></h1>
<p>By default plosxom uses urls like this:</p>
<pre>
 <a href="http://loria/plosxom.php/bullshit/aufschwung">http://loria/plosxom.php/bullshit/aufschwung</a></pre>
<p>While this is in fact still ok for search engines and looks
ok, it still contains the PHP file <strong>plosxom.php</strong> in the url.</p>
<p>You may circumvent this using the apache module <strong>MOD_REWRITE</strong>.</p>
<p>Fist, create a <strong>.htaccess</strong> file in the directory which contains the
plosxom.php like this:</p>
<pre>
 &lt;IfModule mod_rewrite.c&gt;
   RewriteEngine On
   RewriteCond %{REQUEST_FILENAME} !-f
   RewriteCond %{REQUEST_FILENAME} !-d
   RewriteRule    ^(.+)/(.+)$   /plosxom.php/$1/$2  [PT,L]
   RewriteCond %{REQUEST_FILENAME} !-f
   RewriteCond %{REQUEST_FILENAME} !-d
   RewriteRule    ^(.+)$        /plosxom.php/$1     [PT,L]
 &lt;/IfModule&gt;</pre>
<p>If plosxom is not installed in the document root but inside a
subdirectory, eg:</p>
<pre>
 <a href="http://loria/blog/plosxom.php">http://loria/blog/plosxom.php</a></pre>
<p>then you have to add a base directory for mod_rewrite in the
.htaccess file:</p>
<pre>
 RewriteBase /blog</pre>
<p>Next, you have to modify the configuration of plosxom so that
it generates appropriate links. The variable to modify is
<strong>whoami</strong>.</p>
<p>If it contains for example the following:</p>
<pre>
 whoami = <a href="http://loria/plosxom.php">http://loria/plosxom.php</a></pre>
<p>change it to:</p>
<pre>
 whoami = <a href="http://loria">http://loria</a></pre>
<p>Note that we added no trailing slash, plosxom will add slashes
as neccessary.</p>
<p>
</p>
<hr />
<h1><a name="using_plosxom">USING PLOSXOM</a></h1>
<p>
</p>
<h2><a name="using_the_admin_webinterface">USING THE ADMIN WEBINTERFACE</a></h2>
<p>
</p>
<h3><a name="logging_in_as_admin">LOGGING IN AS ADMIN</a></h3>
<p>Access the url configured in your config as <strong>whoami</strong> plus <strong>/admin</strong>, eg:</p>
<pre>
 <a href="http://yoursite.com/plosxom.php/admin">http://yoursite.com/plosxom.php/admin</a></pre>
<p>Some templates also contain a link to the admin interface,
the installer displays a link to the webinterface too, you
may bookmark it.</p>
<p>
</p>
<h3><a name="writing_new_blog_posting">WRITING NEW BLOG POSTING</a></h3>
<p>In the admin webinterface you'll see a list of all you
postings available. If it's the first time you are using
plosxom this listing may be empty though.</p>
<p>Just click the <strong>New Posting</strong> link, a visual text editor
will appear, now just enter some text, assign it a title,
which is required and assign the posting to a category.</p>
<p>If there are no categories just enter a new one into the category
field, otherwise click on one of the existing categories listed
below.</p>
<p><strong>Note: a category is just a directory under your data/ path.
Therefore a category will be removed if it doesn't contain any
files (which are postings) anymore.</strong></p>
<p>Finally click the save button and you are done.</p>
<p>
</p>
<h3><a name="images">IMAGES</a></h3>
<p>From the <strong>Media Manger</strong> you can upload and remove images. To upload
a new image just click the b&lt;Upload media file&gt; link and follow the
instructions.</p>
<p>
</p>
<h3><a name="blog_configuration">BLOG CONFIGURATION</a></h3>
<p>From the admin webinterface you can also edit all configuration
files residing in your etc directory.</p>
<p>You can also install new plugins or templates, activate plugins, choose
a template as the current one.</p>
<p>Some plugins are providing their own admin webinterface hook,
which may be available from the b&lt;Extras&gt; menu or they may create
their own main menu entry.</p>
<p>
</p>
<h2><a name="manually_usage">MANUALLY USAGE</a></h2>
<p>
</p>
<h3><a name="creating_a_new_blog_posting">CREATING A NEW BLOG POSTING</a></h3>
<p>Start blogging by creating txt files under the
data_path directory. The first line will be used as the posting
title. Subdirectories under the data directory will be considered
as category.</p>
<p>
</p>
<h3><a name="installing_plugins">INSTALLING PLUGINS</a></h3>
<p>To install a plugin, download it from the Plugin page:</p>
<p><a href="http://code.google.com/p/plosxom/wiki/Plugins">http://code.google.com/p/plosxom/wiki/Plugins</a></p>
<p>Copy the .php file to the plugin_path directory. If the plugin
contains further installation instructions follow it precisely.
That's it. Plugin will be loaded automatically by plosxom.</p>
<p>In most cases you'll have to add some code snippet to your template.
How to do this will be documented in the plugin.</p>
<p>
</p>
<h3><a name="installing_templates">INSTALLING TEMPLATES</a></h3>
<p>To install a template, download it from the Templates page:</p>
<p><a href="http://code.google.com/p/plosxom/wiki/Templates">http://code.google.com/p/plosxom/wiki/Templates</a></p>
<p>Uninstall the .zip file inside the template_path directory. This
will create a new directory with the name of the template. Add
this name to your configfile by replacing the variable template
with it.</p>
<p>
</p>
<hr />
<h1><a name="updating_plosxom">UPDATING PLOSXOM</a></h1>
<p>To update your installation, go to the Download page and download
the latest patch file. Unpack this patch in your plosxom directory.
A patch tarball only contains the changed files and will not
overwrite any configuration files or templates. If the default
template has changed, a .updated file will be installed instead.
You may then adjust your current template with the changes or
just replace it with the new version.</p>
<p>
</p>
<hr />
<h1><a name="problems">PROBLEMS</a></h1>
<p>If you encounter problems, file a Bugreport if you think it is a
bug:</p>
<p><a href="http://code.google.com/p/plosxom/issues/list">http://code.google.com/p/plosxom/issues/list</a></p>
<p>Send me an email if you need help installing or configuring
plosxom: &lt;nobrain AT bk DOT ru&gt;.</p>
<p>
</p>
<hr />
<h1><a name="thanks">THANKS</a></h1>
<p>Thanks for using plosxom and keeping the opensource movement alive!</p>
<p>
</p>
<hr />
<h1><a name="copyright_and_license">COPYRIGHT AND LICENSE</a></h1>
<p>Plosxom is copyright (c) 2007-2008 Pali Dhar &lt;<a href="mailto:nobrain@bk.ru">nobrain@bk.ru</a>&gt;.</p>
<p>Licensed under the terms of the ``Artistic License'' (see LICENSE).</p>
<p>Smarty is copyright (c) 2001-2005 New Digital Group, Inc.
Licensed under the terms of the GNU Lesser General Public License 2.1
For more details see: <a href="http://smarty.php.net/">http://smarty.php.net/</a></p>

