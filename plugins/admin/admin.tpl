<html>
<head>
<title>{$config.blog_name} - Blog Administration</title>
<script type="text/javascript" src="{$config.baseurl}/templates/{$config.template}/admin_dhtml.js"></script>
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
<link rel="stylesheet" type="text/css" href="{$config.baseurl}/templates/{$config.template}/admin.css">

<!--
     redirect the user back to the blog if unauthenticated/unauthorized.
     you may comment this out if don't like this.
-->
{if $unauth}
<meta http-equiv="refresh" content="10; URL={$config.whoami}">
{/if}

</head>

<body>

{config_load file='lang.conf' section="$lang"}
<!--
<h1>{$config.blog_name} - Blog Administration</h4>
-->

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

<div class="menu">
  <a {if $menu == "index"}    id="highlite" {else} href="{$config.whoami}?admin=yes&" {/if}>Postings</a>

  <a {if $menu == "user"}     id="highlite" {else} href="{$config.whoami}?admin=yes&mode=admin_users" {/if}>Users</a>

  <a {if $menu == "plugin"}   id="highlite" {else} href="{$config.whoami}?admin=yes&mode=admin_plugins" {/if}>Plugins</a>

  <a {if $menu == "template"} id="highlite" {else} href="{$config.whoami}?admin=yes&mode=admin_templates" {/if}>Templates</a>

  <a {if $menu == "config"}   id="highlite" {else} href="{$config.whoami}?admin=yes&mode=admin_config" {/if}>Config</a>

  <a {if $menu == "rpc"}      id="highlite" {else} href="{$config.whoami}?admin=yes&mode=rpcping" {/if}>RPC Ping</a>
 
  <a href="{$config.whoami}">View Blog</a>
  
  <a {if $menu == "help"}     id="highlite" {else} href="{$config.whoami}?admin=yes&mode=help" {/if}>Help</a>
</div>

<br/>

{if $admin_msg}
    <div class="msg">{$admin_msg}</div>
{/if}

{if $admin_error}
    <div class="error">Error: {$admin_error}</div>
{/if}


{if $admin_mode == "admin_page_edit" or $admin_mode == "admin_page_create"}
   {if $admin_mode == "admin_page_create"}
     {assign var="title" value="Create new posting"}
   {else}
     {assign var="title" value="Edit `$post.category`/`$post.id`"}
   {/if}
   
   <h4>{$title}</h4>
    <form method="post" name="edit" action="{$config.whoami}/admin">
      <input type="hidden" name="mode" value="admin_page_save">
      <input type="hidden" name="admin" value="yes">
      <input type="hidden" name="id" value="{$post.file}">
      <input type="hidden" name="category" value="{$post.category}">
      <table cellspacing="0" colpadding="0" width="100%" border="0">
      <tr>
        <td align="left">
          Category: <input type="text" name="newcategory" value="{$post.category}" id="cat">
	</td>
	<td align="right">
          Title: <input type="text" name="title" size="40" value="{$post.title}">
	</td>
      </tr>
      </table>
      <br/>
      <font title="assign category by clicking on it or just enter a new one">Available categories:</font>
       {foreach item=cat from=$categories}
	 {if $post.category == $cat}
	   {assign var="showcat" value="<font style='background: #c4c4c4;' title='current category'>`$cat`</font>"}
	 {else}
	   {assign var="showcat" value="`$cat`} 
	 {/if}</a>
         <!-- <a href="JavaScript:setCat('{$cat}')">{$showcat}</a> -->
	 <a href="#" onclick="setCat('{$cat}')">{$showcat}</a>
       {/foreach}
      <br/>
      <br/>
      <textarea name="content" rows="30">{$post.raw}</textarea>
      <br/>
      <input type="submit" name="submit" value="Save">
    </form>

{elseif $admin_mode == "admin_index"}

  <a class="submenu" href="{$config.whoami}?admin=yes&mode=admin_page_create">New Posting</a><br/>

  {* multiple postings, list them *}
  <table border="0" width="100%">
    <tr>
     <th align="left">Title</th><th align="left">Category</th><th align="left">Modification Time</th><th align="left">Size</th><th>Actions</th>
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
      <td>{$post.text|count_characters} bytes)</td>
      <td>
           <a href="{$config.whoami}?admin=yes&mode=admin_page_edit&category={$post.category}&id={$post.id}">edit</a> |
	   <a href="{$config.whoami}?admin=yes&mode=admin_page_delete&category={$post.category}&id={$post.id}">delete</a>
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



