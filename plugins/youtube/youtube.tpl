{if $mode == "ascii"}

  <!-- plain ascii link to original youtube page -->
  <a href="http://youtube.com/watch?v={$yid}">movie</a>

{elseif $mode == "image"}

  <!-- preview image with link to youtube page -->
  <a href="http://youtube.com/watch?v={$yid}"><img
     src="http://img.youtube.com/vi/{$yid}/default.jpg"
     border="0" title="watch movie at youtube"></a>

{else}

  <!--
       embed video player with youtube video.
       please note, that we are a little bit nicer
       to our users than youtube.com, we are providing
       a browser plugin download link for users without
       flash player installed (hint: flash is still
       no internet standard at all!) and a link to the
       youtube page if a browser doesn't support the
       embed html tag, if any.
   -->
 <div class="youtube">
  <object height="{$yheight}" width="{$ywidth}">
   <param name="movie" value="http://www.youtube.com/v/{$yid}">
    <embed src="http://www.youtube.com/v/{$yid}"
           type="application/x-shockwave-flash"
	   quality="high"
	   allowfullscreen="true"
	   height="{$yheight}"
	   pluginspage="http://www.adobe.com/go/getflashplayer"
	   width="{$ywidth}">
    <noembed>
     <a href="http://youtube.com/watch?v={$yid}">movie</a>
    </noembed>
  </object>
 </div>

{/if}
