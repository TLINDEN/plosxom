<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN"
  "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="de"
<head>
<title>{$config.blog_name} - Blog Administration</title>
<script type="text/javascript" src="{$config.baseurl}/templates/shared/admin_dhtml.js"></script>
<script type="text/javascript">
{literal}
function setCat(showcat) {
  document.edit.newcategory.value = showcat;
}
{/literal}
</script>
<META HTTP-EQUIV="Pragma" CONTENT="no-cache">
<meta http-equiv="expires" content="0">
<meta http-equiv="cache-control" content="no-cache">
<link rel="stylesheet" type="text/css" href="{$config.baseurl}/templates/shared/admin.css">

<!--
     redirect the user back to the blog if unauthenticated/unauthorized.
     you may comment this out if don't like this.
-->
{if $unauth}
<meta http-equiv="refresh" content="10; URL={$config.whoami}">
{/if}

{assign var="base"   value=$config.baseurl}
{assign var="edit"   value="<img title=edit   src=$base/templates/shared/edit.png   border=0>"}
{assign var="delete" value="<img title=delete src=$base/templates/shared/remove.png border=0>"}

{if $admin_mode == "admin_post_edit" or $admin_mode == "admin_page_edit"}
<script language="javascript" type="text/javascript" src="{$config.baseurl}/templates/shared/tiny_mce/tiny_mce.js"></script>
<script language="javascript" type="text/javascript">
tinyMCE.init({ldelim}
    	mode : "textareas",
        theme : "advanced",
	plugins : "table,save,advhr,advimage,advlink,emotions,iespell,insertdatetime,preview,zoom,flash,searchreplace,print,contextmenu",
	theme_advanced_buttons1_add_before : "save,separator",
	theme_advanced_buttons1_add : "fontselect,fontsizeselect",
	theme_advanced_buttons2_add : "separator,insertdate,inserttime,preview,zoom,separator,forecolor,backcolor",
	theme_advanced_buttons2_add_before: "cut,copy,paste,separator,search,replace,separator",
	theme_advanced_buttons3_add_before : "tablecontrols,separator",
	theme_advanced_buttons3_add : "emotions,iespell,flash,advhr,separator,print",
	theme_advanced_toolbar_location : "top",
	theme_advanced_toolbar_align : "left",
	theme_advanced_statusbar_location : "bottom",
	plugin_insertdate_dateFormat : "%Y-%m-%d",
	plugin_insertdate_timeFormat : "%H:%M:%S",
	extended_valid_elements : "a[name|href|target|title|onclick],img[class|src|border=0|alt|title|hspace|vspace|width|height|align|onmouseover|onmouseout|name],hr[class|width|size|noshade],font[face|size|color|style],span[class|align|style]",
	external_link_list_url : "{$config.baseurl}/templates/shared/tiny_mce/link_list.js",
	external_image_list_url : "{$config.baseurl}/templates/shared/tiny_mce/image_list.js",
	flash_external_list_url : "{$config.baseurl}/templates/shared/tiny_mce/flash_list.js",
{rdelim});

</script>
{/if}

</head>

<body>

{config_load file='lang.conf' section="$lang"}

<img src="/demo/images/acc.png" style="float:left;"/>
<h1 style="float:right;">{$config.blog_name} - Blog Administration</h4>
<br style="clear:both;">

{if $unauth}

  <!--
     If you want to hide details about authentication errors,
     e.g. wether a user doesn't exist or a password doesn't
     match, comment $unauth out and just print something like
     this:
     <b>Access Denied!</b>
    -->
  {$unauth}

{else}

