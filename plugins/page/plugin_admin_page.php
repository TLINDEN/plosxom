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

  function admin_page() {}
}

?>
