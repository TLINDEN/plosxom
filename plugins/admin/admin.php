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

class admin extends Plugin {
  var $mode;
  var $workpage;
  var $title;
  var $category;

  function register() {
    $this->add_handler("hook_url_filter", "admin");
    $this->add_handler("hook_send_header", "admin");
    $this->smarty->register_function("admin_edit", array(&$this, "admin_edit"));
  }

  function hook_send_header() {

  }

  function hook_url_filter($path) {
    if(preg_match("/^\/admin\/(edit|delete)\/([^\/]+?)$/", $path, $match)) {
      $this->mode     = $match[1];
      $this->workpage = $match[2];
    }
    elseif(preg_match("/^\/admin\/(edit|delete)\/([^\/]+?)\/([^\/]+?)$/", $path, $match)) {
      $this->mode     = $match[1];
      $this->category = $match[2];
      $this->workpage = $match[3];
    }
    elseif(preg_match("/^\/admin\/create$/", $path)) {
      $this->mode = "create";
    }
    elseif (preg_match("/^\/admin$/", $path)) {
      // index oder save
      $this->mode     = $_POST["mode"];
      $this->workpage = $_POST["workpage"];
      $this->title    = $_POST["title"];
      $this->category = $_POST["category"];
      if(! $this->mode ) {
	  $this->mode = "index";
      }
    }
    else {
      return false;
    }

    if($this->mode) {
      $this->template = "admin.tpl";
      $this->config["postings"] = 30;

      if($this->mode == "edit") {
	/*
	FIXME: make it possible to access the storage plugin the OOP way!
        $handler = $this->handler["hook_storage_fetch"][0];
	$post    = $this->plugins[$handler]->hook_storage_fetch($this->category, $this->posting);
        //$post = standard::hook_storage_fetch($this->category, $this->workpage);
	*/
	$post = standard::getfile($this->config["data_path"], $this->workpage . ".txt", $this->category);
	$this->smarty->assign("post", $post);
      }

      $this->smarty->assign("admin_mode", $this->mode);
      
      return true;
    }
  }

}


