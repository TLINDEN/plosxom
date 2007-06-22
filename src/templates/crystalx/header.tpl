<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">

<head profile="http://gmpg.org/xfn/11">
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />

<title>
{if $singlepost}
{$post.title} 
{else}
{$config.blog_name} - {$config.blog_title}
{/if}
</title>

<script type="text/javascript" src="http://www.haloscan.com/load/YOURHALOSCANID/"> </script>

<meta name="author" content="{$config.autor}" />
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<meta name="generator" content="plosxom 1.05" /> <!-- leave this for stats -->

<!-- you may have to edit the CSS file so that background graphics can be found -->
<style type="text/css" media="screen">
 @import url( {$config.baseurl}/templates/crystalx/style.css );
</style>

{if $feedmeta}
{$feedmeta}
{/if}

</head>

<body>

<!-- Main -->
<div id="main" class="box">

    <!-- Header -->
    <div id="header">

        <!-- Logotyp -->
        <h3 id="logo">{$config.blog_name} - {$config.blog_title}
	
	{* add filter criteria title, if filter in use *}
	{if $archive}
	  - {#archtitle#}: {$archive|date_format}
	{elseif $category}
         - {#cattitle#}: {$category}
       {elseif $technoratitag}
         - Tag: {$technoratitag}
       {/if}
	
	</h3>
        <hr class="noscreen" />          

<!-- Search -->
<!--
deactivated. If there is a search plugin someday, you can enable it here
        <div id="search" class="noprint">
 <form id="searchform" method="get" action="/play/index.php">
                <fieldset><legend>Search</legend>
                    <label><span class="noscreen">Find:</span>
                    <span id="search-input-out">
		<input type="text" name="s" id="search-input" size="10" />
		</span></label>
                    

<input type="image" value="Go!"  src="{$config.baseurl}/templates/crystalx/images/_submit_blue.gif" id="search-submit" />


                </fieldset>
</form>

        </div>
-->
<!-- /search -->






    </div> <!-- /header -->

     <!-- Main menu (tabs) -->
     <div id="tabs" class="noprint">

            <ul class="box">
                <li><a href="{$config.whoami}">{#linkhome#}</a></li>
		<li><a href="{$config.whoami}/archive">{#linkarch#}</a></li>
		<li><a href="{$config.whoami}/page/aboout">About</a></li>
            </ul>

        <hr class="noscreen" />
     </div> <!-- /tabs -->

    <!-- Page (2 columns) -->
    <div id="page" class="box">
    <div id="page-in" class="box">
