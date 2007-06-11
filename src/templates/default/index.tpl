{include file='default/header.tpl'}
<!--
	Default index template, delivered with plosxom source.
-->

{if $archive}
<h3 class="filtertitle">Archive: <a href="{$config.whoami}/archive/{$archive|date_format:'%Y%m%d'}"">{$archive|date_format}</a></h3>
{elseif $category}
<h3 class="filtertitle">Category: <a href="{$config.whoami}/category/{$category}">{$category}</a></h3>
{/if}

{if $singleposting}

   <h2 class="blogtitle"><a href="{$config.whoami}/{$post.category}/{$post.id}">{$post.title}</a></h2>
   <div class="blogposting">
   {$post.text}
   </div>
   <p class="blogmeta"> Geschrieben von <a title='email an den Autor {$config.author}' href="{$config.autor_link}">{$config.author}</a>
                        at {$post.mtime|date_format:"%A, %B %e, %Y"}
    [ <a title='Beitr&auml;ge in Kategorie {$post.category} Lesen' href="{$config.whoami}/category/{$post.category}">{$post.category}</a> |
      <a title=permalink href="{$config.whoami}/{$post.category}/{$post.id}">#</a> | 
      <a href="javascript:HaloScan('{$post.category|upper}{$post.id|upper}');" target="_self"><script
        type="text/javascript">postCount('{$post.category|upper}{$post.id|upper}');</script></a> | 
      <a href="javascript:HaloScanTB('{$post.category|upper}{$post.id|upper}');" target="_self"><script
        type="text/javascript">postCountTB('{$post.category|upper}{$post.id|upper}');</script></a> |
      <a href='http://creativecommons.org/licenses/by-nc-sa/2.0/de/'>cc</a>
    ]
   </p>
   <br/><br/>

{elseif $archivelist}

 <ul>
 {foreach from=$archivedates item=date}
 <li><a href="{$config.whoami}/archive/{$date|date_format:'%Y%m%d'}"">{$date|date_format}</a></li>
 {/foreach}
 </ul>

{else}

 {section name=id loop=$posts}
   {if $posts[id].blogdate}
     <p class="blogdate">{$posts[id].blogdate|date_format}</p>
   {/if}
   <h2 class="blogtitle"><a href="{$config.whoami}/{$posts[id].category}/{$posts[id].id}">{$posts[id].title}</a></h2>
   <div class="blogposting">
   {$posts[id].text}
   </div>
   <p class="blogmeta"> Geschrieben von <a title='email an den Autor {$config.author}' href="{$config.autor_link}">{$config.author}</a>
                        at {$posts[id].mtime|date_format:"%A, %B %e, %Y"}
    [ <a title='Beitr&auml;ge in Kategorie {$posts[id].category} Lesen' href="{$config.whoami}/category/{$posts[id].category}">{$posts[id].category}</a> |
      <a title=permalink href="{$config.whoami}/{$posts[id].category}/{$posts[id].id}">#</a> | 
      <a href="javascript:HaloScan('{$posts[id].category|upper}{$posts[id].id|upper}');" target="_self"><script
        type="text/javascript">postCount('{$posts[id].category|upper}{$posts[id].id|upper}');</script></a> | 
      <a href="javascript:HaloScanTB('{$posts[id].category|upper}{$posts[id].id|upper}');" target="_self"><script
        type="text/javascript">postCountTB('{$posts[id].category|upper}{$posts[id].id|upper}');</script></a> |
      <a href='http://creativecommons.org/licenses/by-nc-sa/2.0/de/'>cc</a>
    ]
   </p>
   <br/><br/>
   {/section}

{/if}


<hr noshade size="1">
<p><a href="{$config.whoami}">Home</a> - 
<a href="{$config.whoami}/archive">Archiv</a> - 
<a href="{$config.whoami}/feed/rss">RSS Feed</a>

{if $archive}
  {assign var="url" value="/archive/$archivestamp"}
{elseif $category}
  {assign var="url" value="/category/$category"}
{else}
  {assign var="url" value=""}
{/if}

{if $past}
  {if $posts}
    - <a href="{$config.whoami}{$url}/past/{$past}">&Auml;ltere</a>
  {/if}
  {if $newer}
    {if $newer == "null"}
       - <a href="{$config.whoami}{$url}">Neuere</a>
    {else}
       - <a href="{$config.whoami}{$url}/past/{$newer}">Neuere</a>
    {/if}
  {/if}
{else}
  - <a href="{$config.whoami}{$url}/past/{$config.postings}">&Auml;ltere</a>
{/if}
</p>


</body>
</html>