
{if $admin_mode == "admin_page"}

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
      <td><a href="{$config.whoami}?admin=yes&mode=admin_page_view&id={$post.id}" title="View '{$post.title}'">{$post.title|truncate:80:" ...":false}</a></td>
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


{elseif $admin_mode == "admin_page_view"}

<h4>View page "{$page.title}"</h4>

<a href="{$config.whoami}?admin=yes&mode=admin_page_edit&id={$post.id}">Edit this posting</a>

<div class="view">{$page.raw}</div>

<input type="button" value="Back" onclick="javascript:history.back()">

{/if}