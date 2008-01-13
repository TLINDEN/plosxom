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
  var $admin;
  var $userlist;
  var $currentuser;

  function register() {
    $this->add_handler("hook_url_filter", "admin");
    $this->add_handler("hook_send_header", "admin");
    $this->add_handler("hook_content", "admin");
    $this->userlist = parse_config("admin.conf");
    $this->smarty->force_compile = 1;
    $this->smarty->caching       = 0;
  }

  function hook_send_header() {
    if(! $this->admin ) {
      # not in admin mode, do notthing
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
	else {
          # user authenticated
	  $this->proceed();
	}
      }
      else {
        header($authheader);
	header('HTTP/1.0 401 Unauthorized');
	$this->smarty->assign("unauth", "You are not authorized to access this page! User does not exist!");
      }
    }
  }

  function hook_url_filter($path) {
    $adreg = "/^\/admin/";
    if(    preg_match($adreg, $path)            # index.php/admin
	|| preg_match($adreg, $_POST['mode'])   # <input type=hidden name=mode value=admin_ ..
	|| preg_match($adreg, $_GET['mode'])    # index.php?mode=admin_...
	|| $_POST['admin']                      # <input type=hidden name=admin value=yes ..
	|| $_GET['admin']                       # index.php?admin=yes
	) {
      $this->admin = true;
      $this->template = "admin.tpl"; # overwrite index template, we are using our own
      return true;
    }
    else {
      return false;
    }
  }

  function proceed() {
    if(! $this->admin ) {
      # not in admin mode, do notthing
      return;
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

    # could be overwriten by some admin_* method
    $this->smarty->assign("admin_mode", $this->input['mode']);
    
    $this->config["postings"] = 30;

    switch($this->input['mode']) {
        case "admin_page_edit":
	                        $this->admin_page_edit();
				$menu = 'index';
				break;
				
        case "admin_page_save":
	                        $this->admin_page_save();
				$menu = 'index';
				break;

        case "admin_page_delete":
	                        $this->admin_page_delete();
				$menu = 'index';
				break;

        case "admin_users":     
	                        $this->admin_users();
				$menu = 'user';
				break;

        case "admin_users_save":
	                        $this->admin_users_save();
				$menu = 'user';
				break;

        case "admin_users_create":
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
      
    $this->smarty->assign("menu", $menu);
  }

  function admin_page_edit() {
    $post = standard::getfile($this->config["data_path"], $this->input['id'] . ".txt", $this->input['category']);
    if($post) {
      $categories = standard::fetch_categories();
      $this->smarty->assign("post", $post);
      $this->smarty->assign("categories", $categories);
    }
    else {
      $this->smarty->assign("admin_error", $this->input['id'] . " does not exist or permission denied!");
      $this->smarty->assign("admin_mode", "admin_index");
    }
  }

  function admin_page_delete() {
    $file = $this->config["data_path"] . '/' . $this->input['category'] . '/' . $this->input['id'] . '.txt';
    if( is_writable($file) && file_exists($file) ) {
      unlink($file);
      $this->smarty->assign("admin_msg", $this->input['id'] . " removed successfully.");
    }
    else {
      $this->smarty->assign("admin_error", $this->input['id'] . " does not exist or permission denied!");
    }
    $this->smarty->assign("admin_mode", "admin_index");
  }

  function admin_page_save() {
    $base        = $this->config["data_path"];
    $file        = $this->input['id'];
    $category    = $this->input['category'];
    $newcategory = $this->input['newcategory'];
    $content     = $this->input['title'] . "\n\n" . $this->input['content'] . "\n";

    if(! $file ) {
      $file = preg_replace("/[^a-z0-9A-Z\s\_\-\.]/", "", $this->input['title']);
      $create = true;
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
      $dir  = "$base/$newcategory";
    }
    else { 
      $file = "$base/$category/$file";
      $dir  = "$base/$category";
    }

    if(! file_exists($file) && ! is_writable($dir) ) {
      $this->smarty->assign("admin_error", "data directory is not writable!");
    }
    elseif( ! is_writable($file) && ! $create) {
      $this->smarty->assign("admin_error", "$file is not writable!");
    }
    else {
      $fd = fopen($file, 'w');
      fwrite($fd, stripslashes($content));
      fclose($fd); 
      chmod($file, 0777);
      $this->smarty->assign("admin_msg", '"' . $this->input['title'] . '" written successfully.');
    }

    $this->smarty->assign("admin_mode", "admin_index");
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
	$this->smarty->assign("admin_mode", "admin_users_edit");
      }
      else {
        $users[$this->input['workuser']] = md5($this->input['password']);
	$this->userfile($users);
        $this->smarty->assign("admin_mode", "admin_users");
      }
    }
    else {
      $this->smarty->assign("admin_user", $this->input['workuser'] );
      $this->smarty->assign("admin_mode", "admin_users_edit");
      $this->smarty->assign("admin_error", "Passwords didn't match!");
    }
    $this->admin_users();
  }

  function admin_user_delete() {
    $users = $this->userlist;
    unset ($users[$this->input['workuser']]);
    $this->userfile($users);
    $this->smarty->assign("admin_mode", "admin_users");
    $this->admin_users();
  }

  function admin_users() {
      $users = $this->userlist;
      ksort($users);
      $this->smarty->assign("admin_users", $users);
  }

  function pluginlist() {
    # generates a list of installed plugins
    $this->plugins = array();

    if ( file_exists($this->config["plugin_path"]) ) {
      $plugin_dh = opendir($this->config["plugin_path"]);
      while ( ( $file = readdir( $plugin_dh )) !== false) {
        if ( preg_match( '/^(.+?)\.nfo$/', $file, $match ) ) {
	  $plugin = $match[1];

	  if($plugin == "standard") {
	    continue;
	  }

	  $plugcfg = array("version" => "unversioned", 
			   "description" => "", "author" => "", 
			   "author_email" => "", "url" => "");

	  $cfgfile = $this->config["plugin_path"] . "/" . $plugin . ".nfo";
	  $plugcfg = parse_config($cfgfile);
	  
	  foreach ($plugcfg as $option => $value) {
	    $this->plugins[$plugin][$option] = $value;
	  }

	  $this->plugins[$plugin]['name'] = $plugin;

	  if(file_exists($this->config["config_path"] . "/" . $plugin . ".conf")) {
	    $this->plugins[$plugin]['config'] = 1;
	  }

	  if(file_exists($this->config["config_path"] . "/" . $plugin . ".disabled")) {
	    $this->plugins[$plugin]['state'] = 'inactive';
	  }
	  else {
	    $this->plugins[$plugin]['state'] = 'active';
	  }
	}
      }
    }

    sort($this->plugins);
  }

  function admin_plugins() {
    $this->pluginlist();
    $this->smarty->assign("plugins", $this->plugins);
  }

  function hook_content(&$text) {
    return $text;
    if ($this->input['mode'] == "admin_plugins") {
      print "<pre>";
      var_dump($this->plugins);
      print "</pre>";
    }
    return $text;
  }
}

 
 
 

