<UL>
<LI><A HREF="#NAME">NAME

</A><LI><A HREF="#INTRODUCTION">INTRODUCTION

</A><LI><A HREF="#INSTALLATION">INSTALLATION

</A><UL>
<LI><A HREF="#DOWNLOADING">DOWNLOADING

</A><LI><A HREF="#GRAPHICAL%20INSTALLATION">GRAPHICAL INSTALLATION

</A><LI><A HREF="#MANUAL%20CONFIGURATION">MANUAL CONFIGURATION

</A></UL>
<LI><A HREF="#USING%20PLOSXOM">USING PLOSXOM


</A><UL>
<LI><A HREF="#USING%20THE%20ADMIN%20WEBINTERFACE">USING THE ADMIN WEBINTERFACE

</A><LI><A HREF="#MANUALLY%20USAGE">MANUALLY USAGE

</A></UL>
<LI><A HREF="#UPDATING%20PLOSXOM">UPDATING PLOSXOM

</A><LI><A HREF="#PROBLEMS">PROBLEMS

</A><LI><A HREF="#THANKS">THANKS

</A><LI><A HREF="#COPYRIGHT%20AND%20LICENSE">COPYRIGHT AND LICENSE

</A></UL>
<HR>
<H1><A NAME="NAME">NAME

</A></H1>

<P>plosxom - a filesystem based blog tool

</P><H1><A NAME="INTRODUCTION">INTRODUCTION

</A></H1>

<P>Plosxom is a blogsoftware written in PHP. It is a rewrite of phposxom.
Blogposts are stored in textfiles, categories are simple directories.
Plosxom has a comprehensive plugin api, which allows to add almost any
functionality or to replace existing functionality.

</P>
<P>Phposxom were itself a php rewrite of blosxom, but the developer has
abandoned the project. I am using it, because I cannot run perl where
my site is hosted and because it is small. However, the sourcecode of
phposxom were nearly unmaintanable, inefficient and bad styled (sorry
Robert). Since I use it on an every day basis, I decided to completely
rewrite it. Plosxom was born.

</P>
<P>Many of the blosxom forks out there have been abandoned too, especially
the php ports (there are lots of it). None of them seemed to be a
usefull base for me. Most of them justify their decision that if it
were developed further it would become too far to existing projects
like wordpress and they didn't want to re-invent the wheel.

</P>
<P>While I share this view in general, I also see a lot of disadvantages
with wordpress: its storage backend can't be replaced. It depends on
MySQL, which is the worst database system on the planet. With plosxom
you could write a sqlite backend or whatever you like, even a mysql
backend. Wordpress is also a &quot;large biest&quot;, it contains a lot of
source code and thus a lot of problems, especially from the security
point of view.

</P>
<P>Speaking of security - plosxom were developed with strong security in
mind. Since there is almost no cgi input being used in the software,
this goal were not difficult to achive. Everything which gets into
the script will be filtered for bogus characters. Future attacks are
hopefully prevented using this technique.

</P>
<P>There is no administration backend delivered with plosxom, because
you write textfiles (or upload them) - that's all. Without admin
backend, the blog can't be hijacked like most other blogs could be.

</P>
<P>The plosxom core engine is very small. In fact, I wrote it in just
two days. This sounds like a hell of a crap, but it isn't. Much
functionality is provided by plugins, even core functions, such as
text file management, is coded as a plugin. Since we are using smarty
as our template engine there are endless possibilities to enhance
plosxom. You can write a simple plugin along with a simple smarty
function and that's it.

</P>
<P>For now, plosxom doesn't provide built-in comment support due to
security reasons. Instead comments can be outsourced to Haloscan.
However, perhaps someone will write a comment plugin some day,
who knows.


</P><H1><A NAME="INSTALLATION">INSTALLATION

</A></H1>
<H2><A NAME="DOWNLOADING">DOWNLOADING

</A></H2>

<P>Download the latest tarball of plosxom. Copy it to your webspace
and unpack it:

</P>
<P>% tar xvfz plosxom-core-x.xx.tar.gz

</P>
<P>You may also unpack it at home and then upload it recursively.

</P>
<P>Make sure your webserver is able to access all the files. Read
permissions are sufficient if you don't intend to use the admin
webinterface.

</P>
<P>For the graphical installation and the admin webinterface to
work you have to make sure the following directories are writable
for the webserver user:

</P>
<PRE> etc/
 templates/
 plugins/
 images/</PRE>

<P>If unsure how to do it, use the following command:

</P>
<PRE> chmod 777 etc templates plugins images</PRE>
<H2><A NAME="GRAPHICAL%20INSTALLATION">GRAPHICAL INSTALLATION

</A></H2>

<P>This is the recommended way to configure plosxom. Point your
browser to the website where you installed plosxom and call
'install.php'. Eg. say you installed it on http://mysite.com/
then call http://mysite.com/install.php in your browser.

