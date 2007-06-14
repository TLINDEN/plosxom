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

To install:

1. copy page.php to your plugins directory.

2. copy page.conf to your etc directory and configure it,
   you only have to assign it a directory which holds static
   pages (.txt files too).

3. create that directory.

4. edit your index template. Look for this context:

{elseif $posts}
[..]
{else}

5. before the {else} add this clause:

{elseif $page}

  {include file="default/page.tpl" post="`$page`" }

(change template name accordingly)

6. create .txt files in your pages directory.

7. to link to a static page, add this (eg. to your menu):

<a href="{$config.whoami}/page/about">About</a>

(here we are linking to an about page)

That's it

 *
 *
 */

class page extends Plugin{
  var $page;
  var $pconfig;

  function register() {
    $this->pconfig = parse_config("page.conf");
    if($this->pconfig["data"]) {
      $this->add_handler("hook_url_filter", "page");
    }
  }

  function hook_url_filter($path) {
    #
    # we look for category and archive
    if(preg_match("/^\/page\/([a-zA-Z0-9\_\-]*)$/", $path, $match)) {
      $this->page = $match[1];
      $this->add_handler("hook_content_filter", "page");
      $this->filter_plugin = $this;
      return true;
    }
    return false;
  }

  function hook_content_filter(&$files) {
    #
    # ok, ignore the $files list and just deliver the page in question
    $entry = standard::getfile($this->pconfig["data"], $this->page . ".txt", "", true); 
    if($entry) {
      $this->smarty->assign("page", $entry);
    }
    return array();
  }
}

?>
