<html>
<body>

{config_load file='lang.conf' section="$lang"}
<h4>{$config.blog_name} - Blog Administration</h4>

<a href="{$config.whoami}/admin">Admin Index</a> -
<a href="{$config.whoami}/admin/create">New Posting</a> -
<a href="{$config.whoami}/admin/rpcping">RPC Ping</a> -
<a href="{$config.whoami}">View Blog</a>

<br/>
<br/>

{if $admin_mode == "edit"}

   <h4>Edit {$post.category}/{$post.id}</h4>
    <form method=postname=edit action="{$config.whoami}/admin">
      <input type=hidden name=mode value=save>
      <input type=hidden name=file value="{$post.file}">
      <input type=hidden name=category value="{$post.category}">
      Category: <input type=text name=newcategory size=40 value="{$post.category}"><br/>
    Available categories: <b>FIXME</b><br/>
     Title: <input type=text name=title size=40 value="{$post.title}"><br/>
     <textarea name=content cols=80 rows=30>{$post.raw}</textarea>
    <br/>
      <input type=submit name=submit value="Save">

{else}

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
