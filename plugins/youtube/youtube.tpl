{if $mode == "ascii"}

  {if $yvideoid == "google"}

    <!-- plain ascii link to original google video page -->
    <a href="http://video.google.com/videoplay?docid={$yid}">movie</a>
 
  {elseif $yvideoid == "sevenload"}

    <!-- plain ascii link to sevenload page, we are linking to the english
         page, you may change the lang subdomain to your own language if
	 supported by sevenload. -->
    <a href="http://en.sevenload.com/videos/{$yid}/">movie</a>

  {else}

    <!-- plain ascii link to original youtube page -->
    <a href="http://youtube.com/watch?v={$yid}">movie</a>

  {/if}

{elseif $mode == "image"}

  {if $yvideoid == "google"}

    <!-- preview image with link to google video page -->
    <a href="http://video.google.com/videoplay?docid={$yid}"><img
      src="{$ygooglethumbnail}" border="0" title="watch movie at google video"/></a>

  {elseif $yvideoid == "sevenload"}

    <!-- I haven't found any way to extract the preview image uri from the video page
         so I just use the sevenload logo here. If someone knows how to extract it
	 send me a message - thanks -->
    <a href="http://en.sevenload.com/videos/{$yid}/"><img 
       src="http://page.sevenload.com/img/sevenload.gif" title="watch movie at sevenload"/></a>

  {else}
    <!-- preview image with link to youtube page -->
    <a href="http://youtube.com/watch?v={$yid}"><img
       src="http://img.youtube.com/vi/{$yid}/default.jpg"
       border="0" title="watch movie at youtube"></a>
  {/if}

{else}

  <div class="youtube">

  {if $yvideoid == "google"}

    <!-- embed video player with google video -->
    <object height="{$yheight}" width="{$ywidth}">
      <param name="movie" value="http://video.google.com/googleplayer.swf?docId={$yid}"/>
        <embed src="http://video.google.com/googleplayer.swf?docId={$yid}"
          id="VideoPlayback" type="application/x-shockwave-flash"
	  height="{$yheight}"
	  width="{$ywidth}"
          pluginspage="http://www.adobe.com/go/getflashplayer">
        </embed>
	<noembed>
          <a href="http://video.google.com/videoplay?docid={$yid}">movie</a>
        </noembed>
    </object>

  {elseif $yvideoid == "sevenload"}

    <!-- embed sevenload video player -->
    <object height="{$yheight}" width="{$ywidth}">
      <param name="FlashVars" value="slxml=en.sevenload.com"/>
      <param name="movie" value="http://en.sevenload.com/pl/{$yid}/{$ywidth}x{$yheight}/swf"/>
         <embed src="http://en.sevenload.com/pl/{$yid}/{$ywidth}x{$yheight}/swf"
	   type="application/x-shockwave-flash"
	   height="{$yheight}"
	   width="{$ywidth}"
	   allowfullscreen="true"
	   pluginspage="http://www.adobe.com/go/getflashplayer"
	   FlashVars="apiHost=api.sevenload.com">
	 </embed>
	 <noembed>
           <a href="http://en.sevenload.com/videos/{$yid}/">movie</a>
	 </noembed>
     </object>

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
    <object height="{$yheight}" width="{$ywidth}">
     <param name="movie" value="http://www.youtube.com/v/{$yid}"/>
      <embed src="http://www.youtube.com/v/{$yid}"
           type="application/x-shockwave-flash"
	   quality="high"
	   allowfullscreen="true"
	   height="{$yheight}"
	   width="{$ywidth}"
	   pluginspage="http://www.adobe.com/go/getflashplayer">
      <noembed>
       <a href="http://youtube.com/watch?v={$yid}">movie</a>
      </noembed>
    </object>

  {/if}

  </div>

{/if}
