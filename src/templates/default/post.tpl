   <h2 class="blogtitle"><a href="{$config.whoami}/{$post.category}/{$post.id}">{$post.title}</a></h2>
   <div class="blogposting">
   {$post.text}
   </div>
   <p class="blogmeta">[ {#wroteby#} <a title='{#emailto#} {$config.author}' href="{$config.autor_link}">{$config.author}</a>
                        {#atdate#} {$post.mtime|date_format:"%A, %B %e, %Y"} ]<br/>
    [ <a title='{#postincat#} {$post.category}' href="{$config.whoami}/category/{$post.category}">{$post.category}</a> |
      <a title=permalink href="{$config.whoami}/{$post.category}/{$post.id}">#</a> | 
      <a href="javascript:HaloScan('{$post.category|upper}{$post.id|upper}');" target="_self"><script
        type="text/javascript">postCount('{$post.category|upper}{$post.id|upper}');</script></a> | 
      <a href="javascript:HaloScanTB('{$post.category|upper}{$post.id|upper}');" target="_self"><script
        type="text/javascript">postCountTB('{$post.category|upper}{$post.id|upper}');</script></a> |
      <a href='http://creativecommons.org/licenses/by-nc-sa/2.0/de/'>cc</a>
    ]
   </p>
   <br/><br/>

