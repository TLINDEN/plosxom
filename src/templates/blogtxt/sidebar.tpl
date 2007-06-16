<div id="col1" class="sidebar">

<ul>

 <!-- menu links -->
 <li id="category-links">
  <h2>Menu</h2>
  <ul>
    <li><a href="{$config.whoami}">{#linkhome#}</a></li>
    <li><a href="{$config.whoami}/archive">{#linkarch#}</a></li>
  </ul>
 </li>


 {if $categories}
  <li id="category-links">
   <h2>Categories</h2>
   <ul>
    {foreach item=cat from=$categories}
     <li><a href="{$config.whoami}/category/{$cat}">{$cat}</a></li>
    {/foreach}
  </li>
 {/if}

{if $feedmeta}
 <li id="rss-links">
  <h2></h2>
  <ul>
    <li class="rss-link"><a href="{$config.whoami}/feed/rss"  type="application/rss+xml">rss</a></li>
    <li class="rss-link"><a href="{$config.whoami}/feed/rss2" type="application/rss+xml">rss2</a></li>
    <li class="rss-link"><a href="{$config.whoami}/feed/atom" type="application/x.atom+xml">atom</a></li>
  </ul>
 </li>
{/if}

 <li id="info-copyright">
  <h2>{$config.blog_name}</h2>
  <ul>
    <li>&copy; 2007 {$config.author}</li>
    <li>Powered by <a href="http://code.google.com/p/plosxom/">Plosxom</a>
  </ul>
 </li>

</ul>
</div><!-- END COL2 / SIDEBEAR -->
