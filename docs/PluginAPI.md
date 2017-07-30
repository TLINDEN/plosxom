Table of content
================

`* [#Introduction Introduction]`
`* [#What_can_plugins_do What can plugins do]`
`* [#How_Plugins_work How Plugins work]`
`* [#Plugin_packaging Plugin packaging]`
`* [#The_Plugin_class The Plugin class]`
`* [#Registering_a_plugin Registering a plugin]`
`* [#The_Registry_class The Registry class]`
`* [#The_config_array The config array]`
`* [#Plugin_configuration Plugin configuration]`
`* [#The_Smarty_object The Smarty object]`
`* [#Registering_a_hook Registering a hook]`
`* [#Available_hooks Available hooks]`
`  * [#hook_url_filter hook_url_filter]`
`  * [#hook_send_header hook_send_header]`
`  * [#hook_storage_fetch hook_storage_fetch]`
`  * [#hook_storage_fetchall hook_storage_fetchall]`
`  * [#hook_content hook_content]`
`* [#Localization_support Localization support]`

Introduction
============

This is the complete API available for plugin developers.

What can plugins do
===================

Plugins can do almost anything - you name it. Here is a list of the possibilities, although it does not claim to be complete:

`* modify postings on the fly (e.g. [PluginMore read-more plugin])`
`* add stuff to postings (e.g. [PluginTechnorati technorati plugin])`
`* completely generate content (e.g. [PluginLinks links plugin])`
`* add or modify HTTP headers (e.g. [PluginFeed feed plugin])`
`* filter postings based on some arbitrary criteria (e.g. [CategoriesPlugin categories plugin])`
`* deliver the postings a completely different way (e.g. [PluginFeed feed plugin])`
`* add authentication support (e.g.[PluginAdmin admin plugin])`
`* add template functions (e.g. [PLuginYoutube youtube plugin])`
`* add new features  (e.g. [PluginStaticPage static-page plugin])`

How Plugins work
================

Plugins will be loaded on every request to the blog.

Plosxom reads all \*PHP\* files in the plugins/ directory and expects them to contain a subclass of the 'Plugin' class with the same name as the PHP file without the .php suffix.

Say there is a plugin \*test\*, it muss at least have this code:

And it must be named \*test.php\* and located in \*plugins/\*.

Plosxom creates a new object for this plugin subclass. After that the following parameters are available to the plugin:

`` * `$this->handler`: the current config (associative array) ``
`` * `$this->config`: a list of installed plugin handlers (associative array) ``
`` * `$this->input`: associative array of input variables (GET and POST) ``
`` * `$this->template`: the current start template file (e.g. "index.tpl") ``
`` * `$this->filter_plugin`: the name of the plugin which matches the current url using the *hook_url_filter* hook ``
`` * `$this->smarty`: the smarty template object ``
`` * `$this->posts`: an associative array of postings ``
`` * `$this->registry`: the *Registry* object (see below) ``

Plosxom then calls the \*register()\* method of the plugin subclass (see below). The \*register()\* method can do almost anything you like. If you have anything to do before any output has been sent to the client, do it here.

Next, Plosxom does its usual processing. There are a lot of so called

-   hooks\*, which are kind of break-points where it calls all plugins

which are registered for this particular hook. For example if Plosxom reads the posting files from disk, it calls a hook called \*hook\_content\*. In this case it calls a function with the same name as the hook for each plugin and passes it the content of the posting, eg:

So, in this example a plugin which has registered itself for the 'hook\_content' hook can manipulate the content of a posting and return it to Plosxom afterwards.

Since displaying of things to the client alsways happens through the use of Smarty templates, a plugin may require certain template code to work. A plugin developer has to document the template requirements for the user, who has to add the template code to his template.