</P>
<P>The installer will create a basic config for you which should
fullfil all requirements of plosxom. It will also check read
and write permissions.

</P>
<P>Finally you have to assign a password for the 'admin' user,
there is no default password or anything. So, if you leave this
step you will not be able to access the admin backend.

</P>
<P>If you are finished you have to remove the file 'install.php'.




</P><H2><A NAME="MANUAL%20CONFIGURATION">MANUAL CONFIGURATION

</A></H2>

<P>Copy the file 'etc/plosxom.conf.dist' to 'etc/plosxom.conf'.
Next edit the configuration file.

</P>
<P>Here is an explanation of the variables:


</P><DL><DT><A NAME="template_path"><B>template_path</B>

</A></DT>
<DD>

<P>Where are the templates located. This directory must be visible
to the webserver, because templates provide css stylesheets, which
are required.

</P>
<P>Example:

</P>
<PRE> template_path = /path/to/webspaceplosxom/templates</PRE>
</DD>
<DT><A NAME="tmp_path"><B>tmp_path</B>

</A></DT>
<DD>

<P>The directory where pre-compiled templates can be stored. Must
be writable for the webserver.

</P>
<P>Example:

</P>
<PRE> tmp_path = /tmp</PRE>
</DD>
<DT><A NAME="data_path"><B>data_path</B>

</A></DT>
<DD>

<P>Here are the postings stored. You can create subdirectories, which
will then considered as categories. Only files with a .txt extension
will be indexed. To hide a file from the blog, change its permissions
so that the webserver can't read it anymore. Consider this as
&quot;draft posting&quot; feature.

</P>
<P>Example:

</P>
<PRE> data_path = /path/to/webspaceplosxom/data</PRE>
</DD>
<DT><A NAME="plugin_path"><B>plugin_path</B>

</A></DT>
<DD>

<P>Where plugins are located.

</P>
<P>Example:

</P>
<PRE> plugin_path = /path/to/webspace/plosxom/plugins</PRE>
</DD>
<DT><A NAME="image_path"><B>image_path</B>

</A></DT>
<DD>

<P>Where images are located, must be accessible from web.

</P>
<P>Example:

</P>
<PRE> image_path = /path/to/webspaceplosxom/images</PRE>
</DD>
<DT><A NAME="template"><B>template</B>

</A></DT>
<DD>

<P>The name of the template to use. Each template is a directory beneath
the template_path.

</P>
<P>Example:

</P>
<PRE> template = default</PRE>
</DD>
<DT><A NAME="blog_name"><B>blog_name</B>

</A></DT>
<DD>

<P>The overall name of your blog.

</P>
<P>Example:

</P>
<PRE> blog_name = BLOGNAME</PRE>
</DD>
<DT><A NAME="blog_title"><B>blog_title</B>

</A></DT>
<DD>

<P>Add a short description, a oneliner is far enogh.

</P>
<P>Example:

</P>
<PRE> blog_title = BLOGDESCRIPTION</PRE>
</DD>
<DT><A NAME="postings"><B>postings</B>

</A></DT>
<DD>

<P>The number of postings to be displayed on the blog front page.

</P>
<P>postings = 10

</P></DD>
<DT><A NAME="author"><B>author</B>

</A></DT>
<DD>
Your name.

</DD>
<DT><A NAME="author_link"><B>author_link</B>

</A></DT>
<DD>
Add a link to your homepage or something which will be used to
underline the author name.

</DD>
<DT><A NAME="whoami"><B>whoami</B>

</A></DT>
<DD>

<P>This is the http url of the plosxom blog.

</P>
<P>Example:

</P>
<PRE> whoami = http://yourpage/plosxom/plosxom.php</PRE>
</DD>
<DT><A NAME="baseurl"><B>baseurl</B>

</A></DT>
<DD>

<P>This is the base url of the blog, without the plosxom.php file.
Will be used to create image or stylesheet links.

</P>
<P>Example:

</P>
<PRE> baseurl = http://yourpage/plosxom/</PRE>
</DD>
<DT><A NAME="imgurl"><B>imgurl</B>

</A></DT>
<DD>

<P>This is the base url to display images.

</P>
<P>Example:

</P>
<PRE> imgurl = http://yourpage/plosxom/images</PRE>
</DD>
<DT><A NAME="image_normal_width"><B>image_normal_width</B>

</A></DT>
<DD>

<P>The width of normalized images in pixel. If you upload an image using the
admin webinterface, smaller images will be generated so that they
fit into your blog design.

</P>
<P>Example:

</P>
<PRE> image_normal_width = 400</PRE>
</DD>
</DL>
<H1><A NAME="USING%20PLOSXOM">USING PLOSXOM