{elseif $admin_mode == "admin_users"}

  <a href="{$config.whoami}?admin=yes&mode=admin_users_create" class="submenu">New User</a><br/><br/>

    {* multiple postings, list them *}
      <table border="0" width="100%">
        <tr>
          <th align="left">Username</th><th align="left">MD5 Password</th><th>Actions</th>
        </tr>
        <tr>
        <td colspan="3">
          <p style="border-bottom: 1px solid #c4c4c4;"></p>
       </tr>
    {foreach item=md5 key=username from=$admin_users}
       <tr>
          <td>{$username}</td>
	  <td>{$md5}</td>
	  <td>
            <a href="{$config.whoami}?admin=yes&mode=admin_users_edit&username={$username}">edit</a>
	    |
            <a href="{$config.whoami}?admin=yes&mode=admin_users_delete&username={$username}">delete</a>
	  </td>
       </tr>
    {/foreach}


{elseif $admin_mode == "admin_users_create" || $admin_admin_mode == "users_edit"}

  {if $admin_mode == "admin_users_create"}
     {assign var="title" value="Create new user"}
  {else}
     {assign var="title" value="Edit `$admin_user`"}
     {assign var="readonly" value="readonly"}
  {/if}

  <h4>{$title}</h4>
    <form method="post" name="edit" action="{$config.whoami}/admin">
      <input type="hidden" name="mode" value="users_save">
      <input type="hidden" name="admin" value="yes">
      <input type="hidden" name="workuser" value="{$admin_user}">
    <table border="0">
     <tr>
      <td>Username:</td><td><input type="text" name="username" size="40" value="{$admin_user}" {$readonly}></td>
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



{elseif $admin_mode == "admin_plugins"}

<a href="{$config.whoami}?admin=yes&mode=admin_plugins_install" class="submenu">Install new plugin</a><br/><br/>

 <table border="0" width="100%">
  <tr>
   <th align="left">Name</th>
   <th align="left">Version</th>
   <th align="left">State</th>
   <th align="left">Author</th>
   <th align="left">Description</th>
  </tr>
{foreach item=plugin from=$plugins}
  {if $plugin.state == "active"}
     {assign var="color" value="green"}
     {assign var="newstate" value="inactive"}
     {assign var="newstateaction" value="disable"}
  {else}
     {assign var="color" value="red"}
     {assign var="newstate" value="active"}
     {assign var="newstateaction" value="enable&nbsp;"}
  {/if}
  <tr>
    <td>{$plugin.name}</td>
    <td><a href="{$plugin.url}">{$plugin.version}</a></td>
    <td><span style="color: {$color};">{$plugin.state}</span></td>
    <td><a href="mailto:{$plugin.author_email}">{$plugin.author}</a></td>
    <td><span title="{$plugin.description}">{$plugin.description|truncate:40:" ...":false}</span></td>
    <td>
      <code>
      <a href="{$config.whoami}?admin=yes&mode=admin_plugins_changestate&newstate={$newstate}">{$newstateaction}</a>
      |
      <a href="{$config.whoami}?admin=yes&mode=admin_plugins_delete&plugin={$plugin.name}">delete</a>
      {if $plugin.config}
        |
        <a href="{$config.whoami}?admin=yes&mode=admin_plugins_editconfig&plugin={$plugin.name}">edit plugin config</a>
      {/if}
      </code>
    </td>
  </tr>
    {/foreach}
 </table>

{/if} <!-- endif admin_mode -->

{/if} <!-- endif $unauth -->


</body>
</html>
