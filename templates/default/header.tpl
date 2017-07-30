<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd">
<html lang="{$config.lang}">
<head>
<link rel="stylesheet" type="text/css" href="{$config.baseurl}/templates/default/default.css">
<meta http-equiv="content-type" content="text/html; charset=iso-8859-1">

{if $feedmeta}
{$feedmeta}
{/if}

<meta name="generator" content="plosxom 1.02">

<title>
{if $singlepost}
{$post.title} 
{else}
{$config.blog_name} - {$config.blog_title}
{/if}
</title>

</head>
<body>
<h2 class='title'><a href='{$config.whoami}'>{$config.blog_name} - {$config.blog_title}</a></h2>
