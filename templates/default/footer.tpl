{*
 * Footer, will be appended to all pages
 *}

<hr noshade size="1">

{* add a basic menu *}
<p><a href="{$config.whoami}">{#linkhome#}</a> -
<a href="{$config.whoami}/archive">{#linkarch#}</a> -
<a href="{$config.whoami}/feed/rss">{#linkfeed#}</a> -
<a href="{$config.whoami}/page/about">About</a>

{* prepare url snippet for later use in paging generation below *}
{if $archive}
  {assign var="url" value="/archive/$archivestamp"}
{elseif $category}
  {assign var="url" value="/category/$category"}
{else}
  {assign var="url" value=""}
{/if}

{* are we displaying an archive of some kind? *}
{if $past}
  {if $posts}
    {* if there are no post, we are at the last page and do not display more 'past links *}
    - <a href="{$config.whoami}{$url}/past/{$past}">{#older#}</a>
  {/if}
  {if $newer}
    {if $newer == "null"}
       {* if we are at the first page, there are no more pages, display no newer link in this case *}
       - <a href="{$config.whoami}{$url}">{#newer#}</a>
    {else}
       - <a href="{$config.whoami}{$url}/past/{$newer}">{#newer#}</a>
    {/if}
  {/if}
{else}
  - <a href="{$config.whoami}{$url}/past/{$config.postings}">{#older#}</a>
{/if}
</p>


</body>
</html>

