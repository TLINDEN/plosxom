<?

class plugin_admin_tagcloud extends Plugin{
  var $page;
  var $pconfig;

  function register() {
    $this->add_handler("admin_tagcloud", "plugin_admin_tagcloud");
    $this->add_template("extra", "shared/extra_tagcloud.tpl");
    $this->smarty->assign("plugin_admin_tagcloud", true);
  }

  function  admin_tagcloud() {
    $handler = $this->registry->get_handler("hook_storage_fetchall");
    $posts = $this->registry->plugins[$handler]->hook_storage_fetchall(true);
    $tags = array();
    foreach ($posts as $post) {
      preg_match_all("/tag:([a-zA-Z0-9]+)/", $post['text'], $matches);
      foreach ($matches[1] as $tag) {
	$tags[$tag]++;
      }
    }

    $content = '';
    foreach ($tags as $tag => $count) {
      $content .= "$tag = $count\n";
    }

    $cloudfile = $this->config['config_path'] . '/tagcloud.conf';
    if($this->registry->plugins['admin']->write($cloudfile, $content)) {
      $this->smarty->assign("admin_msg", "tagcloud.conf has been successfully regenerated");
    }
    chmod($cloudfile, 0666);

    $this->smarty->assign("admin_mode", "admin_extras");
  }

}

?>
