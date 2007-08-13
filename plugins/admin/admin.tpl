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
</head>
<body>

{config_load file='lang.conf' section="$lang"}
<h4>{$config.blog_name} - Blog Administration</h4>

<a href="{$config.whoami}/admin">Admin Index</a> -
<a href="{$config.whoami}/admin/create">New Posting</a> -
<a href="{$config.whoami}/admin/rpcping">RPC Ping</a> -
<a href="{$config.whoami}">View Blog</a>

<br/>
<br/>

{if $admin_mode == "edit" or $admin_mode == "create"}
   {if $admin_mode == "create"}
     {assign var="title" value="Create new posting"}
   {else}
     {assign var="title" value="Edit `$post.category`/`$post.id`"}
   {/if}
   
   <h4>{$title}</h4>
    <form method="post" name="edit" action="{$config.whoami}/admin">
      <input type="hidden" name="mode" value="save">
      <input type="hidden" name="workpage" value="{$post.file}">
      <input type="hidden" name="category" value="{$post.category}">
      Category: <input type="text" name="newcategory" value="{$post.category}" id="cat">
      Title: <input type="text" name="title" size="40" value="{$post.title}"><br/>
      Available categories:
       {foreach item=cat from=$categories}
	 {if $post.category == $cat}
	   {assign var="showcat" value="<font style='background: limegreen;'>`$cat`</font>"}
	 {else}
	   {assign var="showcat" value="`$cat`} 
	 {/if}</a>
         <!-- <a href="JavaScript:setCat('{$cat}')">{$showcat}</a> -->
	 <a href="#" onclick="setCat('{$cat}')">{$showcat}</a>
       {/foreach}
      <br/>
      <textarea name="content" cols="80" rows="30">{$post.raw}</textarea>
      <br/>
      <input type="submit" name="submit" value="Save">

{else}

  {if $admin_msg}
    <div style="background: limegreen; display: box;">{$admin_msg}</div>
  {/if}

  {* multiple postings, list them *}
  {foreach item=post from=$posts}
     {$post.mtime|date_format:"%d.%m.%Y %H:%M"} - 
     <a href="{$config.whoami}/admin/edit/{$post.category}/{$post.id}">edit</a> -
     <a href="{$config.whoami}/admin/delete/{$post.category}/{$post.id}">delete</a>
     - <a href="{$config.whoami}/{$post.id}">{$post.category}/{$post.title}</a> ({$post.text|count_characters} bytes)<br/>
  {/foreach}

<br/>

{if $past}
  {if $posts}
    {* if there are no post, we are at the last page and do not display more 'past links *}
    <div class="f-left">
    <a href="{$config.whoami}/admin/past/{$past}">{#older#}</a>
    </div>
  {/if}
  {if $newer}
    {if $newer == "null"}
       {* if we are at the first page, there are no more pages, display no newer link in this case *}
       <div class="f-right">
         <a href="{$config.whoami}/admin">{#newer#}</a>
       </div>
    {else}
      <div class="f-right">
        <a href="{$config.whoami}/admin/past/{$newer}">{#newer#}</a>
      </div>
    {/if}
  {/if}
{else}
  <div class="f-left">
   <a href="{$config.whoami}/admin/past/{$config.postings}">{#older#}</a>
  </div>
{/if}

{/if}

</body>
</html>
