<h1>
  <a href="{$config.whoami}/page/{$post.id}" title="Permanent link to {$post.title}" rel="bookmark">{$post.title}</a>
</h1>

<p class="info noprint">
<span class="date">{$post.mtime|date_format:"%d.%m.%Y"}</span><span class="noscreen"></span> |
<span class="user"><a title='{#emailto#} {$config.author}' href="{$config.autor_link}">{$config.author}</a></span><span class="noscreen"></span> |
</p>

 {$post.text}
