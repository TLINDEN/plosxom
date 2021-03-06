<?
 #
 #   $Id$
 #
 #   By  accessing  this software,  Plosxom,  you are  duly informed
 #   of and  agree to be  bound  by the  conditions  described below
 #   in this notice:
 #
 #   This software product, Plosxom, is developed  by Pali Dhar  and
 #   copyrighted (C) 2007  by  Pali Dhar,  with all rights reserved.
 #
 #   There is  no charge for  Plosxom software. You can redistribute
 #   it and/or modify it under the terms of the Artistic License 2.0
 #   which is incorporated by reference herein.
 #
 #   PLOSXOM IS PROVIDED "AS IS" AND WITHOUT  ANY EXPRESS OR IMPLIED
 #   WARRANTIES,   INCLUDING,   WITHOUT  LIMITATION,   THE   IMPLIED 
 #   WARRANTIES  OF  MERCHANTIBILITY  AND  FITNESS  FOR A PARTICULAR
 #   PURPOSE.
 #
 #   You   should  have  received a  copy  of  the  artistic license
 #   along with Plosxom.  You may download a copy of the license at:
 #
 #     http://dev.perl.org/licenses/artistic.html
 #
 #   Or contact:
 #
 #    "Pali Dhar" <nobrain@bk.ru>
 #

/*

This is the youtube plugin which allows you to
post youtube videos to your blog by just entering
the video id without the hassle of digging through
youtube html source to find the player embed code.

This plugin also supports google videos and
sevenload videos to be posted. Usage is identical
for all of them. Sevenload doesn't support image
preview mode so far.

This plugin requires version 1.06 of plosxom whose
templates are using the smarty eval() function for
post texts. If you don't have 1.06 installed you
can modify yout post.tpl template to use eval(),
just replace

  $post.text

with

  {eval var=$post.text}

1. copy youtube.php to your plugins directory.

2. copy youtube.tpl to your current template directory.

That's all about it.

To include a youtube video in a blog post, do the following:

- visit the youtube video page you want to post
- retrieve the video id, example:
  http://youtube.com/watch?v=OEXt9qb0zBI
                             ^^^^^^^^^^^  this is the video id
- in a new blog post add one of the following versions:

  o plain ascii html link to the youtube page:

    {youtube mode="ascii" id="jt4XH65rOxA"}

  o preview image with link to youtube page:

    {youtube mode="image" id="jt4XH65rOxA"}

  o display inline video player in the blog post:

    {youtube id="jt4XH65rOxA"}

  o the same as above but with custom dimensions:

    {youtube width="320" height="240" id="jt4XH65rOxA"}

*/

class youtube extends Plugin {

  function register() {
    $this->smarty->register_function("youtube", array(&$this, "getyoutube"));
  }
  
  function getyoutube($params, &$smarty) {
    $tpl    = 'youtube.tpl';
    $width  = 450;
    $height = 370;
    $yvideoid = "youtube";
    
    if( $params["id"]) {
      $id = $params["id"];
    }
    else {
      return;
    }

    if (ereg('^\-?[0-9]*$', $id)) {
      $yvideoid = "google";
    }
    elseif (strlen($id) < 10) {
      $yvideoid = "sevenload";
    }

    if ($yvideoid == "google") {
      /* adjust defaults */
      $width  = 400;
      $height = 326;
    }
    elseif ($yvideoid == "sevenload") {
      $width  = 380;
      $height = 313;
    }

    if( $params["width"]) {
      $width = $params["width"];
    }

    if( $params["height"]) {
      $height = $params["height"];
    }

    if( $params["template"] ) {
      $tpl = $params["template"];
    }

    if($yvideoid == "google" and $params["mode"] == "image") {
      /* need to fetch the img uri, mad enough there's no api
         to retrieve google video thumbnail images nor any way
	 to create the thumbnail url from the video id */
      $page = implode('', file("http://video.google.com/videoplay?docid=$id"));
      if (preg_match("#(http://video.google.com/ThumbnailServer[^\"]*)\"#", $page, $match)) {
	$this->smarty->assign("ygooglethumbnail", $match[1]);
      }
      else {
	/* hm, didn't find the thumbnail uri, fall back to link mode then */
	$params["mode"] = "ascii";
      }
    }

    $this->smarty->assign("yid",      $id);
    $this->smarty->assign("yheight",  $height);
    $this->smarty->assign("ywidth",   $width);
    $this->smarty->assign("mode",     $params["mode"]);
    $this->smarty->assign("yvideoid", $yvideoid);

    $this->smarty->display("shared/$tpl");
  }
  
}

?>
