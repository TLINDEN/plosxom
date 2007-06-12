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

 #
 # This is the feed plugin for plosxom.
 #
 # To install:
 #


class feed extends Plugin {
  var $version;

  function register() {
    $this->add_handler("hook_url_filter", "feed");
    #$this->add_handler("hook_content", "feed");
  }

  function hook_send_header() {
    if($this->version) {
      $this->config["contenttype"] = 'application/rss+xml';
    }
  }

  function hook_content(&$text) {
    #
    # only for 0.91
    # we use 0.92, where we can use cdata
    #if($this->version == "rss") {
    #  # clean out html and stuff, rss 1.0 doesnt support it
    #  return htmlspecialchars(preg_replace('/<a href="(.*?)">(.*?)<.a>/si', '$2($1)', strip_tags($text)));
    #}
  }

  function hook_url_filter($path) {
    #
    # we look for category and archive
    if(preg_match("/^\/feed\/rss$/", $path)) {
      $this->version  = "rss";
      $this->template = "rss.tpl";
      $this->smarty->assign("feed", $this->version);
      $this->smarty->assign("lastmodified", $this->posts[0]);
      return true;
    }
    if(preg_match("/^\/feed/rss2$/", $path)) {
      $this->version  = "rss2";
      $this->template = "rss2.tpl";
      $this->smarty->assign("feed", $this->version);
      $this->smarty->assign("lastmodified", $this->posts[0]);
      return true;
    }
    if(preg_match("/^\/feed/atom$/", $path)) {
      $this->version  = "atom";
      $this->template = "atom.tpl";
      $this->smarty->assign("feed", $this->version);
      $this->smarty->assign("lastmodified", $this->posts[0]);
      return true;
    } 
    return false;
  }

}