<div class="menu" style="float:left; width:80%;">
  <a {if $menu == "post"}     id="highlite" {/if} href="{$config.whoami}?admin=yes&"                     >Postings</a>
  <a {if $menu == "media"}    id="highlite" {/if} href="{$config.whoami}?admin=yes&mode=admin_media"     >Media</a>
  <a {if $menu == "user"}     id="highlite" {/if} href="{$config.whoami}?admin=yes&mode=admin_user"      >Users</a>
  <a {if $menu == "plugin"}   id="highlite" {/if} href="{$config.whoami}?admin=yes&mode=admin_plugin"    >Plugins</a>
  <a {if $menu == "template"} id="highlite" {/if} href="{$config.whoami}?admin=yes&mode=admin_templates" >Templates</a>
  <a {if $menu == "config"}   id="highlite" {/if} href="{$config.whoami}?admin=yes&mode=admin_config"    >Config</a>
  <a {if $menu == "rpc"}      id="highlite" {/if} href="{$config.whoami}?admin=yes&mode=rpcping"         >RPC Ping</a>
{if $plugin_admin_page}
  <a {if $menu == "page"}     id="highlite" {/if} href="{$config.whoami}?admin=yes&mode=admin_page"      >Pages</a>
{/if}
</div>
<div class="menu" style="text-align:right; white-space: nowrap;">
  <a {if $menu == "help"}     id="highlite" {/if} href="{$config.whoami}?admin=yes&mode=help"            >Help</a>
  <a href="{$config.whoami}">View Blog</a>
</div>

<div class="submenu" style="clear:both;">
{if $admin_mode == "admin_post_edit" or $admin_mode == "admin_post"}
  <a href="{$config.whoami}?admin=yes&mode=admin_post_edit">New Posting</a>
{elseif $admin_mode == "admin_user" or $admin_mode == "admin_user_edit" or $admin_mode == "admin_user_create"}
  <a href="{$config.whoami}?admin=yes&mode=admin_user_create">New User</a>
{elseif $admin_mode == "admin_plugin" or $admin_mode == "admin_plugin_edit" or $admin_mode == "admin_plugin_create"}
  <a href="{$config.whoami}?admin=yes&mode=admin_plugin_install">Install new plugin</a>
{elseif $admin_mode == "admin_templates" or $admin_mode == "admin_user_templates" or $admin_mode == "admin_user_templates"}
  <a href="{$config.whoami}?admin=yes&mode=admin_templates_install">Install new template</a>
{elseif $admin_mode == "admin_media" or $admin_mode == "admin_media_upload" or $admin_mode == "admin_media_delete"}
  <a href="{$config.whoami}?admin=yes&mode=admin_media_upload">Upload file</a>
{/if}
{if $plugin_admin_page}
  {if $admin_mode == "admin_page_edit" or $admin_mode == "admin_page"}
   <a href="{$config.whoami}?admin=yes&mode=admin_page_edit">New Static Page</a>
  {/if}
{/if}
</div>


{if $admin_msg}
    <div class="msg">{$admin_msg}</div>
{/if}

{if $admin_error}
    <div class="error">Error: {$admin_error}</div>
{/if}

{if $admin_info}
    <div class="info">Info: {$admin_info}</div>
{/if}



{if $admin_mode == "admin_post_edit"}
   {if $post.id}
     {assign var="title" value="Edit <a href=$base/`$post.category`/`$post.id`>`$post.title`</a>"}
   {else}
     {assign var="title" value="Create new posting"}
   {/if}
   
   <h4>{$title}</h4>
    <form method="post" name="edit" action="{$config.whoami}/admin">
      <input type="hidden" name="mode" value="admin_post_save">
      <input type="hidden" name="admin" value="yes">
      <input type="hidden" name="id" value="{$post.file}">
      <input type="hidden" name="category" value="{$post.category}">
      <table cellspacing="0" colpadding="0" width="100%" border="0">
       <tr>
        <td align="left">
          Category:
        </td>
        <td align="left">
          Title:
        </td>
      </tr>

      <tr>
        <td align="left" style="padding-right: 20px;">
          <input type="text" name="newcategory" value="{$post.category}" id="cat" style="width: 180px;">
	</td>
	<td align="right">
          <input type="text" name="title" value="{$post.title}" style="width: 592px;">
	</td>
      </tr>
      </table>
      <br/>
      <font title="assign category by clicking on it or just enter a new one">Available categories:</font>
       {foreach item=cat from=$categories}
	 {if $post.category == $cat}
	   {assign var="showcat" value="<font style='background: #c4c4c4;' title='current category'>`$cat`</font>"}
	 {else}
	   {assign var="showcat" value="`$cat`"} 
	 {/if}</a>
         <!-- <a href="JavaScript:setCat('{$cat}')">{$showcat}</a> -->
	 <a href="#" onclick="setCat('{$cat}')">{$showcat}</a>
       {/foreach}
      <br/>
      <br/>
      <textarea name="content" rows="30">{$post.raw}</textarea>
      <br/>
      <input type="submit" name="submit" value="Save">
      <input type="button" value="Cancel" onclick="javascript:history.back()">
    </form>

