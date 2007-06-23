
        <!-- Right column -->
        <div id="col" class="noprint">
            <div id="col-in">

<ul><span>

<h2>Menu:</h2>
  <ul>
    <li><a href="{$config.whoami}">{#linkhome#}</a></li>
    <li><a href="{$config.whoami}/archive">{#linkarch#}</a></li>
  </ul>
 </li>

<br/>

<h2>Categories:</h2>
   <ul>
    {foreach item=cat from=$categories}
     <li><a href="{$config.whoami}/category/{$cat}">{$cat}</a></li>
    {/foreach}
   </ul>

<h2>Blogroll:</h2>
  <ul>
    {links config="links.conf" template="<li><a href='%1'>%2</a></li>"}
  </ul>
<br/>

{if $feedmeta}
<h2>Feeds</h2>
  <ul>
    <li class="rss-link"><a href="{$config.whoami}/feed/rss"  type="application/rss+xml">rss</a></li>
    <li class="rss-link"><a href="{$config.whoami}/feed/rss2" type="application/rss+xml">rss2</a></li>
    <li class="rss-link"><a href="{$config.whoami}/feed/atom" type="application/x.atom+xml">atom</a></li>
  </ul>
{/if}

<br/>

<h2>{$config.blog_name}</h2>
  <ul>
    <li><a href="{$config.author_link}">&copy; 2007 {$config.author}</a></li>
    <li><a href="http://code.google.com/p/plosxom/">Powered by Plosxom</a>
  </ul>
 
<br/>


</span></ul>