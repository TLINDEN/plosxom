   <h2 class="blogtitle"><a href="{$config.whoami}/page/{$post.id}">{$post.title}</a></h2>
   <div class="blogposting">
   {$post.text}
   </div>
   <p class="blogmeta">[ {#wroteby#} <a title='{#emailto#} {$config.author}' href="{$config.autor_link}">{$config.author}</a>
                        {#atdate#} {$post.mtime|date_format:"%A, %B %e, %Y"} ]
   </p>
   <br/><br/>
