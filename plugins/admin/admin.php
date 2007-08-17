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
  var $workuser;
  var $workplugin;
  var $title;
  var $category;
  var $newcategory;
  var $pconfig;
  var $currentuser;

  function register() {
    $this->add_handler("hook_url_filter", "admin");
    $this->add_handler("hook_send_header", "admin");
    $this->smarty->register_function("admin_edit", array(&$this, "admin_edit"));
    $this->pconfig = parse_config("admin.conf");
  }

  function hook_send_header() {
    $authheader = "WWW-Authenticate: Basic realm='Plosxom Blog Admin Plugin'";
    if(! $_SERVER['PHP_AUTH_USER'] || ! $_SERVER['PHP_AUTH_PW']) {
      header($authheader);
      header('HTTP/1.0 401 Unauthorized');
      $this->smarty->assign("unauth", "You are no authorized to access this page!");
    }
    else {
      $this->currentuser = $_SERVER['PHP_AUTH_USER'];
      if (array_key_exists($this->currentuser, $this->pconfig)) {
	$md5given = md5($_SERVER['PHP_AUTH_PW']);
	if($md5given != $this->pconfig[$this->currentuser]) {
          header($authheader);
	  header('HTTP/1.0 401 Unauthorized');
	  $this->smarty->assign("unauth", "You are no authorized to access this page! Password missmatch!");
	}
        # else: user authenticated, just proceed. 
      }
      else {
        header($authheader);
	header('HTTP/1.0 401 Unauthorized');
	$this->smarty->assign("unauth", "You are no authorized to access this page! User does not exist!");
      }
    }
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
    elseif(preg_match("/^\/admin\/past\/(\d+?)$/", $path, $match)) {
      $this->mode = "index";
      $this->input["past"] = $match[1];
      if($this->input["past"] > $this->config["postings"]) {
         $newer = $this->input["past"] - $this->config["postings"];
      }
      else {
         $newer = "null";
      }
      $this->smarty->assign('newer', $newer);
      $this->smarty->assign('past', $this->input["past"] + $this->config["postings"]);
    }
    elseif (preg_match("/^\/admin$/", $path)) {
      // index oder save
      $this->mode        = $_POST["mode"];
      $this->workpage    = $_POST["workpage"];
      $this->title       = $_POST["title"];
      $this->category    = $_POST["category"];
      $this->newcategory = $_POST["newcategory"];
      $this->content     = $_POST["content"];
      if(! $this->mode ) {
	  $this->mode = "index";
      }
    }
    elseif(preg_match("/^\/admin\/users$/", $path)) {
      $this->mode = "users";
      if($_POST["mode"]) {
	  $this->mode = $_POST["mode"];
      }
      $this->workuser  = $_POST["username"];
      $this->password  = $_POST["password"];
      $this->password2 = $_POST["password2"];
      $users = $this->pconfig;
      ksort($users);
      $this->smarty->assign("admin_users", $users);
    }
    elseif(preg_match("/^\/admin\/users\/(edit|delete)\/(.+?)$/", $path, $match)) {
      $this->mode = "users_" . $match[1];
      $this->workuser = $match[2];
      $this->smarty->assign("admin_user", $this->workuser );
    }
    elseif(preg_match("/^\/admin\/users\/create$/", $path)) {
      $this->mode = "users_create";
    }
    elseif(preg_match("/^\/admin\/plugins$/", $path)) {
      $this->mode = "plugins";
      if($_POST["mode"]) {
	  $this->mode = $_POST["mode"];
      }
      $this->workplugin = $_POST["plugin"];
      $this->pluginlist();
    }
    elseif(preg_match("/^\/admin\/plugins\/install$/", $path)) {
      $this->mode = "plugins_install";
    }
    elseif(preg_match("/^\/admin\/plugins\/delete\/(.+?)$/", $path, $match)) {
      $this->workplugin = $match[1];
      $this->mode = "plugins_delete";
    }
    else {
      return false;
    }

    if($this->mode) {
      $this->template = "admin.tpl";
      $this->config["postings"] = 30;

      $this->smarty->assign("admin_mode", $this->mode);

      switch($this->mode) {
        case "edit":           $this->edit();         break;
        case "save":           $this->save();         break;
        case "users_save":     $this->usersave();     break;
        case "users_delete":   $this->userdelete();   break;
        case "delete":         $this->delete();       break;
	case "plugins_save":   $this->pluginsave();   break;
	case "plugins_delete": $this->plugindelete(); break;
      }
      
      return true;
    }
  }

  function edit() {
    $post = standard::getfile($this->config["data_path"], $this->workpage . ".txt", $this->category);
    $categories = standard::fetch_categories();
    $this->smarty->assign("post", $post);
    $this->smarty->assign("categories", $categories);
  }


  function save() {
    $base        = $this->config["data_path"];
    $file        = $this->workpage;
    $category    = $this->category;
    $newcategory = $this->newcategory;
    $content     = $this->title . "\n\n" . $this->content . "\n";

    if(! $file ) {
      $file = preg_replace("/[^a-z0-9A-Z\s\_\-\.]/", "", $this->title);
    }

    $file = preg_replace("/[\s\-_\/\\\(\)]+/", "_", $file);
    if (! preg_match("/\.txt$/", $file)) {
      $file .= ".txt";
    }

    if($category != $newcategory) {
      if (! is_dir("$base/$newcategory")) {
        mkdir("$base/$newcategory");
        chmod("$base/$newcategory", 0775);
      }
      if($category) {
        unlink("$base/$category/$file");
      }
      $file = "$base/$newcategory/$file";
    }
    else { 
      $file = "$base/$category/$file";
    }

    if(! file_exists($file) ) {
      $ping = 1;
    }

    if( ! is_writable($file)) {
      $this->smarty->assign("admin_error", "$category/$file is not writable!");
    }
    else {
      $fd = fopen($file, 'w');
      fwrite($fd, stripslashes($content));
      fclose($fd); 
      chmod($file, 0777);
      $this->smarty->assign("admin_msg", $this->workpage . " written successfully.");
    }
  }
 
  function userfile($data) {
        /* store admin.conf */
        $file = $this->config["config_path"] . "/admin.conf";
	if(! is_writable($file) ) {
          $this->smarty->assign("admin_error", "admin.conf is not writable!");
	}
	else {
          $fd = fopen($file, 'w');
          foreach ($data as $user => $md5) {
            fwrite($fd, $user . " = " . $md5 . "\n");
          }
          fclose($fd);
          chmod($file, 0777);
          $this->smarty->assign("admin_msg", "User saved.");
	}
  }
 
  function usersave() {
    $users = $this->pconfig;
    if($this->password == $this->password2) {
      if(strlen($this->password) < 6) {
        $this->smarty->assign("admin_error", "Password too short!");
	$this->smarty->assign("admin_user", $this->workuser );
	$this->smarty->assign("admin_mode", "users_edit");
      }
      else {
        $users[$this->workuser] = md5($this->password);
	$this->userfile($users);
        $this->smarty->assign("admin_mode", "users");
      }
    }
    else {
      $this->smarty->assign("admin_user", $this->workuser );
      $this->smarty->assign("admin_mode", "users_edit");
      $this->smarty->assign("admin_error", "Passwords didn't match!");
    }
  }

  function userdelete() {
    $users = $this->pconfig;
    unset ($users[$this->workuser]);
    $this->userfile($users);
    $this->smarty->assign("admin_mode", "users");
  }

  function delete() {
    unlink($this->config["data_path"] . '/' . $this->category . '/' . $this->workpage . '.txt');
    $this->smarty->assign("admin_msg", $this->workpage . " removed successfully.");
  }

  function pluginlist() {
      $this->plugins = array();
      foreach ($this->handler as $handler => $handler_list) {
        foreach ($handler_list as $plugin) {
          $this->plugins[$plugin]["handler"][$handler] = 1;
	  if(! array_key_exists("version", $this->plugins[$plugin])) {
	    $cfgfile = $this->config["plugin_path"] . "/" . $plugin . ".nfo";
	    $plugcfg = array("version" => "unversioned", "description" => "", "author" => "", "author_email" => "", "url" => "");
	    if(file_exists($cfgfile)) {
              $plugcfg = parse_config($cfgfile);
	    }
	    foreach ($plugcfg as $option => $value) {
              $this->plugins[$plugin][$option] = $value;
	    }
	  }
	}
      }
  }
}
