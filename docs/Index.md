[1](http://plosxom.googlecode.com/files/network.png)

Introduction
============

Plosxom is a blogsoftware written in PHP by [T. Linden](http://www.daemon.de). It is a rewrite of [phposxom](http://www.celsius1414.com/phposxom/). Blogposts are stored in textfiles, categories are simple directories. Plosxom has a comprehensive plugin api, which allows to add almost any functionality or to replace existing functionality.

Details
=======

Phposxom were itself a php rewrite of [blosxom](http://thesaurus.reference.com/go/http://www.blosxom.com/), but the developer has abandoned the project. I am using it, because I cannot run perl where my site is hosted and because it is small. However, the sourcecode of phposxom were nearly unmaintanable, inefficient and bad styled (sorry Robert). Since I use it on an every day basis, I decided to completely rewrite it. Plosxom was born.

Many of the blosxom forks out there have been abandoned too, especially the php ports (there are lots of it). None of them seemed to be a usefull base for me. Most of them justify their decision that if it were developed further it would become too far to existing projects like wordpress and they didn't want to re-invent the wheel.

While I share this view in general, I also see a lot of disadvantages with wordpress: its storage backend can't be replaced. It depends on MySQL, which is the worst database system on the planet. With plosxom you could write a sqlite backend or whatever you like, even a mysql backend. Wordpress is also a "large biest", it contains a lot of source code and thus a lot of problems, especially from the security point of view.

Speaking of security - plosxom were developed with strong security in mind. Since there is almost no cgi input being used in the software, this goal were not difficult to achive. Everything which gets into the script will be filtered for bogus characters. Future attacks are hopefully prevented using this technique.

The plosxom core engine is very small. In fact, I wrote it in just two days. This sounds like a hell of a crap, but it isn't. Much functionality is provided by plugins, even core functions, such as text file management, is coded as a plugin. Since we are using [smarty](http://thesaurus.reference.com/go/http://smarty.php.net/) as our template engine there are endless possibilities to enhance plosxom. You can write a simple plugin along with a simple smarty function and that's it.

Comments
========

For now, plosxom doesn't provide built-in comment support due to security reasons. Instead comments can be outsourced to [Haloscan](http://www.haloscan.com). However, perhaps someone will write a comment plugin some day, who knows.

Administering plosxom
=====================

-   Plosxom\* can be maintained the traditional unix way on the commandline. Just ssh into your webservers shell account, cd to the data directory, mkdir a new one (viewed as category), cd into that new directory, vi a new file with .txt suffix, first line being the title, :x it and you're almost done. Of yourse you could also write a new posting on your local computer and upload it afterwards.

Adding images, installing templates or plugins - everything can be done from the commandline, you'll always keep full control over your blog.

However, for the lazy people out there, an administration webinterface exists as [PluginAdmin plugin] too. From the admin webinterface you can add postings, install plugins or change the current template as easy as clicking the mouse.

[For the curious - here is a screenshot of the admin backend in action](http://www.23hq.com/PaliDhar/photo/2805302/original)

More Informations
=================

[Download](http://code.google.com/p/plosxom/downloads/list) the latest version, Take a look at the [Installation documentation] or browse the [Plugins plugin-directory].

If you have questions post them to the [Forum](http://groups.google.com/group/plosxom-discuss).

To get an idea of how it looks, visit this [blog site](http://www.vondein.org)!

Update November 2008
====================

-   www.nonlogic.org\*, the site where I did most of the development work and also published my own blog, developer blog as well as the demo blog, is down for unknown reason. The only notice I could found was this topic that where set a while ago on \_\#\#nonlogic\_:


