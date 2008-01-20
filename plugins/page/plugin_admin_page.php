<?

class plugin_admin_page extends Plugin{
  var $page;
  var $pconfig;

  function register() {
    $this->pconfig = parse_config("page.conf");
    if($this->pconfig["data"]) {
      $this->add_handler("admin_page",         "plugin_admin_page");
      $this->add_handler("admin_page_edit",    "plugin_admin_page");
      $this->add_handler("admin_page_save",    "plugin_admin_page");
      $this->add_handler("admin_page_delete", "plugin_admin_page");
      $this->smarty->assign("plugin_admin_page", true);
    }
  }

  function admin_page() {
    $pages = $this->fetchall();
    $this->smarty->assign('pages', $pages);
  }

  function admin_page_edit() {
    if ($this->input['id']) {
      $page = standard::getfile($this->pconfig["data"], $this->input['id'] . '.txt');
      if($page) {
	  $this->smarty->assign("page", $page);
      }
      else {
	  $this->smarty->assign("admin_error", $this->input['id'] . " does not exist or permission denied!");
	  $this->smarty->assign("admin_mode", "admin_page");
      }
    }
    $this->admin_page();
  }

  function admin_page_save() {
      $base        = $this->pconfig["data"];
      $file        = $this->input['id'];
      $content     = $this->input['title'] . "\n\n" . $this->input['content'] . "\n";

      $this->smarty->assign("admin_mode", "admin_page");

      if(! $file ) {
	  $file = preg_replace("/[^a-z0-9A-Z\s\_\-\.]/", "", $this->input['title']);
	  $create = true;
      }

      $file = preg_replace("/[\s\-_\/\\\(\)]+/", "_", $file);
      if (! preg_match("/\.txt$/", $file)) {
	  $file .= ".txt";
      }

      if(! file_exists($file) && ! is_writable($base) ) {
	  $this->smarty->assign("admin_error", "data directory is not writable!");
      }
      elseif( ! is_writable($base . '/' . $file) && ! $create) {
	  $this->smarty->assign("admin_error", "$file is not writable!");
      }
      else {
	  if($this->registry->plugins['admin']->write($base . '/' . $file, $content)) {
	      $this->smarty->assign("admin_msg", '"' . $this->input['title'] . '" written successfully.');
	  }
      }
      $this->admin_page();
  }

  function admin_page_delete() {
      $file = $this->pconfig["data"] . '/' . $this->input['id'] . '.txt';
      if( $this->registry->plugins['admin']->unlink($file) ) {
	  $this->smarty->assign("admin_msg", $this->input['id'] . " removed successfully.");
      }
    # else: error stored in unlink()
      $this->smarty->assign("admin_mode", "admin_page");
      $this->admin_page();
  }

  function fetchall() {
    $pages = array();
    if(file_exists($this->pconfig["data"]) and is_dir($this->pconfig["data"])) {
      $dh = opendir($this->pconfig["data"]);
      while ( $file = readdir($dh) ) {
        if($file == "." or $file == "..") { continue; }
	if(is_readable($this->pconfig["data"] . "/" . $file)) {
          $entry = standard::getfile($this->pconfig["data"], $file, "", true);
	  $pages[] = $entry;
	}
      }
      $sort = array();
      foreach ($pages as $pos => $entry) {
        $sort[$pos] = $entry["mtime"];
      }
      array_multisort($sort, SORT_DESC, $pages);
    }
    else {
      $this->smarty->assign("admin_error", "data path for static pages does not exist");
    }
    return $pages;
  }
}

?>
