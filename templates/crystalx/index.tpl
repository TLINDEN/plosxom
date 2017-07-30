<!-- Content -->
{config_load file='lang.conf' section="$lang"}
{include file="crystalx/header.tpl"}

{* are we displaying an archive of some kind? *}
{* prepare url snippet for later use in paging generation below *}
{if $archive}
  {assign var="url" value="/archive/$archivestamp"}
{elseif $category}
  {assign var="url" value="/category/$category"}
{else}
  {assign var="url" value=""}
{/if}

<div id="content">

<div class="article">


{* content area *}


{* content area *}
{if $post}

 {* a single blog posting *} 
 {include file="crystalx/post.tpl" post="`$post`" }

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
     {include file="crystalx/post.tpl" post="`$post`" }
  {/foreach}

{elseif $page}

  {include file="crystalx/page.tpl" post="`$page`" }

{else}

   {* oops, no data? *}
   <h1>{#emptytitle#}</h1>
   <p>
   {#emptytext#}
   </p>

{/if}




<!-- end loop -->


{if $past}
  {if $posts}
    {* if there are no post, we are at the last page and do not display more 'past links *}
    <div class="f-left">
    <a href="{$config.whoami}{$url}/past/{$past}">{#older#}</a>
    </div>
  {/if}
  {if $newer}
    {if $newer == "null"}
       {* if we are at the first page, there are no more pages, display no newer link in this case *}
       <div class="f-right">
         <a href="{$config.whoami}{$url}">{#newer#}</a>
       </div>
    {else}
      <div class="f-right">
        <a href="{$config.whoami}{$url}/past/{$newer}">{#newer#}</a>
      </div>
    {/if}
  {/if}
{else}
 {if !$post}
  <div class="f-left">
   <a href="{$config.whoami}{$url}/past/{$config.postings}">{#older#}</a>
  </div>
 {/if}
{/if}



</div>

<hr class="noscreen" />
<!-- /content -->
</div>

{include file="crystalx/nav.tpl"}

                <hr class="noscreen" />
            
            </div> <!-- /col-in -->
        </div> <!-- /col -->

    </div> <!-- /page-in -->
    </div> <!-- /page -->

<!-- begin footer -->

{include file="crystalx/footer.tpl"}