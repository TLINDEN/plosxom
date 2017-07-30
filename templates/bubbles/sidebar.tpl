 <div id="sidebar">
  <ul>
   <li>
    <h2>navigate</h2>
    <ul>
      <li><a href="{$config.whoami}">{#linkhome#}</a></li>
      <li><a href="{$config.whoami}/archive">{#linkarch#}</a></li>
{if $feedmeta}
      <li><a href="{$config.whoami}/feed/rss2" type="application/rss+xml">RSS</a></li>
{/if}
    </ul>
   </li>

   <li>
     <h2>categories</h2>
     <ul>
       {foreach item=cat from=$categories}
        <li><a href="{$config.whoami}/category/{$cat}">{$cat}</a></li>
       {/foreach}
     </ul>
   </li>

   <li>
     <h2>Blogroll:</h2>
      <ul>
        {links config="blogroll.conf" template="<li><a href='%1'>%2</a></li>"}
      </ul>
   </li>

   <li>
     <h2>{$config.blog_name}</h2>
       <ul>
         <li><a href="{$config.author_link}">&copy; 2008 {$config.author}</a></li>
         <li><a href="http://code.google.com/p/plosxom/">Powered by Plosxom</a>
       </ul>
   </li> 

  </ul>
  <div style="clear: both;">&nbsp;</div>
 </div>
 <!-- end sidebar -->
