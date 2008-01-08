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
  var $userlist;
  var $currentuser;

  function register() {
    $this->add_handler("hook_url_filter", "admin");
    $this->add_handler("hook_send_header", "admin");
    $this->userlist = parse_config("admin.conf");
  }

  function hook_send_header() {
    if(! $this->mode ) {
      # not in admin mode, do not authenticate
      return;
    }

    $authheader = "WWW-Authenticate: Basic realm='Plosxom Blog Admin Plugin'";
    if(! $_SERVER['PHP_AUTH_USER'] || ! $_SERVER['PHP_AUTH_PW']) {
      header($authheader);
      header('HTTP/1.0 401 Unauthorized');
      $this->smarty->assign("unauth", "You are not authorized to access this page!");
    }
    else {
      $this->currentuser = $_SERVER['PHP_AUTH_USER'];
      if (array_key_exists($this->currentuser, $this->userlist)) {
	$md5given = md5($_SERVER['PHP_AUTH_PW']);
	if($md5given != $this->userlist[$this->currentuser]) {
          header($authheader);
	  header('HTTP/1.0 401 Unauthorized');
	  $this->smarty->assign("unauth", "You are not authorized to access this page! Password missmatch!");
	}
        # else: user authenticated, just proceed. 
      }
      else {
        header($authheader);
	header('HTTP/1.0 401 Unauthorized');
	$this->smarty->assign("unauth", "You are not authorized to access this page! User does not exist!");
      }
    }
  }

  function hook_url_filter($path) {
    if(preg_match("/^\/admin/", $path) || $_POST['admin'] || $_GET['admin']) {
      $this->admin = true;
    }
    else {
      return false;
    }

    # preset mode
    $this->input['mode'] = 'admin_index';
    $menu = 'index';

    # fetch input
    foreach ($_GET as $option => $value) {
      $this->input[$option] = $value;
    }
    foreach ($_POST as $option => $value) {
      $this->input[$option] = $value;
    }

    $this->template = "admin.tpl";
    $this->config["postings"] = 30;

    switch($this->input['mode']) {
        case "admin_page_edit":
	                        $this->admin_page_edit();
				$menu = 'page';
				break;
				
        case "admin_page_save":
	                        $this->admin_page_save();
				$menu = 'page';
				break;

        case "admin_page_delete":
	                        $this->admin_page_delete();
				$menu = 'page';
				break;

        case "admin_users":     
	                        $this->admin_users();
				$menu = 'user';
				break;

        case "admin_users_save":
	                        $this->admin_users_save();
				$menu = 'user';
				break;

        case "admin_users_delete":
	                        $this->admin_users_delete();
				$menu = 'user';
				break;

        case "admin_plugins":
	                        $this->admin_plugins();
				$menu = 'plugin';
				break;

	case "admin_plugins_save":
	                        $this->admin_plugins_save();
				$menu = 'plugin';
				break;

	case "admin_plugins_delete":
	                        $this->admin_plugins_delete();
				$menu = 'plugin';
				break;
    }
      
    $this->smarty->assign("admin_mode", $this->input['mode']);
    $this->smarty->assign("menu", $menu);

    return true;
  }

  function admin_page_edit() {
    $post = standard::getfile($this->config["data_path"], $this->input['workpage'] . ".txt", $this->input['category']);
    $categories = standard::fetch_categories();
    $this->smarty->assign("post", $post);
    $this->smarty->assign("categories", $categories);
  }


  function admin_page_save() {
    $base        = $this->config["data_path"];
    $file        = $this->input['workpage'];
    $category    = $this->input['category'];
    $newcategory = $this->input['newcategory'];
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
      $this->smarty->assign("admin_msg", $this->input['workpage'] . " written successfully.");
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
 
  function admin_user_save() {
    $users = $this->userlist;
    if($this->input['password'] == $this->input['password2']) {
      if(strlen($this->input['password']) < 6) {
        $this->smarty->assign("admin_error", "Password too short!");
	$this->smarty->assign("admin_user", $this->input['workuser'] );
	$this->smarty->assign("admin_mode", "users_edit");
      }
      else {
        $users[$this->input['workuser']] = md5($this->input['password']);
	$this->userfile($users);
        $this->smarty->assign("admin_mode", "users");
      }
    }
    else {
      $this->smarty->assign("admin_user", $this->input['workuser'] );
      $this->smarty->assign("admin_mode", "users_edit");
      $this->smarty->assign("admin_error", "Passwords didn't match!");
    }
  }

  function admin_user_delete() {
    $users = $this->userlist;
    unset ($users[$this->input['workuser']]);
    $this->userfile($users);
    $this->smarty->assign("admin_mode", "users");
  }

  function admin_page_delete() {
    unlink($this->config["data_path"] . '/' . $this->input['category'] . '/' . $this->input['workpage'] . '.txt');
    $this->smarty->assign("admin_msg", $this->input['workpage'] . " removed successfully.");
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

  function admin_users() {
      $users = $this->userlist;
      ksort($users);
      $this->smarty->assign("admin_users", $users);
  }
}
