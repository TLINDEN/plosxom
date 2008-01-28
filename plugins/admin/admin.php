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
  var $imgreg = '(jpg|jpeg|gif|png)';

  function register() {
    if($this->config["version"] < 1.05) {
      die("The admin plugin requires at least plosxom version 1.05, this is: " . $this->config["version"]);
    }
    $this->add_handler("hook_url_filter", "admin");
    $this->add_handler("hook_send_header", "admin");
    $this->add_handler("hook_content", "admin");
    $this->userlist = parse_config("admin-users.conf");
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
      $this->template = "shared/admin.tpl"; # overwrite index template, we are using our own
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
    $this->input['mode'] = 'admin_post';
    $menu = 'post';

    # fetch input
    foreach ($_GET as $option => $value) {
      $this->input[$option] = $value;
    }
    foreach ($_POST as $option => $value) {
      $this->input[$option] = $value;
    }

    # could be overwriten by some admin_* method
    $this->smarty->assign("admin_mode", $this->input['mode']);
   
    # posts per page
    $this->config["postings"] = 30;

    # individual admin-plugin templates
    $this->smarty->assign("extra_tpl",    $this->get_templates("extra"));
    $this->smarty->assign("menu_tpl",     $this->get_templates("menu"));
    $this->smarty->assign("main_tpl",     $this->get_templates("main"));
    $this->smarty->assign("submenu_tpl",  $this->get_templates("submenu"));
    $this->smarty->assign("postsave_tpl", $this->get_templates("postsave"));

    # switch to specific processing method
    if(preg_match("/^admin_([a-z]+)/", $this->input['mode'], $match)) {
      $menu = $match[1];
      $method = $this->input['mode'];

      if(is_callable(array($this, $method))) {
        $this->$method();
	$called = true;
      }

      foreach ($this->get_handlers($this->input['mode']) as $handler) {
	if(is_callable(array($this->registry->plugins[$handler], $method))) {
          $this->registry->plugins[$handler]->$method();
          $called = true;
	}
      }

      if(! $called) {
        $this->smarty->append("admin_error", "unsupported admin mode: $method");
      }
    }

    if($this->input['back']) {
      if(preg_match("/^admin_([a-z]+)/", $this->input['back'], $match)) {
	$menu = $match[1];
      }
    }

    $this->smarty->assign("menu", $menu);
  }

  function admin_post() {
    $handler = $this->registry->get_handler("hook_storage_fetchall");
    $posts   = $this->registry->plugins[$handler]->hook_storage_fetchall();
    $this->smarty->assign('posts', $posts);
  }

  function admin_config() {
    $configs = array();
    $dh = opendir($this->config['config_path']);
    if($dh) {
      while ( ( $F = readdir( $dh )) !== false) {
	if($F == "." or $F == "..") {
	  continue;
	}
	if ( preg_match("/\.conf$/", $F)) {
	  $configs[] = $F;
	}
      }
      closedir($dh);
      sort($configs);
    }
    else {
      $this->smarty->append("admin_error", "config directory not readable!");
    }
    $this->smarty->assign("configs", $configs);
  }

  function admin_config_edit() {
    $filename = $this->config['config_path'] . '/'. $this->input['configfile'];
    $back = 'admin_config';

    if($this->input['back']) {
      $back = $this->input['back'];
      $this->smarty->assign("back", $back);
    }
    if(is_readable($filename) and ereg('\.conf$', $filename)) {
      $content = implode('', file($filename));
      $this->smarty->assign("configcontent", $content);
      $this->smarty->assign("configfile", $this->input['configfile']);
    }
    else {
      $this->smarty->append("admin_error", "configfile " . $this->input['configfile'] . " does not exist or is not readable!");
      $this->admin_config();
      $this->smarty->assign("admin_mode", "admin_config");
    }
  }

  function admin_config_view() {
    $this->smarty->append("admin_test", "test message");
    $this->admin_config_edit();
  }


  function admin_config_save() {
    $filename = $this->config['config_path'] . '/'. $this->input['configfile'];
    if($this->write($filename, $this->input['configcontent'])) {
      $this->smarty->append("admin_msg", '"' . $this->input['configfile'] . '" written successfully.');
    }
    $this->admin_config();
    $back = 'admin_config';
    if($this->input['back']) {
      $back = $this->input['back'];
      if($back == 'admin_plugin') {
	$this->admin_plugin();
      }
    }
    $this->smarty->assign("admin_mode", $back);
  }

  function admin_post_edit() {
    if($this->input['id']) {
      $post = standard::getfile($this->config["data_path"], $this->input['id'] . ".txt", $this->input['category'], true, false);
      if($post) {
	$categories = standard::fetch_categories();
	$this->smarty->assign("post", $post);
	$this->smarty->assign("categories", $categories);
      }
      else {
	$this->smarty->append("admin_error", $this->input['id'] . " does not exist or permission denied!");
	$this->smarty->assign("admin_mode", "admin_post");
	$this->admin_post();
      }
    }
  }


  function admin_post_view() {
    $this->admin_post_edit();
  }


  function admin_post_delete() {
    $file = $this->config["data_path"] . '/' . $this->input['category'] . '/' . $this->input['id'] . '.txt';
    if( $this->unlink($file) ) {
      $this->smarty->append("admin_msg", $this->input['id'] . " removed successfully.");
    }
    # else: error stored in unlink()
    $this->smarty->assign("admin_mode", "admin_post");
    $this->admin_post();
  }
  
  function admin_post_save() {
    $base        = $this->config["data_path"];
    $file        = $this->input['id'];
    $category    = $this->input['category'];
    $newcategory = $this->input['newcategory'];
    $content     = $this->input['title'] . "\n\n" . $this->input['content'] . "\n";

    $this->smarty->assign("admin_mode", "admin_post");

    if(! $file ) {
      $file = preg_replace("/[^a-z0-9A-Z\s\_\-\.\/\\\(\)]/", "", $this->input['title']);
      $create = true;
    }

    $file = preg_replace("/[\s\-_\/\\\(\)]+/", "_", $file);
    if (! preg_match("/\.txt$/", $file)) {
      $file .= ".txt";
    }

    if($category != $newcategory) {
      if (! is_dir("$base/$newcategory")) {
	if(! $this->mkdir("$base/$newcategory") ) {
	  $this->admin_post();
	  return;
	}
      }
      if($category) {
	if( ! $this->unlink("$base/$category/$file")) {
	  return;
	}
	else {
	  $this->rmdir("$base/$category"); # we ignore errors here and keep such directories
	}
      }
      $file = "$base/$newcategory/$file";
      $dir  = "$base/$newcategory";
      $create = true;
    }
    else { 
      $file = "$base/$category/$file";
      $dir  = "$base/$category";
    }

    if(! file_exists($file) && ! is_writable($dir) ) {
      $this->smarty->append("admin_error", "data directory is not writable!");
    }
    elseif( ! is_writable($file) && ! $create) {
      $this->smarty->append("admin_error", "$file is not writable!");
    }
    else {
      if($this->write($file, $content)) {
        $this->smarty->append("admin_msg", '"' . $this->input['title'] . '" written successfully.');
	foreach ($this->get_handlers('admin_postsave') as $handler) {
	  $this->registry->plugins[$handler]->admin_postsave();
	}
      }
    }
    $this->admin_post();
  }
 
  function userfile($data) {
    /* store admin.conf */
    $file = $this->config["config_path"] . "/admin-users.conf";
    $content = '';
    foreach ($data as $user => $md5) {
      $content .= $user . ' = ' . $md5 . "\n";
    }
    return $this->write($file, $content); 
  }

  function admin_user_create() {}

  function admin_user_edit() {
    $users = $this->userlist;
    if(array_key_exists($this->input['username'], $users)) {
      $this->smarty->assign("username", $this->input['username']);
    }
    else {
      $this->smarty->append("admin_error", "user " . $this->input['username'] . "doesn't exist!");
      $this->smarty->assign("admin_mode", "admin_user");
      $this->admin_user();
    }
  }

  function admin_user_save() {
    $users = $this->userlist;
    if($this->input['password'] == $this->input['password2']) {
      if(strlen($this->input['password']) < 6) {
        $this->smarty->append("admin_error", "Password too short!");
	$this->smarty->assign("admin_user", $this->input['username'] );
	$this->smarty->assign("admin_mode", "admin_user_edit");
      }
      else {
        $users[$this->input['username']] = md5($this->input['password']);
	if ( $this->userfile($users) ) {
          $this->smarty->append("admin_msg", "User " . $this->input['username'] . " has been saved.");
	  $this->userlist = parse_config("admin-users.conf");
	}
        $this->smarty->assign("admin_mode", "admin_user");
      }
    }
    else {
      $this->smarty->assign("admin_user", $this->input['username'] );
      $this->smarty->assign("admin_mode", "admin_user_edit");
      $this->smarty->append("admin_error", "Passwords didn't match!");
    }
    $this->admin_user();
  }


  function admin_user_delete() {
    $users = $this->userlist;
    unset ($users[$this->input['username']]);
    if ( $this->userfile($users) ) {
      $this->smarty->append("admin_msg", "User " . $this->input['username'] . " has been deleted.");
      $this->userlist = parse_config("admin-users.conf");
    }
    $this->smarty->assign("admin_mode", "admin_user");
    $this->admin_user();
  }

  function admin_user() {
      $users = $this->userlist;
      ksort($users);
      $this->smarty->assign("admin_user", $users);
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

	  if(file_exists($this->config["plugin_path"] . "/" . $plugin . ".txt")) {
            $this->plugins[$plugin]['help'] = 1;
          }

	  if(file_exists($this->config["plugin_path"] . "/" . $plugin . ".disabled")) {
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

  function admin_plugin_help() {
    $file = $this->config["plugin_path"] . "/" . $this->input['plugin'] . '.txt';
    if(is_readable($file)) {
      $help = implode('', file($file));
      $this->smarty->assign("plugin_help", $help);
      $this->smarty->assign("plugin", $this->input['plugin']);
    }
    else {
      $this->smarty->append("admin_error", $this->input['plugin'] . " does not have a help file installed!");
      $this->admin_plugin();
      $this->smarty->assign("admin_mode", 'admin_plugin');
    }
  }

  function admin_plugin() {
    $this->pluginlist();
    $this->smarty->assign("plugins", $this->plugins);
  }

  function admin_plugin_install() {
    if(! is_writable($this->config["plugin_path"]) or ! is_writable($this->config["template_path"] . '/shared')) {
      $this->smarty->append("admin_error", "plugin path or shared template path not writable!");
      $this->admin_plugin();
      $this->smarty->assign("admin_mode", 'admin_plugin');
    }
  }

  function admin_plugin_upload() {
    $orig = basename($_FILES['archive']['name']);
    $tmp  = $_FILES['archive']['tmp_name'];
    $err  = $_FILES['archive']['error'];
    $size = $_FILES['archive']['size'];
    $info = '';
    $help = '';

    if($error) {
      $this->smarty->append("admin_error", $error);
    }
    else {
      if(preg_match("/^(.*)\.zip$/", $orig, $match)) {
	$plugin = preg_replace("/[\"\*\'\`\]\[\s\/\\\(\)]/", '', $match[1]);
	if($size > 0) {
	  $zipfile = $this->config['tmp_path'] . '/' . $plugin . '.zip';
	  if(move_uploaded_file($tmp, $zipfile)) {
	    $zip = new ZipArchive();
	    $zip->open($zipfile);
	    for ($pos = 0; $pos < $zip->numFiles; $pos++) {
	      $file = $zip->statIndex($pos);
	      $fd = $zip->getStream($file['name']);
	      if($fd) {
		$content = '';
		while (!feof($fd)) {
		  $content .= fread($fd, 2);
		}
		fclose($fd);
		if(preg_match("/\.tpl$/", $file['name'])) {
		  if($this->write($this->config['template_path'] . '/shared/' . basename($file['name']), $content, true)) {
		    $info .= "Extracted " . basename($file['name']) . " to " . $this->config['template_path'] . '/shared/' . "<br/>";
		  }
		}
		elseif(preg_match("/\.conf$/", $file['name'])) {
		  if($this->write($this->config['config_path'] . '/' . basename($file['name']), $content, true)) {
                    $info .= "Extracted " . basename($file['name']) . " to " . $this->config['config_path'] . "<br/>";
                  }
		}
		elseif(preg_match("/\./", $file['name'])) {
		  if($this->write($this->config['plugin_path'] . '/' . basename($file['name']), $content, true)) {
                    $info .= "Extracted " . basename($file['name']) . " to " . $this->config['plugin_path'] . "<br/>";
                  }
		  if(preg_match("/\.txt$/", $file['name'])) {
		    $help = $content;
		  }
		}
	      }
	      else {
		$this->smarty->append("admin_error", "error extracting " . $file['name'] . " from $zipfile!");
		break;
	      }
	    }
	    $this->unlink($zipfile);
	  }
	  else {
	    $this->smarty->append("admin_error", "possible upload attack, aborted!");
	  }
	}
	else {
	  $this->smarty->append("admin_error", "uploaded file has 0 bytes!");
	}
      }
      else {
	$this->smarty->append("admin_error", "ZIP file expected!");
      }
    }
    if($info) {
      $this->smarty->append("admin_info", $info);
      if($help) {
	$this->smarty->assign("plugin_help", $help);
      }
    }
    $this->admin_plugin();
    $this->smarty->assign("admin_mode", 'admin_plugin');
  }

  function admin_plugin_delete() {
    $plugin = $this->input['plugin'];
    $info   = '<br/>';
    if($plugin) {
      $php = $this->config["plugin_path"] . '/' . $plugin . '.php';
      $nfo = $this->config["plugin_path"] . '/' . $plugin . '.nfo';
      $txt = $this->config["plugin_path"] . '/' . $plugin . '.txt';
      foreach (array($php, $txt, $nfo) as $file) {
	if(file_exists($file) and is_writable($file) and is_writable($this->config["plugin_path"])) {
	  if($this->unlink($file)) {
	    $info .= "removed $file<br/>";
	  }
	}
	else {
	  $info .= "$file does not exist or permission denied!<br/>";
	}
      }
    }
    else {
      $this->smarty->append("admin_error", "no plugin given");
    }
    $this->smarty->append("admin_info", $info);
    $this->admin_plugin();
    $this->smarty->assign("admin_mode", 'admin_plugin');
  }

  function admin_plugin_changestate() {
    $disabled = $this->config["plugin_path"] . '/' . $this->input['plugin'] . '.disabled';
    if(is_writable($this->config["plugin_path"])) {
      if($this->input['newstate'] == 'inactive') {
	if($this->write($disabled, ' ')) {
	  $this->smarty->append("admin_msg", $this->input['plugin'] . " deactivated.");
	}
      }
      else {
	if(file_exists($disabled)) {
	  if($this->unlink($disabled)) {
	    $this->smarty->append("admin_msg", $this->input['plugin'] . " activated.");
	  }
	}
      }
    }
    else {
      $this->smarty->append("admin_error", "Plugin path not writable!");
    }
    $this->admin_plugin();
    $this->smarty->assign("admin_mode", 'admin_plugin');
  }

  function hook_content(&$text) {
    return $text;
    if ($this->input['mode'] == "admin_plugin") {
      print "<pre>";
      var_dump($this->plugins);
      print "</pre>";
    }
    return $text;
  }

  function write($file, $content, $dontstrip=false) {
    if(! file_exists($file)) {
      $dir = dirname($file);
      if (! is_dir($dir) || ! is_writable($dir)) {
        $this->smarty->append("admin_error", "'$dir' is not a directory or does not exist!");
        return false;
      }
    }

    $fd = fopen($file, 'w');
    if ( ! $fd ) {
      $this->smarty->append("admin_error", "could not open file '$file'!");
      return false;
    }
    else {
      if(! $dontstrip) {
	$content = stripslashes($content);
      }
      if (! fwrite($fd, $content)) {
        $this->smarty->append("admin_error", "could not write to file '$file'");
        return false;
      }
      else {
        fclose($fd); 
        chmod($file, 0666);
        return true;
      }
    }
  }

  function mkdir($dir) {
    $base = dirname($dir);
    if( ! is_writable($base) ) {
      $this->smarty->append("admin_error", "directory '$base' is not writable!");
      return false;
    }
    else {
      if ( mkdir($dir) ) {
	chmod($dir, 0775);
	return true;
      }
      else {
	$this->smarty->append("admin_error", "could not create directory '$dir'!");
	return false;
      }
    }
  }

  function unlink($file) {
    if(! file_exists($file)) {
      $this->smarty->append("admin_error", "file '$file' does not exist anymore!");
      return false;
    }
    else {
      if( ! unlink($file)) {
	$this->smarty->append("admin_error", "could not remove file '$file'!");
	return false;
      }
      else {
	return true;
      }
    }
  }

  function rmdir($dir) {
    if (! is_dir($dir)  || ! is_writable($dir)) {
      $this->smarty->append("admin_error", "'$dir' is not a directory or does not exist!");
      return false;
    }

    $dh = opendir($dir);
    if ( ! $dh ) {
      $this->smarty->append("admin_error", "could not open directory '$dir'!");
      return false;
    }

    $empty = true;
    while ( ( $F = readdir( $dh )) !== false) {
      if($F !== "." and $F !== "..") {
	$empty = false;
	break;
      }
    }
    closedir($dh);

    if( $empty ) {
      if(! rmdir($dir)) {
	$this->smarty->append("admin_error", "could not remove directory '$dir'!");
	return false;
      }
      else {
	$this->smarty->append("admin_info", "directory '$dir' have been removed.");
	return true;
      }
    }
    else {
      $this->smarty->append("admin_error", "directory '$dir' is not empty!");
      return false;
    }
  }


  function media_get_thname($image) {
    $normal = 'normal___' . $this->config['image_normal_width'] . 'x' . $this->config['image_normal_width'] . '_' . $image;
    $thumb  = '___' . $image;
    return array($normal, $thumb);
  }

  function admin_media_delete() {
    if($this->input['image']) {
      $names  = $this->media_get_thname($this->input['image']);
      $thumb  = $this->config['image_path'] . '/' . $names[0];
      $normal = $this->config['image_path'] . '/' . $names[1];
      $image  = $this->config['image_path'] . '/' . $this->input['image'];
      $info = '<br/>';
      foreach (array($thumb, $normal, $image) as $file) {
	if(file_exists($file) and is_writable($file)) {
	  if($this->unlink($file)) {
	    $info .= "Removed $file<br/>";
	  }
	  else {
	    $info .= "Could not remove $file<br/>";
	  }
	}
	else {
	  $info .= "$file does not exist!<br/>";
	}
      }
    }
    else {
      $this->smarty->append("admin_error", "no image filename given!");
    }
    $this->smarty->append("admin_info", $info);
    $this->smarty->assign("admin_mode", "admin_media");
    $this->admin_media();
  }

  function make_thumb($original, $thumbnail, $reflection = false) {
    include_once($this->config["lib_path"] . '/thumbnail.inc.php');
    $T = new Thumbnail($original);
    $T->resize(150,150);
    $T->cropFromCenter(100);
    if($reflection) {
      $T->createReflection(40,15,90,true,'#a4a4a4');
    }
    $T->save($thumbnail);
    chmod($thumbnail, 0666);
  }

  function make_normalized($original, $normalized, $maxwidth) {
    include_once($this->config["lib_path"] . '/thumbnail.inc.php');
    $T = new Thumbnail($original);
    if($T->getCurrentWidth() > $maxwidth) {
      $T->resize($maxwidth, $maxwidth);
      $T->save($normalized);
      chmod($normalized, 0666);
    }
  }

  function admin_media() {
    $files      = $this->scan_dir($this->config['image_path']);
    $thumbnails = array();
    foreach ($files as $file) {
      $entry  = array();
      if(preg_match("/\." . $this->imgreg . "$/i", $file)) {
	$image = $file;
	$names  = $this->media_get_thname($image);
	$normal = $names[0];
	$thumb  = $names[1];
      
	$original   = $this->config['image_path'] . '/' . $image;
	$thumbnail  = $this->config['image_path'] . '/' . $thumb;
	$normalized = $this->config['image_path'] . '/' . $normal;

	if(! file_exists($thumbnail) ) {
	  $this->make_thumb($original, $thumbnail, true);
	}
	else {
	  if(filemtime($original) > filemtime($thumbnail)) {
	    /* original image has been modified, recreate the thumbnail */
	    $this->make_thumb($original, $thumbnail, true);
	  }
	}

	if($this->config['image_normal_width']) {
	  if(! file_exists($normalized) ) {
	    $this->make_normalized($original, $normalized, $this->config['image_normal_width']);
	  }
	  else {
	    if(filemtime($original) > filemtime($normalized)) {
	      /* original image has been modified, recreate the normalized versoin */
	      $this->make_normalized($original, $normalized, $this->config['image_normal_width']);
	    }
	  }
	  $entry['normal'] = $normal;
	}

	$entry['thumbnail'] = $this->config['imgurl'] . '/' . $thumb;
	$entry['orig']      = $image;
	$entry['isimage']   = 1;
      }
      else {
	if(preg_match("/.*\.(.+)$/", $file, $match)) {
	  $suffix = $match[1];
	}
	else {
	  $suffix = "unknown";
	}
	if(file_exists($this->config['template_path'] . '/shared/' . $suffix . '.png')) {
	  $entry['thumbnail'] = $this->config['baseurl'] . '/templates/shared/' . $suffix . '.png';
	}
	else {
	  $entry['thumbnail'] = $this->config['baseurl'] . '/templates/shared/unknown.png';
	}
	$entry['orig']      = $file;
      }
      $thumbnails[] = $entry;
    }
    $this->smarty->assign("images", $thumbnails);
  }

  function scan_dir($dir) {
    if(is_dir($dir) and is_readable($dir)) {
      $handle = opendir($dir);
      $files  = array();

      while(($file = readdir($handle))!== false) {
	if(preg_match("/^___/", $file) or preg_match("/^normal___/", $file) or is_dir("$dir/$file")) {
	  continue;
	}
	if(is_readable("$dir/$file")) {
	  $mtime = filemtime("$dir/$file");
	  $entry = array("mtime" => $mtime, "file" => $file);
	  $files[] = $entry;
	}
      }
    
      closedir($handle);

      # reverse sort the files
      $sort = array();
      foreach ($files as $pos => $entry) {
	$sort[$pos] = $entry["mtime"];
      }
      array_multisort($sort, SORT_DESC, $files);

      $sorted = array();
      foreach ($files as $pos => $entry) {
        $sorted[$pos] = $entry["file"];
      }

      return $sorted;
    }
    else {
      $this->smarty->append("admin_error", "directory '$dir' does not exist or is not readable!");
      return array();
    }
  }


  function admin_media_upload() {}

  function admin_media_uploadfile() {
    $orig = basename($_FILES['mediafile']['name']);
    $tmp  = $_FILES['mediafile']['tmp_name'];
    $err  = $_FILES['mediafile']['error'];
    $size = $_FILES['mediafile']['size'];

    if($error) {
      $this->smarty->append("admin_error", $error);
    }
    else {
      if($size > 0) {
	$dest = $this->config['image_path'] . '/' . preg_replace("/[\"\*\'\`\]\[\s\/\\\(\)]/", '', $orig);
	if(move_uploaded_file($tmp, $dest)) {
	  chmod($dest, 0666);
	  $this->smarty->append("admin_msg", "$dest successfully uploaded");	  
	}
	else {
	  $this->smarty->append("admin_error", "possible upload attack, aborted!");
	}
      }
      else {
	$this->smarty->append("admin_error", "uploaded file has 0 bytes!");
      }
    }

    $this->smarty->assign("admin_mode", "admin_media");
    $this->admin_media();
  }

  function admin_extras() {}

  function admin_template() {
    $templates = array();
    if(is_dir($this->config["template_path"])) {
      $dh = opendir($this->config["template_path"]);
      while ( ( $template = readdir( $dh )) !== false) {
	if(is_dir($this->config["template_path"] . "/$template")
	  and $template != "shared" and $template != '.' and $template != '..') {
	  $nfofile = $this->config["template_path"] . "/$template/$template.nfo";

	  $nfo = array("version" => "unversioned", 
		       "description" => "", "author" => "unknown", 
		       "author_email" => "", "url" => "",
		       "icon" => "", "state" => "inactive",
		       "name" => $template
		       );

	  if (file_exists($nfofile)) {
	    $nfo = parse_config($nfofile);
	  }

	  if($this->config['template'] == $template) {
	    $nfo['state'] = "active";
	  }

	  if($nfo['screenshot']) {
	    $screenshot = $this->config["template_path"] . "/$template/" . $nfo['screenshot'];
	    if( file_exists($screenshot) and is_writable($this->config["template_path"] . "/$template/") ) {

	      $file = '___' . preg_replace("/[^a-z0-9A-Z\s\_\-\.\/\\\(\)]/", '', $nfo['screenshot']);
	      $thumb = $this->config["template_path"] . "/$template/" . $file;

	      if(! file_exists($thumb) ) {
		$this->make_thumb($screenshot, $thumb);
	      }
	      else {
		if(filemtime($screenshot) > filemtime($thumb)) {
		  /* original image has been modified, recreate the thumbnail */
		  $this->make_thumb($screenshot, $thumb);
		}
	      }

	      $nfo['thumbnail'] = $file;
	    }
	    else {
	      $nfo['screenshot'] = '';
	    }
	  }
	  $nfo['name'] = $template;
 	  $templates[$template] = $nfo;
	}
      }
    }
    $this->smarty->assign("templates", $templates);
  }

  function admin_template_changestate() {
    if(is_dir($this->config["template_path"] . "/$template/" . $this->input['template'])) {
      $configfile = $this->config['config_path'] . '/'. 'plosxom.conf';
      $content = implode('', file($configfile));
      $changed = preg_replace("/^template = .*$/m", "template = " . $this->input['template'], $content);
      if($changed != $content) {
	if(is_writable($configfile)) {
	  if($this->write($configfile, $changed)) {
	    $this->smarty->append("admin_msg", "template has been set to " . $this->input['template']);
	    $this->config['template'] = $this->input['template'];
	  }
	}
	else {
	  $this->smarty->append("admin_error", "plosxom.conf not writable!");
	}
      }
      else {
	$this->smarty->append("admin_error", "replacing template in plosxom.conf failed!");
      }
    }
    else {
      $this->smarty->append("admin_error", "template does not exist!");
    }
    $this->admin_template();
    $this->smarty->assign("admin_mode", "admin_template");
  }

  function admin_template_edit() {
    $files = array();
    $dh = opendir($this->config['template_path'] . '/' . $this->input['template']);
    if($dh) {
      while ( ( $F = readdir( $dh )) !== false) {
        if($F == "." or $F == "..") {
          continue;
        }
        if ( preg_match("/\.(tpl|css)$/", $F)) {
          $files[] = $F;
        }
      }
      closedir($dh);
      sort($files);
    }
    else {
      $this->smarty->append("admin_error", "template directory not readable!");
    }
    $this->smarty->assign("template", $this->input['template']);
    $this->smarty->assign("template_files", $files);
  }


  function admin_template_editfile() {
    $filename = $this->config['template_path'] . '/' . $this->input['template'] . '/' . $this->input['template_file'];
    if(is_readable($filename) and ereg('\.(tpl|css)$', $filename)) {
      $content = implode('', file($filename));
      $this->smarty->assign("template_content", $content);
      $this->smarty->assign("template_file", $this->input['template_file']);
    }
    else {
      $this->smarty->append("admin_error", "template file " . $this->input['template_file'] . " does not exist or is not readable!");
      $this->smarty->assign("admin_mode", "admin_template_edit");
      $this->admin_template_edit();
    }
    $this->smarty->assign("template", $this->input['template']);
  }

  function admin_template_savefile() {
    $filename = $this->config['template_path'] . '/' . $this->input['template'] . '/' . $this->input['template_file'];
    if($this->write($filename, $this->input['template_content'])) {
      $this->smarty->append("admin_msg", '"' . $this->input['template_file'] . '" written successfully.');
    }
    $this->smarty->assign("admin_mode", "admin_template_edit");
    $this->admin_template_edit();
    $this->smarty->assign("template", $this->input['template']);
  }


  function admin_template_install() {
    if(! is_writable($this->config["template_path"])) {
      $this->smarty->append("admin_error", "template path is not writable!");
      $this->admin_template();
      $this->smarty->assign("admin_mode", 'admin_template');
    }
  }

  function admin_template_upload() {
    $orig = basename($_FILES['template']['name']);
    $tmp  = $_FILES['template']['tmp_name'];
    $err  = $_FILES['template']['error'];
    $size = $_FILES['template']['size'];
    $info = '';
    $help = '';

    if($error) {
      $this->smarty->append("admin_error", $error);
    }
    elseif(! preg_match("/^[0-9a-z\_\-\.]*$/i", $orig)) {
      $this->smarty->append("admin_error", "Template archive names shall only consist of chars 0-9, a-z, A-Z, -, _, . !");
    }
    else {
      if(preg_match("/^(.*)\.zip$/i", $orig, $match)) {
	$template = $match[1];
	if($size > 0) {
	  $zipfile = $this->config['tmp_path'] . '/' . $template . '.zip';
	  if(move_uploaded_file($tmp, $zipfile)) {
	    $zip = new ZipArchive();
	    $zip->open($zipfile);
	    $tplfiles = array();
	    for ($pos = 0; $pos < $zip->numFiles; $pos++) {
	      $file = $zip->statIndex($pos);
	      if(preg_match("/^$template\//", $file['name'])) {
		$tplfiles[] = $file['name'];
	      }
	    }
	    if(! $tplfiles) {
	      $this->smarty->append("admin_error", "zip file contains either no files at all or files are not organized in a directory!");
	    }
	    else {
	      if($zip->extractTo($this->config['template_path'], $tplfiles)) {
		if(is_dir($this->config['template_path'] . '/' . $template)) {
		  foreach ($tplfiles as $tpl) {
		    if($tpl) {
		      $cfile = $this->config['template_path'] . '/' . $tpl;
		      $info .= "<br/>extracted $cfile";
		      if(is_dir($cfile)) {
			chmod($cfile, 0777);
		      }
		      else {
			chmod($cfile, 0666);
		      }
		    }
		  }
		  $this->smarty->append("admin_msg", "template has been installed.");
		}
		else {
		  $this->smarty->append("admin_error", "extraction of template $template failed!");
		}
	      }
	      else {
		$this->smarty->append("admin_error", "extraction of template $template failed!");
	      }
	    }
	    $this->unlink($zipfile);
	  }
	  else {
	    $this->smarty->append("admin_error", "possible upload attack, aborted!");
	  }
	}
	else {
	  $this->smarty->append("admin_error", "uploaded file has 0 bytes!");
	}
      }
      else {
	$this->smarty->append("admin_error", "ZIP file expected, got: $orig!");
      }
    }

    if($info) {
      $this->smarty->append("admin_info", $info);
    }

    $this->admin_template();
    $this->smarty->assign("admin_mode", 'admin_template');
  }


  function admin_template_delete() {
    if($this->input['template'] == $this->config['template']) {
      $this->smarty->append("admin_error", "Cannot remove $template, it is currently in use!");
    }
    else {
      $tpl = $this->config['template_path'] . '/' . $this->input['template'];
      if(is_dir($tpl) and is_writable($tpl)) {
	if($this->deldir($tpl)) {
	  $this->smarty->append("admin_msg", "Removed template directory $tpl");
	}
	else {
	  $this->smarty->append("admin_error", "Could not remove template directory $tpl!");
	}
      }
      else {
	$this->smarty->append("admin_error", "Directory $tpl does not exist or permission denied!");
      }
    }
    $this->admin_template();
    $this->smarty->assign("admin_mode", 'admin_template');
  }
 
  function deldir( $dir ){
    if( is_dir( $dir ) ){
      foreach( $this->scan_dir_all( $dir ) as $item ){
	if(! $this->deldir( $dir . "/" . $item )) {
	  return false;
	}
      }
      return $this->rmdir( $dir );
    }
    else {
      return $this->unlink( $dir );
    }
  }

  function scan_dir_all($dir) {
    $files  = array();
    if(is_dir($dir) and is_readable($dir)) {
      $handle = opendir($dir);
      while(($file = readdir($handle))!== false) {
	if( $file == '.' or $file == '..' ) {
          continue;
	}
	$files[] = $file;
      }
    }
    return $files;
  }

}
