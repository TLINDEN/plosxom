# Introduction #

Administration Webinterface Plugin


# Details #

This plugin will be delivered with plosxom core release. Usage is straight forward and easy to learn.


# Development Status #

**Last updated: 13.2.2008**

For now the following functionality is implemented so far:

  * admin login using http basic authentication
  * it uses its own template and is therefore fully customizable too
  * supports plugins itself (admin-plugins), an example is the [static-page admin pluign](http://plosxom.googlecode.com/svn/plugins/page/plugin_admin_page.php), which is complete
  * create, edit and remove of blog postings
  * wysiwyg editor (tinymce)
  * media file management (no upload yet)
  * config file management, the user can directly edit configs from the admin backend
  * user management
  * installation and deletion of plugins, plugins can also deactivated
  * media file upload and delete function
  * template management
  * [rpc ping](PluginAdminRPC.md)
  * [admin plugin for tag generation](PluginTagCloud.md)
  * [documentation (online help)](PlosxomReadme.md)
  * [localization (using smarty lang support)](http://code.google.com/p/plosxom/source/browse/plugins/admin/lang-admin.conf)

# Screenshots #

For the curious you might take a look how it looks:

  * [List of postings](http://www.23hq.com/PaliDhar/photo/2364042/original)
  * [Editing a posting](http://www.23hq.com/PaliDhar/photo/2805302/original)
  * [Managing plugins](http://www.23hq.com/PaliDhar/photo/2805198/original)
  * [Installing a plugin](http://www.23hq.com/PaliDhar/photo/2824016/original)
  * [Media files management](http://www.23hq.com/PaliDhar/photo/2828758/original)

_Please note that some screenshots might be outdated_


# Download #

While the admin plugin can be downloaded [separately](http://plosxom.googlecode.com/files/admin-1.00.zip) it is not recommended to do so. Instead use the latest version of plosxom which includes the latest admin backend.

However, if you intend to download and install it manually, please consider the following hints:

  * the admin plugin requires at least plosxom **1.06** or higher
  * copy the files admin.php, admin.nfo to your plugin directory
  * copy the files admin-users.cionf and lang-admin.conf to your etc directory
  * copy all the other files and directories to templates/shared/
  * add a link to the admin backend:
> > `<a href="{$config.whoami}/admin">Login</a>`