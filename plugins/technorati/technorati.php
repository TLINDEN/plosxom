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
 # This is the technorati plugin for plosxom.
 #
 # To install:
 #
 # Add this to your stylesheet:
 # .tag {
 #  color: #c0c0c0;
 #  font-size: 70%;
 # }
 #
 # In your template replace:
 # {elseif $category}
 #  {assign var="url" value="/category/$category"}
 # {else}
 #
 # by
 # {elseif $category}
 #  {assign var="url" value="/category/$category"}
 # {elseif $technoratitag}
 #  {assign var="url" value="/tag/$technoratitag"}
 # {else}
 #
 # copy technorati.php to your plugin directory.
 # make it readable by your webserver.
 #
 #
 # To add technorati tags to your post add them to the
 # end of the posting file like this:
 # tag:technorati, tag:plosxom, tag:php, ...
 #


class technorati extends Plugin {
  var $tag;

  function register() {
    $this->add_handler("hook_url_filter", "technorati");
    $this->add_handler("hook_content", "technorati");
  }

  function hook_content(&$text) {
    $text = preg_replace("/tag:([^\.]*)$/m", "<div class=\"tag\">[Tags: tag:\\1]</div>", $text, 1);
    $text = preg_replace("/tag:([a-zA-Z0-9]+)/", '<a href="' . $this->config["whoami"] . "/tag/\\1\" rel=\"tag\">\\1</a>", $text);
    return $text;
  }

  function hook_url_filter($path) {
    #
    # we look for category and archive
    if(preg_match("/^\/tag\/([a-zA-Z0-9\_\-]*)$/", $path, $match)) {
      $this->tag = $match[1];
      $this->smarty->assign("technoratitag", $this->tag);
      $this->add_handler("hook_content_filter", "technorati");
      $this->filter_plugin = $this;
      return true;
    }
    if(preg_match("/^\/tag\/([a-zA-Z0-9\_\-]*)\/past\/(\d*)$/", $path, $match)) {
      $this->tag = $match[1];
      $this->smarty->assign("technoratitag", $this->tag);
      $this->input["past"] = $match[2];
      $this->add_handler("hook_content_filter", "technorati");
      $this->filter_plugin = "technorati";
      return true;
    }
    return false;
  }

  function hook_content_filter(&$files) {
        $posts = array();
	$filtered = array();
	$numfiles = count($files);
	$maxfiles = $this->input["past"] + $this->config["postings"];

        for($pos = 0; $pos < $numfiles; $pos++) {
          $entry = getfile($this->config["data_path"], $files[$pos]["file"], $files[$pos]["category"]);
	  if($entry) {
	    if(ereg('tag:' . $this->tag . '[^a-zA-Z0-9]', $entry["text"])) {
	      #  file content matches current tag
              $filtered[] = $entry;
	     }
	  }
          if(count($filtered) > $maxfiles) {
            # don't read more files as neccessary
            break;
          }
        }
	$numfiles = count($filtered);
	$this->input["numfiles"] = $numfiles;
	for($pos = $this->input["past"]; $pos < $maxfiles; $pos++) {
	  $posts[] = $filtered[$pos];
	}

        return $posts;
  }
}


