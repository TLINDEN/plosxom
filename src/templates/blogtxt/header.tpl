<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">

<head>

<title>
{if $singlepost}
{$post.title} 
{else}
{$config.blog_name} - {$config.blog_title}
{/if}
</title>

<meta http-equiv="content-type" content="text/html; charset=iso-8859-1" />
<meta name="generator" content="plosxom 1.04">
<meta name="description" content="" />

<link rel="stylesheet" title="blog.txt" href="{$config.baseurl}/templates/blogtxt/style.css" type="text/css" media="all" />

{if $feedmeta}
{$feedmeta}
{/if}

<style type="text/css" media="all">
/*<![CDATA[*/
li#rss-links ul li.rss-link {literal}{{/literal} background: url({$config.baseurl}/templates/blogtxt/images/feed.png) no-repeat left center; {literal}}{/literal}
li#interact-links ul li.comment-link {literal}{{/literal} background: url({$config.baseurl}/templates/blogtxt/images/comment.png) no-repeat left center; {literal}}{/literal}
li#interact-links ul li.trackback-link {literal}{{/literal} background: url({$config.baseurl}/templates/blogtxt/images/trackback.png) no-repeat left center; {literal}}{/literal}
/*]]>*/
</style>

</head>

<body>

<div id="wrapper">

 <div id="container">

 <div id="header">
  <h1 id="title"><a href="{$config.whoami}" title="{$config.blog_name}">{$config.blog_name}</a></h1>
   <p id="description">{$config.blog_title}</p>
 </div>
<!-- END HEADER -->