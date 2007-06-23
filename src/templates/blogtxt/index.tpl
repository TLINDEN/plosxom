{*
 * This is the blogtxt template, based on blog.txt:
 * http://www.plaintxt.org/themes/blogtxt/
 * ported to plosxom/smarty by pali dhar
 *}
{config_load file='lang.conf' section="$lang"}
{include file='blogtxt/header.tpl'}

<div id="content" class="narrowcolumn">

{* add filter criteria title, if filter in use *}
{if $archive}
   <h2 class="post-title">{#archtitle#}: <a href="{$config.whoami}/archive/{$archive|date_format:'%Y%m%d'}">{$archive|date_format}</a></h2>
{elseif $category}
   <h2 class="post-title">{#cattitle#}: <a href="{$config.whoami}/category/{$category}">{$category}</a></h2>
{/if}



{* are we displaying an archive of some kind? *}
{* prepare url snippet for later use in paging generation below *}
{if $archive}
  {assign var="url" value="/archive/$archivestamp"}
{elseif $category}
  {assign var="url" value="/category/$category"}
{else}
  {assign var="url" value=""}
{/if}





{* content area *}
{if $post}

 {* a single blog posting *} 
 {include file="blogtxt/post.tpl" post="`$post`" }

{elseif $archivelist}

  {* output a list of archive links *}
  <ul>
    {foreach from=$archivedates item=date}
      <li><a href="{$config.whoami}/archive/{$date|date_format:'%Y%m%d'}">{$date|date_format}</a></li>
    {/foreach}
  </ul>

{elseif $posts}

  {* multiple postings, list them *}
  {foreach item=post from=$posts}
     {include file="blogtxt/post.tpl" post="`$post`" }
  {/foreach}

{elseif $page}

  {include file="blogtxt/page.tpl" post="`$page`" }

{else}

   {* oops, no data? *}
   <h2 class="post-title">{#emptytitle#}</h2>
   <div class="post-entry">
   {#emptytext#}
   </div>

{/if}


<div class="navigation">
{if $past}
  {if $posts}
    {* if there are no post, we are at the last page and do not display more 'past links *}
    <div class="alignleft">
    <a href="{$config.whoami}{$url}/past/{$past}">{#older#}</a>
    </div>
  {/if}
  {if $newer}
    {if $newer == "null"}
       {* if we are at the first page, there are no more pages, display no newer link in this case *}
       <div class="alignright">
         <a href="{$config.whoami}{$url}">{#newer#}</a>
       </div>
    {else}
      <div class="alignright">
        <a href="{$config.whoami}{$url}/past/{$newer}">{#newer#}</a>
      </div>
    {/if}
  {/if}
{else}
 {if !$post}
  <div class="alignleft">
   <a href="{$config.whoami}{$url}/past/{$config.postings}">{#older#}</a>
  </div>
 {/if}
{/if}
</div>

</div><!-- END CONTENT / NARROWCOLUMN -->

</div><!-- END CONTAINER (has been started in header.tpl -->

{include file="blogtxt/sidebar.tpl"}

{include file="blogtxt/footer.tpl"}

