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

ReadMore Plugin - display a "read more" link in list mode.

Requires: plosxom 1.06 or higher.

Known incompatibilities: for now, technorati tags (using the
technorait plugin) disappear in list mode, because they usually
are written at the last line of a posting which, together with
all the other stuff after the read-more slug, will be removed.

Hopefully this will be fixed sometime.

To install:

1. copy more.php to your plugin directory.

2. copy more.conf to your etc directory (optional).

That's it.

To use, write a post file and on the position where you want to
have the "read more" link to appear write:

<!--more-->

Everything after this slug will be removed if you are in list
mode, that is a list of postings will currently displayed. If
in single post mode, nothing happens to the slug so that the
complete unaltered posting appears.

 */


class more extends Plugin {
  var $tag;
  var $cfg;

  function register() {
    $this->add_handler("hook_content", "more");
    $this->cfg = array("more" => "read more");
    if(is_readable($this->config["config_path"] . "/more.conf")) {
      $this->cfg = parse_config("more.conf");
    }
  }

  function hook_content(&$post) {
    if(! $this->input["posting"]) {
      $post["text"] = preg_replace("/<!--\s*more\s*-->.*$/s",
             "<a href=\"" . $this->config["whoami"] . "/" . $post["category"] . "/" . $post["id"]
	     . "\">" . $this->cfg["more"] . "</a></p>", $post["text"]);
    }
    return $post;
  }
}