{elseif $admin_mode == "admin_post"}

  {* multiple postings, list them *}
  <table border="0" width="100%">
    <tr>
     <th align="left">Title</th>
     <th align="left">Category</th>
     <th align="left">Modification Time</th>
     <th align="left">Size</th>
     <th align="left">Actions</th>
    </tr>
    <tr>
     <td colspan="5">
       <p style="border-bottom: 1px solid #c4c4c4;"></p>
    </tr>
  {foreach item=post from=$posts}
    <tr>
      <td><a href="{$config.whoami}/{$post.category}/{$post.id}" title="View '{$post.title}'">{$post.title|truncate:40:" ...":false}</a></td>
      <td>{$post.category}</td>
      <td>{$post.mtime|date_format:"%d.%m.%Y %H:%M"}</td>
      <td>{$post.text|count_characters} bytes</td>
      <td>
           <a href="{$config.whoami}?admin=yes&mode=admin_post_edit&category={$post.category}&id={$post.id}">{$edit}</a>
	   <a href="{$config.whoami}?admin=yes&mode=admin_post_delete&category={$post.category}&id={$post.id}">{$delete}</a>
      </td>
    </tr>
  {/foreach}
  </table>
  
<br/>


{if $past}
  {if $posts}
    {* if there are no post, we are at the last page and do not display more 'past links *}
    <div class="f-left">
    <a href="{$config.whoami}?admin=yes&past={$past}">{#older#}</a>
    </div>
  {/if}
  {if $newer}
    {if $newer == "null"}
       {* if we are at the first page, there are no more pages, display no newer link in this case *}
       <div class="f-right">
         <a href="{$config.whoami}?admin=yes&">{#newer#}</a>
       </div>
    {else}
      <div class="f-right">
        <a href="{$config.whoami}?admin=yes&past={$newer}">{#newer#}</a>
      </div>
    {/if}
  {/if}
{else}
  <div class="f-left">
   <a href="{$config.whoami}?admin=yes&past={$config.postings}">{#older#}</a>
  </div>
{/if}



{elseif $admin_mode == "admin_user"}

    {* multiple postings, list them *}
      <table border="0" width="100%">
        <tr>
          <th align="left">Username</th>
	  <th align="left">MD5 Password</th>
	  <th align="left">Actions</th>
        </tr>
        <tr>
        <td colspan="3">
          <p style="border-bottom: 1px solid #c4c4c4;"></p>
       </tr>
    {foreach item=md5 key=username from=$admin_user}
       <tr>
          <td>{$username}</td>
	  <td>{$md5}</td>
	  <td>
            <a href="{$config.whoami}?admin=yes&mode=admin_user_edit&username={$username}">{$edit}</a>
            <a href="{$config.whoami}?admin=yes&mode=admin_user_delete&username={$username}">{$delete}</a>
	  </td>
       </tr>
    {/foreach}


{elseif $admin_mode == "admin_user_create" || $admin_mode == "admin_user_edit"}

  {if $admin_mode == "admin_user_create"}
     {assign var="title" value="Create new user"}
  {else}
     {assign var="title" value="Edit `$username`"}
     {assign var="readonly" value="readonly"}
  {/if}

  <h4>{$title}</h4>
    <form method="post" name="edit" action="{$config.whoami}/admin">
      <input type="hidden" name="mode" value="admin_user_save">
      <input type="hidden" name="admin" value="yes">
    <table border="0">
     <tr>
      <td>Username:</td><td><input type="text" name="username" size="40" value="{$username}" {$readonly}></td>
     </tr>
     <tr>
      <td>Password:</td><td><input type="password" name="password" size="40"></td>
     </tr>
     <tr>
      <td>Repeat:</td><td><input type="password" name="password2" size="40"></td>
     </tr>
     </table><br/>
      <input type="submit" name="submit" value="Save">
    </form>



{elseif $admin_mode == "admin_plugin"}

 {if $plugin_help}
  <div class="msg">{$plugin_help}</div>
 {/if}

 <table border="0" width="100%">
  <tr>
   <th align="left">Name</th>
   <th align="left">Version</th>
   <th align="left">Author</th>
   <th align="left">Description</th>
   <th align="left">State</th>
   <th align="left">Actions</th>
  </tr>

  <tr>
   <td colspan="6">
     <p style="border-bottom: 1px solid #c4c4c4;"></p>
   </td>
  </tr>

{foreach item=plugin from=$plugins}
  {if $plugin.state == "active"}
     {assign var="newstate" value="inactive"}
     {assign var="img"      value="<img title=deactivate src=$base/templates/shared/ok.png border=0>"}
  {else}
     {assign var="newstate" value="active"}
     {assign var="img"      value="<img title=activate   src=$base/templates/shared/no.png border=0>"}
  {/if}
  <tr>
    <td>{$plugin.name}</td>
    <td><a href="{$plugin.url}">{$plugin.version}</a></td>
    <td><a href="mailto:{$plugin.author_email}">{$plugin.author}</a></td>
    <td>
       <span title="{$plugin.description}">
         {if $plugin.help}<a href="{$config.whoami}?admin=yes&mode=admin_plugin_help&plugin={$plugin.name}">{/if}{$plugin.description|truncate:40:" ...":false}{if $plugin.help}</a>{/if}
       </span>
    </td>
    <td>
      <a href="{$config.whoami}?admin=yes&mode=admin_plugin_changestate&newstate={$newstate}&plugin={$plugin.name}">{$img}</a>
    </td>
    <td>
      <a href="{$config.whoami}?admin=yes&mode=admin_plugin_delete&plugin={$plugin.name}">{$delete}</a>
      {if $plugin.config}
        <a href="{$config.whoami}?admin=yes&mode=admin_config_edit&configfile={$plugin.name}.conf&back=admin_plugin">{$edit}</a>
      {/if}
    </td>
  </tr>
    {/foreach}
 </table>


{elseif $admin_mode == "admin_plugin_help"}

<h2>Installation instructions and help for plugin {$plugin}</h2>

{$plugin_help}

<a href="{$config.whoami}?admin=yes&mode=admin_plugin">back</a>

{elseif $admin_mode == "admin_page"}

  {* multiple postings, list them *}
  <table border="0" width="100%">
    <tr>
     <th align="left">Title</th>
     <th align="left">Modification Time</th>
     <th align="left">Size</th>
     <th align="left">Actions</th>
    </tr>
    <tr>
     <td colspan="5">
       <p style="border-bottom: 1px solid #c4c4c4;"></p>
    </tr>
  {foreach item=post from=$pages}
    <tr>
      <td><a href="{$config.whoami}/page/{$post.id}" title="View '{$post.title}'">{$post.title|truncate:80:" ...":false}</a></td>
      <td>{$post.mtime|date_format:"%d.%m.%Y %H:%M"}</td>
      <td>{$post.text|count_characters} bytes</td>
      <td>
           <a href="{$config.whoami}?admin=yes&mode=admin_page_edit&id={$post.id}">{$edit}</a>
           <a href="{$config.whoami}?admin=yes&mode=admin_page_delete&id={$post.id}">{$delete}</a>
      </td>
    </tr>
  {/foreach}
  </table>
  
<br/>




{elseif $admin_mode == "admin_page_edit"}
   {if $post.id}
     {assign var="title" value="Edit <a href=$base/page/`$page.id`>`$page.title`</a>"}
   {else}
     {assign var="title" value="Create new static page"}
   {/if}
   
   <h4>{$title}</h4>
    <form method="post" name="edit" action="{$config.whoami}/admin">
      <input type="hidden" name="mode" value="admin_page_save">
      <input type="hidden" name="admin" value="yes">
      <input type="hidden" name="id" value="{$page.file}">
      <table border="0" cellspacing="0" colpadding="0" width="100%">
        <tr>
	  <td align="left">
	    Title:
	  </td>
	  <td align="right">
	    <input type="text" name="title" value="{$page.title}" style="width: 692px;">
	  </td>
	</tr>
      </table>
      <br/>
      <textarea name="content" rows="30">{$page.raw}</textarea>
      <br/>
      <input type="submit" name="submit" value="Save">
      <input type="button" value="Cancel" onclick="javascript:history.back()">
    </form>




{elseif $admin_mode == "admin_config"}

  {* multiple configs, list them *}
  <table border="0" width="100%">
    <tr>
     <th align="left">Configfile</th>
     <th align="left">Actions</th>
    </tr>
    <tr>
     <td colspan="5">
       <p style="border-bottom: 1px solid #c4c4c4;"></p>
    </tr>

  {foreach item=configfile from=$configs}

     <tr>
      <td>{$configfile}</td>
      <td>
           <a href="{$config.whoami}?admin=yes&mode=admin_config_edit&configfile={$configfile}">{$edit}</a>
      </td>
    </tr>

  {/foreach}

 </table>


{elseif $admin_mode == "admin_config_edit"}
   <h4>Edit configfile {$configfile}</h4>
    <form method="post" name="edit" action="{$config.whoami}/admin">
      <input type="hidden" name="mode" value="admin_config_save">
      <input type="hidden" name="admin" value="yes">
      {if $back}
        <input type="hidden" name="back" value="{$back}">
      {/if}
      <input type="hidden" name="configfile" value="{$configfile}">
      <br/>
      <textarea name="configcontent" rows="30">{$configcontent}</textarea>
      <br/>
      <input type="submit" name="submit" value="Save">
      <input type="button" value="Cancel" onclick="javascript:history.back()">
    </form>


{elseif $admin_mode == "admin_plugin_install"}

<h4>Install new plugin</h4>

<p>Enter location of plugin zip file on your local harddisk or locate it using the 'browse' button</p>

<form method="post" name="uploadplugin" action="{$config.whoami}/admin" enctype="multipart/form-data">
  <input type="hidden" name="mode" value="admin_plugin_upload">
  <input type="hidden" name="admin" value="yes">
  <input type="file" name="archive" size="80">
  <br/>
  <input type="submit" name="submit" value="upload this file">
</form>



{elseif $admin_mode == "admin_media"}


 {foreach item=image from=$images}
  <div class="thumb">
   <div class="f-left">
    <div class="thumbimage">
     {if $image.normal}
      <a target="__new" href="{$config.imgurl}/{$image.normal}"><img src="{$image.thumbnail}"
         title="{$image.orig}" border="0"/></a>
     {else}
      <a href="{$config.imgurl}/{$image.orig}"><img title="{$image.orig}" src="{$image.thumbnail}" border="0"/></a>
     {/if}
   </div>
  </div>

  <div class="f-right">
    <a href="{$config.whoami}?admin=yes&mode=admin_media_delete&image={$image.orig}"><img
       src="{$config.baseurl}/templates/shared/img-delete.png" 
       title="remove image" border="0"></a><br/>

    {if $image.isimage}
       <a target="__new" href="{$config.imgurl}/{$image.orig}"><img   class="" border="0"
          title="original size view"
          src="{$config.baseurl}/templates/shared/img-view-orig.png"></a><br/>

       {if $image.normal}
         <a target="__new"  href="{$config.imgurl}/{$image.normal}"><img border="0"
            title="{$config.image_normal_width}x{$config.image_normal_width} width version"
            src="{$config.baseurl}/templates/shared/img-view-small.png"></a>
       {/if}
    {/if}
  </div>

  </div>
 {/foreach}


{elseif $admin_mode == "admin_media_upload"}

<h4>Upload media file</h4>

<p>Enter location of media file on your local harddisk or locate it using the 'browse' button</p>

<form method="post" name="uploadmedia" action="{$config.whoami}/admin" enctype="multipart/form-data">
  <input type="hidden" name="mode" value="admin_media_uploadfile">
  <input type="hidden" name="admin" value="yes">
  <input type="file" name="mediafile" size="80">
  <br/>
  <input type="submit" name="submit" value="upload this file">
</form>



{/if} <!-- endif admin_mode -->

{/if} <!-- endif $unauth -->


</body>
</html>
