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
<link rel="stylesheet" type="text/css" href="{$config.baseurl}/templates/{$config.template}/admin.css">
</head>
<body>

{config_load file='lang.conf' section="$lang"}
<h1>{$config.blog_name} - Blog Administration</h4>

{if $unauth}

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
<br/>


{if $admin_msg}
    <div style="background: limegreen; display: box;">{$admin_msg}</div>
{/if}

{if $admin_error}
    <div style="background: orange; display: box;">Error: {$admin_error}</div>
{/if}


{if $admin_mode == "admin_edit_page" or $admin_mode == "admin_create_page"}
   {if $admin_mode == "admin_create_page"}
     {assign var="title" value="Create new posting"}
   {else}
     {assign var="title" value="Edit `$post.category`/`$post.id`"}
   {/if}
   
   <h4>{$title}</h4>
    <form method="post" name="edit" action="{$config.whoami}/admin">
      <input type="hidden" name="mode" value="admin_save_page">
      <input type="hidden" name="admin" value="yes">
      <input type="hidden" name="workpage" value="{$post.file}">
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
      <td><a href="{$config.whoami}/{$post.id}" title="View Posting">{$post.title}</a></td>
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

{/if} <!-- endif admin_mode -->

{/if} <!-- endif $unauth -->


</body>
</html>
