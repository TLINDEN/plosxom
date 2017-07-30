<div class="post-container">

<div class="post">
 <h2><a href="{$config.whoami}/{$post.category}/{$post.id}">{$post.title}</a></h2>
 {eval var=$post.text}
</div>

<div class="post-meta">
 <ul>
  <li class="post-date">
   <a href="{$config.whoami}/archive/{$post.mtime|date_format:'%Y%m%d'}"
      class="date">{$post.mtime|date_format:'%d.%m.%Y'}</a>
  </li>

 <li>posted in:</li>
 <li>
  <a title='{#postincat#} {$post.category}' href="{$config.whoami}/category/{$post.category}">{$post.category}</a>
 </li>


 </ul>
</div>

</div> <!-- container -->
