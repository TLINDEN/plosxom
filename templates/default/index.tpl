{*
 * This is the default template delivered with plosxom.
 *}
{config_load file='lang.conf' section="$lang"}
{include file='default/header.tpl'}


{* add filter criteria title, if filter in use *}
{if $archive}
   <h3 class="filtertitle">{#archtitle#}: <a href="{$config.whoami}/archive/{$archive|date_format:'%Y%m%d'}"">{$archive|date_format}</a></h3>
{elseif $category}
   <h3 class="filtertitle">{#cattitle#}: <a href="{$config.whoami}/category/{$category}">{$category}</a></h3>
{/if}


{* content area *}
{if $post}

 {* a single blog posting *} 
 {include file="default/post.tpl" post="`$post`" }

{elseif $archivelist}

  {* output a list of archive links *}
  <ul>
    {foreach from=$archivedates item=date}
      <li><a href="{$config.whoami}/archive/{$date|date_format:'%Y%m%d'}"">{$date|date_format}</a></li>
    {/foreach}
  </ul>

{elseif $posts}

  {* multiple postings, list them *}
  {foreach item=post from=$posts}
     {if $post.blogdate}
       <div class="blogdate">
        <a href="{$config.whoami}/archive/{$post.blogdate|date_format:'%Y%m%d'}">{$post.blogdate|date_format:'%d.%m.%Y'}</a>
       </div>
     {/if}
     {include file="default/post.tpl" post="`$post`" }
  {/foreach}

{elseif $page}

  {include file="default/page.tpl" post="`$page`" }

{else}

   {* oops, no data? *}
   <h2 class="blogtitle">{#emptytitle#}</h2>
   <div class="blogposting">
   {#emptytext#}
   </div>

{/if}


{include file="default/footer.tpl"}

