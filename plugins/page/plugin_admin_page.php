<?

class plugin_admin_page extends Plugin{
  var $page;
  var $pconfig;

  function register() {
    $this->pconfig = parse_config("page.conf");
    if($this->pconfig["data"]) {
      $this->add_handler("admin_page", "plugin_admin_page");
      $this->smarty->assign("plugin_admin_page", true);
    }
  }

  function admin_page() {
    $pages = $this->fetchall();
    $this->smarty->assign('pages', $pages);
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
