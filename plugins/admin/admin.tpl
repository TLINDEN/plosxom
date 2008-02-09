<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN"
  "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="{$lang}"
<head>

{foreach from=$langfiles item=file}
  {config_load file="$file" section="en"}
  {config_load file="$file" section="$lang"}
{/foreach}

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
<script language="javascript" type="text/javascript" src="{$config.baseurl}/templates/shared/tiny_mce/jscripts/tiny_mce/tiny_mce.js"></script>
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
	relative_urls : false,
	convert_urls : false,
	document_base_url : "{$config.baseurl}",
	language : "{$lang}"
{rdelim});

</script>
{/if}

</head>

<body>




<img src="{$config.baseurl}/templates/shared/plosxom.png" style="float:left;"/>
<div class="title">
  <h1>Blog Administration</h1>
  <h2>{$config.blog_name} - {$config.blog_title}</h2>
</div>
<br style="clear:both;">


{if $unauth}

     <b>{#accessdenied#}</b>

{else}

<div class="menu" style="float:left; width:80%;">
  <a {if $menu == "post"}     id="highlite" {/if} href="{$config.whoami}?admin=yes&"                     >{#menupost#}</a>
  <a {if $menu == "media"}    id="highlite" {/if} href="{$config.whoami}?admin=yes&mode=admin_media"     >{#menumedia#}</a>
  <a {if $menu == "user"}     id="highlite" {/if} href="{$config.whoami}?admin=yes&mode=admin_user"      >{#menuuser#}</a>
  <a {if $menu == "plugin"}   id="highlite" {/if} href="{$config.whoami}?admin=yes&mode=admin_plugin"    >{#menuplugin#}</a>
  <a {if $menu == "template"} id="highlite" {/if} href="{$config.whoami}?admin=yes&mode=admin_template"  >{#menutemplate#}</a>
  <a {if $menu == "config"}   id="highlite" {/if} href="{$config.whoami}?admin=yes&mode=admin_config"    >{#menuconfig#}</a>
  <a {if $menu == "extras"}   id="highlite" {/if} href="{$config.whoami}?admin=yes&mode=admin_extras"    >{#menuextra#}</a>
{foreach from=$menu_tpl item=tpl}
  {include file=$tpl}
{/foreach}
</div>
<div class="menu" style="text-align:right; white-space: nowrap;">
  <a {if $menu == "help"}     id="highlite" {/if} href="{$config.whoami}?admin=yes&mode=admin_help"            >{#menuhelp#}</a>
  <a href="{$config.whoami}">{#menuviewblog#}</a>
</div>

<div class="submenu" style="clear:both;">
{if $admin_mode == "admin_post_edit" or $admin_mode == "admin_post"}
  <a href="{$config.whoami}?admin=yes&mode=admin_post_edit">{#newpost#}</a>
{elseif $admin_mode == "admin_user" or $admin_mode == "admin_user_edit" or $admin_mode == "admin_user_create"}
  <a href="{$config.whoami}?admin=yes&mode=admin_user_create">{#newuser#}</a>
{elseif $admin_mode == "admin_plugin" or $admin_mode == "admin_plugin_edit" or $admin_mode == "admin_plugin_create"}
  <a href="{$config.whoami}?admin=yes&mode=admin_plugin_install">{#newplugin#}</a>
{elseif $admin_mode == "admin_template" or $admin_mode == "admin_template_edit" or $admin_mode == "admin_template_delete" or $admin_mode == "admin_template_install"}
  <a href="{$config.whoami}?admin=yes&mode=admin_template_install">{#newtemplate#}</a>
{elseif $admin_mode == "admin_media" or $admin_mode == "admin_media_upload" or $admin_mode == "admin_media_delete"}
  <a href="{$config.whoami}?admin=yes&mode=admin_media_upload">{#newmedia#}</a>
{/if}
{foreach from=$submenu_tpl item=tpl}
  {include file=$tpl}
{/foreach}
&nbsp;
</div>



{if $messageinfo}
 <div class="info">
  <p>{#boxinfo#}:<br/>
    {foreach from=$messageinfo item=part}
      {assign var="index" value="`$part.id`"}
      {xprintf id=$smarty.config.$index param=$part.param}<br/>
    {/foreach}
  </p>
 </div>
{/if}

{if $messageerror}
 <div class="error">
  <p>{#boxerror#}:<br/>
    {foreach from=$messageerror item=part}
      {assign var="index" value="`$part.id`"}
      {xprintf id=$smarty.config.$index param=$part.param}<br/>
    {/foreach}
  </p>
 </div>
{/if}

{if $admin_mode == "admin_post_edit"}
   {if $post.id}
     {assign var="title" value="`$smarty.config.edit` <a href=$base/`$post.category`/`$post.id`>`$post.title`</a>"}
   {else}
     {assign var="title" value="`$smarty.config.createnew` `$smarty.config.posting`"}
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
          {#cattitle#}:
        </td>
        <td align="left">
          {#title#}:
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
      <font title="{#hintnewcat#}">{#catavail#}:</font>
       {foreach item=cat from=$categories}
	 {if $post.category == $cat}
	   {assign var="showcat" value="<font style='background: #c4c4c4;' title='`$smarty.config.catavail`'>`$cat`</font>"}
	 {else}
	   {assign var="showcat" value="`$cat`"} 
	 {/if}</a>
         <!-- <a href="JavaScript:setCat('{$cat}')">{$showcat}</a> -->
	 <a href="#" onclick="setCat('{$cat}')">{$showcat}</a>
       {/foreach}
      <br/>
      <br/>
      <textarea name="content" rows="30">{$post.text}</textarea>
      <br/>
      <input type="submit" name="submit" value="{#buttonsave#}">
      <input type="button" value="{#buttoncancel#}" onclick="javascript:history.back()">
      {foreach from=$postsave_tpl item=tpl}
        {include file=$tpl}
      {/foreach}
    </form>




{elseif $admin_mode == "admin_post_view"}

   <h4>{#view#} "{$post.title}"</h4>

   <p>
   <a href="{$config.whoami}?admin=yes&mode=admin_post_edit&category={$post.category}&id={$post.id}">{#editthis#} {#posting#}</a>
   </p>

   <div class="view">{$post.text}</div>

      <input type="button" value="{#buttback#}" onclick="javascript:history.back()">




{elseif $admin_mode == "admin_post"}

{assign var="bg" value=""}

  {* multiple postings, list them *}
  <table border="0" width="100%">
    <tr>
     <th align="left">{#title#}</th>
     <th align="left">{#cattitle#}</th>
     <th align="left">{#mtime#}</th>
     <th align="left">{#size#}</th>
     <th align="left">{#actions#}</th>
    </tr>
    <tr>
     <td colspan="5">
       <p style="border-bottom: 1px solid #c4c4c4;"></p>
    </tr>
  {foreach item=post from=$posts}
    <tr class="{$bg}">
      <td><a href="{$config.whoami}?admin=yes&mode=admin_post_view&category={$post.category}&id={$post.id}" title="{#view#} '{$post.title}'">{$post.title|truncate:40:" ...":false}</a></td>
      <td>{$post.category}</td>
      <td>{$post.mtime|date_format:"%d.%m.%Y %H:%M"}</td>
      <td>{$post.text|count_characters} bytes</td>
      <td>
           <a href="{$config.whoami}?admin=yes&mode=admin_post_edit&category={$post.category}&id={$post.id}">{$edit}</a>
	   <a href="{$config.whoami}?admin=yes&mode=admin_post_delete&category={$post.category}&id={$post.id}">{$delete}</a>
      </td>
    </tr>

  {if $bg}
    {assign var="bg" value=""}
  {else}
    {assign var="bg" value="greyrow"}
  {/if}

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

{assign var="bg" value=""}

    {* multiple postings, list them *}
      <table border="0" width="100%">
        <tr>
          <th align="left">{#username#}</th>
	  <th align="left">{#usermd5#}</th>
	  <th align="left">{#Actions#}</th>
        </tr>
        <tr>
        <td colspan="3">
          <p style="border-bottom: 1px solid #c4c4c4;"></p>
       </tr>
    {foreach item=md5 key=username from=$admin_user}
       <tr class="{$bg}">
          <td>{$username}</td>
	  <td>{$md5}</td>
	  <td>
            <a href="{$config.whoami}?admin=yes&mode=admin_user_edit&username={$username}">{$edit}</a>
            <a href="{$config.whoami}?admin=yes&mode=admin_user_delete&username={$username}">{$delete}</a>
	  </td>
       </tr>
  {if $bg}
    {assign var="bg" value=""}
  {else}
    {assign var="bg" value="greyrow"}
  {/if}

    {/foreach}


{elseif $admin_mode == "admin_user_create" || $admin_mode == "admin_user_edit"}

  {if $admin_mode == "admin_user_create"}
     {assign var="title" value="`$smarty.config.createnew` `$smarty.config.user`"}
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
      <td>{#username#}:</td><td><input type="text" name="username" size="40" value="{$username}" {$readonly}></td>
     </tr>
     <tr>
      <td>{#userpass#}:</td><td><input type="password" name="password" size="40"></td>
     </tr>
     <tr>
      <td>{#userpass2#}:</td><td><input type="password" name="password2" size="40"></td>
     </tr>
     </table><br/>
      <input type="submit" name="submit" value="{#buttonsave#}">
    </form>



{elseif $admin_mode == "admin_plugin"}

 {if $plugin_help}
  <div class="msg">{$plugin_help}</div>
 {/if}

{assign var="bg" value=""}

 <table border="0" width="100%">
  <tr>
   <th align="left">{#name#}</th>
   <th align="left">{#version#}</th>
   <th align="left">{#author#}</th>
   <th align="left">{#descr#}</th>
   <th align="left">{#state#}</th>
   <th align="left">{#actions#}</th>
  </tr>

  <tr>
   <td colspan="6">
     <p style="border-bottom: 1px solid #c4c4c4;"></p>
   </td>
  </tr>

{foreach item=plugin from=$plugins}
  {if $plugin.state == "active"}
     {assign var="newstate" value="inactive"}
     {assign var="img"      value="<img title='`$smarty.config.hintdis`' src=$base/templates/shared/ok.png border=0>"}
  {else}
     {assign var="newstate" value="active"}
     {assign var="img"      value="<img title='`$smarty.config.hintena`'   src=$base/templates/shared/no.png border=0>"}
  {/if}
  <tr class="{$bg}">
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
  {if $bg}
    {assign var="bg" value=""}
  {else}
    {assign var="bg" value="greyrow"}
  {/if}

    {/foreach}
 </table>


{elseif $admin_mode == "admin_plugin_help"}

<h2>{#plughelptitle#} {$plugin}</h2>

{$plugin_help}

<a href="{$config.whoami}?admin=yes&mode=admin_plugin">{#buttonback#}</a>







{elseif $admin_mode == "admin_config"}

{assign var="bg" value=""}

  {* multiple configs, list them *}
  <table border="0" width="100%">
    <tr>
     <th align="left">{#configfile#}</th>
     <th align="left">{#actions#}</th>
    </tr>
    <tr>
     <td colspan="5">
       <p style="border-bottom: 1px solid #c4c4c4;"></p>
    </tr>

  {foreach item=configfile from=$configs}

     <tr class="{$bg}">
      <td>
           <a href="{$config.whoami}?admin=yes&mode=admin_config_view&configfile={$configfile}">{$configfile}</a>
      </td>
      <td>
           <a href="{$config.whoami}?admin=yes&mode=admin_config_edit&configfile={$configfile}">{$edit}</a>
      </td>
    </tr>

  {if $bg}
    {assign var="bg" value=""}
  {else}
    {assign var="bg" value="greyrow"}
  {/if}

  {/foreach}

 </table>



{elseif $admin_mode == "admin_config_view"}

   <h4>{#view#} {#configfile#} {$configfile}</h4>

   <p>
   <a href="{$config.whoami}?admin=yes&mode=admin_config_edit&configfile={$configfile}">{#editthis#} {#configfile#}</a>
   </p>

   <div class="view"><pre>{$configcontent}</pre></div>

   <a href="{$config.whoami}?admin=yes&mode=admin_config">{#buttonback#}</a>






{elseif $admin_mode == "admin_config_edit"}
   
   <h4>{#edit#} {#configfile#} {$configfile}</h4>
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
      <input type="submit" name="submit" value="{#buttonsave#}">
      <input type="button" value="{#buttoncancel#}" onclick="javascript:history.back()">
    </form>


{elseif $admin_mode == "admin_plugin_install"}

<h4>{#newplugin#}</h4>

<p>{#hintplugbrowse#}</p>

<form method="post" name="uploadplugin" action="{$config.whoami}/admin" enctype="multipart/form-data">
  <input type="hidden" name="mode" value="admin_plugin_upload">
  <input type="hidden" name="admin" value="yes">
  <input type="file" name="archive" size="80">
  <br/>
  <input type="submit" name="submit" value="{#plugupload#}">
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
       title="{#hintimgdel#}" border="0"></a><br/>

    {if $image.isimage}
       <a target="__new" href="{$config.imgurl}/{$image.orig}"><img   class="" border="0"
          title="{#hintimgorig#}"
          src="{$config.baseurl}/templates/shared/img-view-orig.png"></a><br/>

       {if $image.normal}
         <a target="__new"  href="{$config.imgurl}/{$image.normal}"><img border="0"
            title="{$config.image_normal_width}x{$config.image_normal_width} {#hintimgnorm#}"
            src="{$config.baseurl}/templates/shared/img-view-small.png"></a>
       {/if}
    {/if}
  </div>

  </div>
 {/foreach}


{elseif $admin_mode == "admin_media_upload"}

<h4>{#imgupload#}</h4>

<p>{#hintimgbrowse#}</p>

<form method="post" name="uploadmedia" action="{$config.whoami}/admin" enctype="multipart/form-data">
  <input type="hidden" name="mode" value="admin_media_uploadfile">
  <input type="hidden" name="admin" value="yes">
  <input type="file" name="mediafile" size="80">
  <br/>
  <input type="submit" name="submit" value="{#plugupload#}">
</form>



{elseif $admin_mode == "admin_extras"}

<br/>
{foreach from=$extra_tpl item=tpl}
  {include file=$tpl}
{/foreach}







{elseif $admin_mode == "admin_template"}

 {if $template_help}
  <div class="msg">{$template_help}</div>
 {/if}

 <table border="0" width="100%" cellspacing="0" cellpadding="5">
  <tr>
   <th align="left">{#preview#}</th>
   <th align="left">{#name#}</th>
   <th align="left">{#version#}</th>
   <th align="left">{#author#}</th>
   <th align="left">{#descr#}</th>
   <th align="left">{#state#}</th>
   <th align="left">{#actions#}</th>
  </tr>

  <tr>
   <td colspan="7">
     <p style="border-bottom: 1px solid #c4c4c4;"></p>
   </td>
  </tr>

{assign var="bg" value=""}

{foreach item=template from=$templates}
  {if $template.state == "active"}
     {assign var="activestyle" value="color:green;"}
     {assign var="activate" value="0"}
     {assign var="img"      value="<img title='`$smarty.config.tplinuse`' src=$base/templates/shared/ok.png border=0>"}
  {else}
     {assign var="activestyle" value=""}
     {assign var="activate" value="1"}
     {assign var="img"      value="<img title='activate template'  src=$base/templates/shared/no.png border=0>"}
  {/if}
  <tr valign="top" class="{$bg}">
    <td>
    {if $template.screenshot}
     <a target="__new" href="{$base}/templates/{$template.name}/{$template.screenshot}"/><img
        src="{$base}/templates/{$template.name}/{$template.thumbnail}" title="{#preview#}" border="0"/></a>
    {/if}
    </td>
    <td style="{$activestyle}">{$template.name}</td>
    <td>{if $template.url}<a href="{$template.url}">{/if}{$template.version}{if $template.url}</a>{/if}</td>
    <td>{if $template.author_email}<a href="mailto:{$template.author_email}">{/if}{$template.author}{if $template.author_email}</a>{/if}</td>
    <td>
        {$template.description}
    </td>
    <td>
      {if $activate}
        <a href="{$config.whoami}?admin=yes&mode=admin_template_changestate&newstate={$newstate}&template={$template.name}">{$img}</a>
      {else}
        {$img}
      {/if}
    </td>
    <td>
      <a href="{$config.whoami}?admin=yes&mode=admin_template_delete&template={$template.name}">{$delete}</a>
      <a href="{$config.whoami}?admin=yes&mode=admin_template_edit&template={$template.name}">{$edit}</a>
    </td>
  </tr>

  {if $bg}
    {assign var="bg" value=""}
  {else}
    {assign var="bg" value="greyrow"}
  {/if}

 {/foreach}
 </table>



{elseif $admin_mode == "admin_template_edit"}

<h4>{#edittitle#} <i>{$template}</i></h4>

  {* multiple template files, list them *}
  <table border="0" width="100%">
    <tr>
     <th align="left">{#templatefile#}</th>
     <th align="left">{#actions#}</th>
    </tr>
    <tr>
     <td colspan="5">
       <p style="border-bottom: 1px solid #c4c4c4;"></p>
    </tr>

  {foreach item=file from=$template_files}

     <tr>
      <td>{$file}</td>
      <td>
           <a href="{$config.whoami}?admin=yes&mode=admin_template_editfile&template_file={$file}&template={$template}">{$edit}</a>
      </td>
    </tr>

  {/foreach}

 </table>


{elseif $admin_mode == "admin_template_editfile"}
   
   <h4>{#edit#} {#templatefile#} {$template_file} - {#template#} {$template}</h4>
    <form method="post" name="edit" action="{$config.whoami}/admin">
      <input type="hidden" name="mode" value="admin_template_savefile">
      <input type="hidden" name="admin" value="yes">
      <input type="hidden" name="template" value="{$template}">
      <input type="hidden" name="template_file" value="{$template_file}">
      <br/>
      <textarea name="template_content" rows="30">{$template_content}</textarea>
      <br/>
      <input type="submit" name="submit" value="{#buttonsave#}">
      <input type="button" value="{#buttoncancel#}" onclick="javascript:history.back()">
    </form>



{elseif $admin_mode == "admin_template_install"}

<h4>{#newtemplate#}</h4>

<p>{#hinttplbrowse#}</p>

<form method="post" name="uploadtemplate" action="{$config.whoami}/admin" enctype="multipart/form-data">
  <input type="hidden" name="mode" value="admin_template_upload">
  <input type="hidden" name="admin" value="yes">
  <input type="file" name="template" size="80">
  <br/>
  <input type="submit" name="submit" value="{#plugupload#}">
</form>

{elseif $admin_mode == "admin_help"}

  {include file="`$config.template_path`/shared/README.html"}

{else}

{foreach from=$main_tpl item=tpl}
  {include file=$tpl}
{/foreach}


{/if} <!-- endif admin_mode -->


{/if} <!-- endif $unauth -->


</body>
</html>
