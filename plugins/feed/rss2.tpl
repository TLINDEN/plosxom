<?xml version="1.0" encoding="ISO-8859-1"?>
<rss version="2.0">
<channel>
   <title>{$config.blog_name} (RSS-Feed)</title>
   <link>{$config.whoami}</link>
   <description>{$config.blog_title}</description>
   <language>{$config.lang}-{$config.lang}</language>
   <copyright>Copyright 2007</copyright>
   <pubDate>{$lastmodified|date_format:"%a, %d %b %Y %T EST"}</pubDate>
   {section name=id loop=$posts}
   <item>
     <title><![CDATA[{$posts[id].title}]]></title>
     <description>
     <![CDATA[
     {$posts[id].text}
     ]]></description>
     <author>{$config.author} &lt;{$config.author_email}&gt;</author>
     <guid isPermaLink="false">{$posts[id].id}</guid>
     <link>{$config.whoami}/{$posts[id].category}/{$posts[id].id}</link>
   </item>
   {/section} 

</channel>
</rss>
