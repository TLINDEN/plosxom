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
 # It supports RSS, RSS2 and ATOM style feeds.
 #
 # To install:
 #
 # copy feed.php to your plugin dir
 # copy the *.tpl files to your template dir
 #
 # somewhere in your header template add the following code:
 #
 # {if $feedmeta}
 #  {$feedmeta}
 # {/if}
 #
 # you may add a html link to a feed like this:
 #
 # <a href="{$config.whoami}/feed/rss">RSS Feed</a>
 #

class feed extends Plugin {
  var $version;

  function register() {
    $this->add_handler("hook_url_filter", "feed");
    $meta =  '<link rel="alternate"  type="application/x.atom+xml" title="atom feed" href="' . $this->config["whoami"] . '/feed/atom"/>' . "\n";
    $meta .= '<link rel="alternate"  type="application/rss+xml"    title="rss feed"  href="' . $this->config["whoami"] . '/feed/rss"/>'  . "\n";
    $meta .= '<link rel="alternate"  type="application/rss+xml"    title="rss2 feed" href="' . $this->config["whoami"] . '/feed/rss2"/>' . "\n";
    $this->smarty->assign("feedmeta", $meta);
  }

  function hook_url_filter($path) {
    #
    # we look for category and archive
    if(preg_match("/^\/feed\/rss$/", $path)) {
      $this->version  = "rss";
      $this->template = "shared/rss.tpl";
      $this->smarty->assign("feed", $this->version);
      $this->config["contenttype"] = 'application/rss+xml';
    }
    if(preg_match("/^\/feed\/rss2$/", $path)) {
      $this->version  = "rss2";
      $this->template = "shared/rss2.tpl";
      $this->smarty->assign("feed", $this->version);
      $this->config["contenttype"] = 'application/rss+xml';
    }
    if(preg_match("/^\/feed\/atom$/", $path)) {
      $this->version  = "atom";
      $this->template = "shared/atom.tpl";
      $this->smarty->assign("feed", $this->version);
      $this->config["contenttype"] = 'application/atom+xml';
    } 
    if($this->version) {
      $this->smarty->assign("feed", $this->version);
      return true;
    }
    else {
      return false;
    }
  }

}


