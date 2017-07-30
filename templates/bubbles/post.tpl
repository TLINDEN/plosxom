<div class="post">
  <h2 class="title">
    <a href="{$config.whoami}/{$post.category}/{$post.id}">{$post.mtime|date_format:"%d.%m.%Y"}</a>
  </h2>
</div>
      
<div class="post">
  <div class="entry">
          <h3>{$post.title}</h3>
          {eval var=$post.text}
  </div>
</div>
