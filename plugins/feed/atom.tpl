<?xml version="1.0" encoding="ISO-8859-1"?>
<feed
   xmlns="http://www.w3.org/2005/Atom"
   xmlns:rdf="http://www.w3.org/1999/02/22-rdf-syntax-ns#"
   xmlns:dc="http://purl.org/dc/elements/1.1/"
   xmlns:admin="http://webns.net/mvcb/"
>
   <link href="{$config.whoami}/feed/atom" rel="self"      title="{$config.blog_name} (atom feed)" type="application/atom+xml" />
   <link href="{$config.whoami}/feed/rss2" rel="alternate" title="{$config.blog_name} (rss2 feed)" type="application/rss+xml" />
   <link href="{$config.whoami}"           rel="alternate" title="{$config.blog_name}"             type="text/html" />
   <author><name>{$config.author}</name></author>
   <title>{$config.blog_name} (ATOM-Feed)</title>
   <subtitle type="html">{$config.blog_title}</subtitle>
   <id>{$config.whoami}</id>
   <updated>{$lastmodified|date_format:"%Y-%m-%dT%TZ"}</updated>
   <generator uri="http://code.google.com/p/plosxom/" version="1.02">plosxom</generator>
   <dc:language>{$config.lang}</dc:language>
   <admin:errorReportsTo rdf:resource="mailto:" />

   {section name=id loop=$posts}
     <entry>
        <link href="{$config.whoami}/{$posts[id].category}/{$posts[id].id}" rel="alternate" title="{$posts[id].title}"/>
        <author>
            <name>{$config.author}</name>
            <email>{$config.author_email}</email>
        </author>
        <published>{$posts[id].mtime|date_format:"%Y-%m-%dT%TZ"}</published>
	<updated>{$posts[id].mtime|date_format:"%Y-%m-%dT%TZ"}</updated>
        <id>{$config.whoami}/{$posts[id].category}/{$posts[id].id}</id>
        <title type="html">{$posts[id].title}</title>
        <category scheme="{$config.whoami}/{$posts[id].category}" label="{$posts[id].category}" term="{$posts[id].category}"/>
        <content type="html"><![CDATA[
            {$posts[id].text}
        ]]></content>
     </entry>
   {/section}
</feed>