</A></H1>
<H2><A NAME="USING%20THE%20ADMIN%20WEBINTERFACE">USING THE ADMIN WEBINTERFACE

</A></H2>
<H3><A NAME="LOGGING%20IN%20AS%20ADMIN">LOGGING IN AS ADMIN

</A></H3>

<P>Access the url configured in your config as <B>whoami</B> plus <B>/admin</B>, eg:

</P>
<PRE> http://yoursite.com/plosxom.php/admin</PRE>

<P>Some templates also contain a link to the admin interface,
the installer displays a link to the webinterface too, you
may bookmark it.

</P><H3><A NAME="WRITING%20NEW%20BLOG%20POSTING">WRITING NEW BLOG POSTING

</A></H3>

<P>In the admin webinterface you'll see a list of all you
postings available. If it's the first time you are using
plosxom this listing may be empty though.

</P>
<P>Just click the <B>New Posting</B> link, a visual text editor
will appear, now just enter some text, assign it a title,
which is required and assign the posting to a category.

</P>
<P>If there are no categories just enter a new one into the category
field, otherwise click on one of the existing categories listed
below.

</P>
<P><B>Note: a category is just a directory under your data/ path.
Therefore a category will be removed if it doesn't contain any
files (which are postings) anymore.</B>

</P>
<P>Finally click the save button and you are done.

</P><H3><A NAME="IMAGES">IMAGES

</A></H3>

<P>From the <B>Media Manger</B> you can upload and remove images. To upload
a new image just click the b&lt;Upload media file&gt; link and follow the
instructions.

</P><H3><A NAME="BLOG%20CONFIGURATION">BLOG CONFIGURATION

</A></H3>

<P>From the admin webinterface you can also edit all configuration
files residing in your etc directory.

</P>
<P>You can also install new plugins or templates, activate plugins, choose
a template as the current one.

</P>
<P>Some plugins are providing their own admin webinterface hook,
which may be available from the b&lt;Extras&gt; menu or they may create
their own main menu entry.


</P><H2><A NAME="MANUALLY%20USAGE">MANUALLY USAGE

</A></H2>
<H3><A NAME="CREATING%20A%20NEW%20BLOG%20POSTING">CREATING A NEW BLOG POSTING

</A></H3>

<P>Start blogging by creating txt files under the
data_path directory. The first line will be used as the posting
title. Subdirectories under the data directory will be considered
as category.

</P><H3><A NAME="INSTALLING%20PLUGINS">INSTALLING PLUGINS

</A></H3>

<P>To install a plugin, download it from the Plugin page:

</P>
<P>http://code.google.com/p/plosxom/wiki/Plugins

</P>
<P>Copy the .php file to the plugin_path directory. If the plugin
contains further installation instructions follow it precisely.
That's it. Plugin will be loaded automatically by plosxom.

</P>
<P>In most cases you'll have to add some code snippet to your template.
How to do this will be documented in the plugin.


</P><H3><A NAME="INSTALLING%20TEMPLATES">INSTALLING TEMPLATES

</A></H3>

<P>To install a template, download it from the Templates page:

</P>
<P>http://code.google.com/p/plosxom/wiki/Templates

</P>
<P>Uninstall the .zip file inside the template_path directory. This
will create a new directory with the name of the template. Add
this name to your configfile by replacing the variable template
with it.



</P><H1><A NAME="UPDATING%20PLOSXOM">UPDATING PLOSXOM

</A></H1>

<P>To update your installation, go to the Download page and download
the latest patch file. Unpack this patch in your plosxom directory.
A patch tarball only contains the changed files and will not
overwrite any configuration files or templates. If the default
template has changed, a .updated file will be installed instead.
You may then adjust your current template with the changes or
just replace it with the new version.



</P><H1><A NAME="PROBLEMS">PROBLEMS

</A></H1>

<P>If you encounter problems, file a Bugreport if you think it is a
bug:

</P>
<P>http://code.google.com/p/plosxom/issues/list

</P>
<P>Send me an email if you need help installing or configuring
plosxom: &lt;nobrain AT bk DOT ru&gt;.



</P><H1><A NAME="THANKS">THANKS

</A></H1>

<P>Thanks for using plosxom and keeping the opensource movement alive!


</P><H1><A NAME="COPYRIGHT%20AND%20LICENSE">COPYRIGHT AND LICENSE

</A></H1>

<P>Plosxom is copyright (c) 2007-2008 Pali Dhar &lt;nobrain@bk.ru&gt;.

</P>
<P>Licensed under the terms of the &quot;Artistic License&quot; (see LICENSE).


</P>
<P>Smarty is copyright (c) 2001-2005 New Digital Group, Inc.
Licensed under the terms of the GNU Lesser General Public License 2.1
For more details see: http://smarty.php.net/

</P>
