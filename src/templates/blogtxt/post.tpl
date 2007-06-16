<div class="post">

 <h2 class="post-title">
  <a href="{$config.whoami}/{$post.category}/{$post.id}" title="{$post.title}" rel="bookmark">{$post.title}</a>
 </h2>

 <div class="post-entry">
 {$post.text}
 </div><!-- END POST-ENTRY -->
 
 <p class="post-footer">
   &para;
   <a href="{$config.whoami}/{$post.category}/{$post.id}" title="{$post.title}" rel="permalink">{$post.mtime|date_format:"%d.%m.%Y"}</a>
   &sect;
   <a href="{$config.whoami}/category/{$post.category}" title="{#postincat#} {$post.category}" rel="category tag">{$post.category}</a>
   &Dagger;
   <!-- haloscan comment -->
   <a href="javascript:HaloScan('{$post.category|upper}{$post.id|upper}');" target="_self"><script
         type="text/javascript">postCount('{$post.category|upper}{$post.id|upper}');</script></a>
  </p>
</div><!-- END POST -->

