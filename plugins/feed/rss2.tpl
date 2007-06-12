<?xml version="1.0" encoding="ISO-8859-1"?>
<rss version="2.0">
<channel>
   <title>{$config.blog_name} (RSS-Feed)</title>
   <link>{$config.whoami}</link>
   <description>{$config.blog_title}</description>
   <language>{$config.lang}-{$config.lang}</language>
   <copyright>Copyright 2007</copyright>
   <pubDate>{$lastmodified}</<pubDate>
   {section name=id loop=$posts}
   <item>
     <title><![CDATA[{$posts[id].title}]]</title>
     <description><![CDATA[{$posts[id].text}]]></description>
     <author>{$config.author}</author>
     <guid>{$posts[id].id}</guid>
     <link>{$config.whoami}/{$posts[id].category}/{$posts[id].id}</link>
   </item>
   {/section} 

</channel>
</rss>
