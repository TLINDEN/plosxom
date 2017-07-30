<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">

<html xmlns="http://www.w3.org/1999/xhtml">
 <head>
<meta http-equiv="content-type" content="text/html; charset=utf-8" />
<title>
{if $singlepost}
{$post.title} 
{else}
{$config.blog_name} - {$config.blog_title}
{/if}
</title>

<meta name="author" content="{$config.autor}" />
<meta name="generator" content="plosxom 1.06" /> <!-- leave this for stats -->

<style type="text/css" media="screen">
 @import url( {$config.baseurl}/templates/blacktie/style.css );
</style>

{if $feedmeta}
{$feedmeta}
{/if}

<style type="text/css">
   /* Bottom */
   div#bottom {literal}{{/literal}
	position:relative;
	text-align:center;
	margin:0 auto 30px auto;
	width:430px;
	height:122px;
	background-image:url({$config.baseurl}/templates/blacktie/tie-bottom.png);
  {literal}}{/literal}

   a.permalink {literal}{{/literal}
	display:block;
	width:23px;
	height:23px;
	background-image:url({$config.baseurl}/templates/blacktie/permalink.png);
	position:absolute;
	left:-16px;
	top:20px;
	border:0 !important;
  {literal}}{/literal}
</style>

</head>

<body>

<div id="container">
   
 <div id="header">

  {include file="blacktie/sidebar.tpl"}

   <div id="top-right">
    {$config.blog_title}
   </div>

   <h1>{$config.blog_name}</h1>

 </div>




<div id="posts">

{if $post}

 {* a single blog posting *}
 {include file="blacktie/post.tpl" post="`$post`" }

{elseif $archivelist}

  {* output a list of archive links *}
  <ul>
    {foreach from=$archivedates item=date}
      <li>
        <a href="{$config.whoami}/archive/{$date|date_format:'%Y%m%d'}">{$date|date_format}</a>
      </li>
    {/foreach}
  </ul>

{elseif $posts}

  {* multiple postings, list them *}
  {foreach item=post from=$posts}
     {include file="blacktie/post.tpl" post="`$post`" }
  {/foreach}

{elseif $page}

  {include file="blacktie/page.tpl" post="`$page`" }

{else}

   {* oops, no data? *}
   <h1>{#emptytitle#}</h1>
   <p>
   {#emptytext#}
   </p>

{/if}

</div> <!-- posts -->

</div> <!-- container -->

  <div id="bottom">

{* prepare url snippet for later use in paging generation below *}
{if $archive}
  {assign var="url" value="/archive/$archivestamp"}
{elseif $category}
  {assign var="url" value="/category/$category"}
{else}
  {assign var="url" value=""}
{/if}

  <ul>
{* are we displaying an archive of some kind? *}
{if $past}
  {if $posts}
    {* if there are no post, we are at the last page and do not display more 'past links *}
    <li><a class="prev-link" href="{$config.whoami}{$url}/past/{$past}">&#171;</a></li>
  {/if}
  {if $newer}
    {if $newer == "null"}
       {* if we are at the first page, there are no more pages, display no newer link in this case *}
       <li><a class="next-link" href="{$config.whoami}{$url}">&#187;</a></li>
    {else}
       <li><a class="next-link" href="{$config.whoami}{$url}/past/{$newer}">&#187;</a></li>
    {/if}
  {/if}
{else}
   <li><a class="prev-link" href="{$config.whoami}{$url}/past/{$config.postings}">&#171;</a></li>
{/if}

   </ul>

  </div> <!-- bottom -->

  <div id="footer">
   <p><a href="http://code.google.com/p/plosxom/">Powered by Plosxom</a>
      | Theme by <a href="http://heather-rivers.com/">Heather Rivers</a></p>
  </div>
  
 </body>
</html>

