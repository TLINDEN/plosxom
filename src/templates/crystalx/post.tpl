<h1>
  <a href="{$config.whoami}/{$post.category}/{$post.id}" title="Permanent link to {$post.title}" rel="bookmark">{$post.title}</a>
</h1>

<p class="info noprint">
<span class="date">{$post.mtime|date_format:"%d.%m.%Y"}</span><span class="noscreen"></span> |
<span class="user"><a title='{#emailto#} {$config.author}' href="{$config.autor_link}">{$config.author}</a></span><span class="noscreen"></span> |
<span class="cat">
<a href="{$config.whoami}/category/{$post.category}" title="{#postincat#} {$post.category}" rel="category tag">{$post.category}</a>
</span><span class="noscreen"></span>
</p>

 {$post.text}

<p class="btn-more box noprint"><small>
<!-- haloscan comment -->
   <a href="javascript:HaloScan('{$post.category|upper}{$post.id|upper}');" target="_self">
    <script type="text/javascript">postCount('{$post.category|upper}{$post.id|upper}');</script></a>
</small></p>

