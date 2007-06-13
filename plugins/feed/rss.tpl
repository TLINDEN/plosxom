<?xml version="1.0" encoding="iso-8859-1"?>
<rss version="0.92">
<channel>
   <title>{$config.blog_name} (RSS-Feed)</title>
   <link>{$config.whoami}</link>
   <description>{$config.blog_title}</description>
   <language>{$config.lang}-{$config.lang}</language>
   <copyright>Copyright 2007</copyright>
   {section name=id loop=$posts}
   <item>
     <title><![CDATA[{$posts[id].title}]]></title>
     <description><![CDATA[{$posts[id].text}]]></description>
     <link>{$config.whoami}/{$posts[id].category}/{$posts[id].id}</link>
   </item>
   {/section} 
</channel>
</rss>