Plugins can also add Smarty functions since a plugin has full access to the smarty template object. Refer to the [smarty online documentation for details](http://www.smarty.net/manual/en/).

An exception are admin sub-plugins. The [PluginAdmin admin webinterface] is in fact an ordinary plugin but it has its own plugin support. Admin sub-plugins have to be registered as all other plugins as well. But such sub-plugins are not required to register special hooks, instead they have to implement one or more \`admin\_\*\` methods which will be called automatically if the CGI variable \*mode\* points to them, see below for more details on this.

Plugin packaging
================

A plugin package must be provided as a ZIP file for download. The zipfile should be named as the plugin itself along with its current version, such as

-   feed-1.02.zip\*. It should contain at least a file with the php extension

and the same name as the plugin, e.g. \*feed.php\*. Optional it may contain a file with \*nfo\* extension, e.g. \*feed.nfo\* which will be used by the admin-plugin to display detailed informations about the plugin. This file is a simple config file and should look like this (example for page plugin):

Also optional it may contain a file with \*txt\* extension with the same name as the plugin, eg \*feed.txt\*, which may contain installation instructions. Those instructions will be displayed if the user installs the plugin via the admin backend.

The Plugin class
================

Your plugin inherits from the \*Plugin\* class and can therefore call the following methods:

`` * `get_handler($hook)`: returns the first registered plugin handler for this particular hook. ``
`` * `get_handlers($hook)`: the very same as above but returns a list of all handlers for the given hook. ``
`` * `add_handler($hook, $pluginname)`: a plugin has to call this method to register itself for a particular handler. It has to provide a method of the same name as the hook then. Usually called within the `register()` method. eg:  ``
`` * `add_template($type, $template)`: this can be used by admin sub-plugins to register template-hooks (see below). ``
`` * `replace_template($template)`: use this to replace the template file (default: `index.tpl`) with your own. ``

Registering a plugin
====================

So, here is a more complete example, let's take a look to the [PluginFeed feed plugin]:

As you can see, we have to write a \*register()\* function. From there we are registering ourself for the \*hook\_url\_filter\* hook. We also provide a method with the same name which does the actual work, if Plosxom starts parsing URL parameters. In the case of the feed plugin (code omitted) we look if the URL contains something like \`/feed/rss2\`. If this is the case, we return \*true\* which signals Plosxom that a url filtering plugin matched. We also replace the template with our own (eg. 'templates/shared/rss2.tpl') to generate XML output instead of the usual HTML.

The Registry class
==================

The \*Registry\* class, accessible from your plugin as \`\$this-\>registry\` is, as the name suggests, the central plugin registry. It contains all registered plugins for all hooks and can be used to request the name of a plugin and finally to execute the hook method of that plugin from your own plugin.

The primary use for this is to access the \*standard\* plugin which is part of the plosxom core and is responsible for filesystem operations.

To re-read all posting files, you could do the following:

In this case \`\$handler\` could contain the string 'standard', but it could be replaced by a mysql storage plugin or anything else.

Beside, you can also call plugin methods directly using the namespace notation, but beware: you will not call the method as object method but as function. The method in case must support it. Example:

In this case we request a single posting file.

However, you won't need it in most cases.

The config array
================

The plosxom configuration is available from plugin code as \`\$this-\>config\`, which is an associative array containing all variables of \*etc/plosxom.conf\*.

For example to get the value of the variable \_data\_path\_ in the config:

There are some special internal config variables which are not available in the plosxom.conf file but are added at runtime by Plosxom:

`* 'contenttype': this will be used as content type header for client output. You may overwrite it, eg: `
`* 'posting': this variable will be 'true' if Plosxom displays a single posting and not the list of postings.`
`* 'config_path': the directory where config files are located`
`* 'lib_path': the directory where libraries are located`
`* 'version': the current Plosxom version number`

Plugin configuration
====================

A plugin may also support its own configuration file. Such a file must have the \*conf\* file extension and contain option/value pairs separated by equalsign. Empty lines or lines beginning with \*\#\* will be ignored.

Example config file for the [PluginLog logging plugin]:

To get the content of such a config file, call the global method

-   parse\_config()\* with the filename of your config file as parameter.

If the filename contains no path parts it will be searched in the plosxom \*etc/\* directory, otherwise in the given path.

Example:

Usually you would do this in the \*register()\* method during the initialization of the plugin.

The Smarty object
=================

Plosxom uses the [smarty engine](http://www.smarty.net) as template system. A smarty template is just a plain text file with the \*tpl\* file extension which contains HTML or CSS code along with smarty code.

To pass some data over to a template, you have to assign the variable to smarty, eg:

Such a variable can be any kind of PHP variable as a string or an array. You can access this variable from smarty this way:

It is possible to include other sub-templates, there are loop conditions such as 'foreach', conditional statements as 'if/elseif/else' and a lot of more things.

Please refer to the [smarty online documentation](http://www.smarty.net/manual/en/) for more details how to write smarty templates.

If you want to use your own start template and not the default 'index.tpl' (e.g. the feed or the admin plugins are doing this), then replace the default start template this way:

Registering a hook
==================

To register a hook, just call the \*add\_handler()\* method along with the name of the hook and the name of your plugin, eg:

You must provide a class method with the same name as the hook which will be called by Plosxom:

That's it - as simple as possible.

Available hooks
===============

hook\_url\_filter
-----------------

The very first hook being called by Plosxom is the url filter hook. It is special because only one handler at once can be in use in the same time. Plosxom considers a url as unique and that only one plugin can catch a certain url.

Passed parameters:

`* a string containing the path part of the url. In fact this is the content of the enviroment variable 'PATH_INFO'.`

Expected return value:

`* must return 'true' if the supplied url matches, 'false' otherwise`

hook\_send\_header
------------------

This hook can be used to send, modify or remove HTTP headers.

Passed parameters:

`* none`

Expected return value:

`* none`

hook\_storage\_fetch
--------------------

This is a special hook which is responsible for fetching postings from disk (or any other media), it maybe overwritten, but only be implemented once.

By default the \*standard\* plugin, distributed with Plosxom, implements it.

Passed parameters:

`* category`
`* posting-id`

Expected return value:

`* associated array containing the posting (along with title, text, id, category and mtime)`

hook\_storage\_fetchall
-----------------------

The very same as above but returns all postings as unsorted list of associative arrays.

Passed parameters:

`* none`

Expected return value:

`* unsorted list of associative array of postings`

hook\_content
-------------

The \*hook\_content\* hook can be used to modify content at runtime.

Passed parameters:

`* the text of a posting`

Expected return value:

`* the (possibly) modified text content of a posting`

Localization support
====================

Plosxom templates support localization so that a template can be displayed in different languages. Which language to use will be configured by the user in the \*plosxom.conf\* variable \*lang\*, by default this is \*en\*.

Plosxom passes all .conf files starting with \*lang-\* located in the

-   etc/\* directory to smarty. So if you want to write a language file, just

create a lang-\_something\_ file and place it there.

Every language must have it's own ini-style block in the config file, here is a short example:

You may also provide an extra language file for an existing language file as long as it contains the required ini-style language declaration. This way you can extend language support for a certain template or plugin without altering the original language files.

The \*en\* (english) language part will \*always\* loaded. Make sure that your language file contains at least all required variables in the english language block. If a variable is undefined in a particular language or ir the user configures a language which is completely undefined, everything will be displayed in english, which is much better than to display nothing.

Language variables can be used in smarty templates in two ways:

This is the standard way. However, sometimes you may need to use such a language variable in a variable assignment or inside a foreach block or something else. In such a case you can refer to the language variable this way:


