<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<!--

Design by Free CSS Templates
http://www.freecsstemplates.org
Released for free under a Creative Commons Attribution 2.5 License

Title      : Balloonr/Bubbles
Version    : 1.0
Released   : 20070813
Description: A two-column design for 1024x768 resolutions suitable for blogs and forum sites.

-->
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
 @import url( {$config.baseurl}/templates/bubbles/style.css );
</style>

{if $feedmeta}
{$feedmeta}
{/if}


</head>
<body>
<!-- start header -->
<div id="header">
       
	<h1><a href="/">{$config.blog_title}</a></h1>

</div>
<!-- end header -->
<!-- start page -->
<div id="page">
	<!-- start content -->

{if $post}

 {* a single blog posting *} 
 {include file="bubbles/post.tpl" post="`$post`" }

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
     {include file="bubbles/post.tpl" post="`$post`" }
  {/foreach}

{elseif $page}

  {include file="bubbles/page.tpl" post="`$page`" }

{else}

   {* oops, no data? *}
   <h1>{#emptytitle#}</h1>
   <p>
   {#emptytext#}
   </p>

{/if}




<div id="content">
{if $past}
  {if $posts}
    {* if there are no post, we are at the last page and do not display more 'past links *}
    <div class="f-left">
    <a class="previous" href="{$config.whoami}{$url}/past/{$past}">{#older#}</a>
    </div>
  {/if}
  {if $newer}
    {if $newer == "null"}
       {* if we are at the first page, there are no more pages, display no newer link in this case *}
       <div class="f-right">
         <a class="next" href="{$config.whoami}{$url}">{#newer#}</a>
       </div>
    {else}
      <div class="f-right">
        <a class="next" href="{$config.whoami}{$url}/past/{$newer}">{#newer#}</a>
      </div>
    {/if}
  {/if}
{else}
 {if !$post}
  <div class="f-left">
   <a class="previous" ref="{$config.whoami}{$url}/past/{$config.postings}">{#older#}</a>
  </div>
 {/if}
{/if}

<br /><br />
</div>


{include file="bubbles/sidebar.tpl"}

</div>
<!-- end page -->

<div id="footer">
	<p id="legal">&copy;2008 All Rights Reserved. </p> 
	<p id="links">
         <a href="http://code.google.com/p/plosxom/" title="powered by">Plosxom</a>
         |
         <a href="http://www.freecsstemplates.org/preview/balloonr/" title="designed by">Free CSS Templates</a>
         |
         <a href="http://stumblepeach.com" title="customized by">StumblePeach</a>
         <br />
         </p>
</div>
</body>
</html>
