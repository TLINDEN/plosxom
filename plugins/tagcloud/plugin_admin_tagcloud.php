<?

class plugin_admin_tagcloud extends Plugin{
  var $page;
  var $pconfig;

  function register() {
    $this->add_handler("admin_tagcloud", "plugin_admin_tagcloud");
    $this->add_handler("admin_postsave", "plugin_admin_tagcloud");
    $this->add_template("extra", "shared/extra_tagcloud.tpl");
    $this->add_template("postsave", "shared/postsave_tagcloud.tpl");
    $this->smarty->assign("plugin_admin_tagcloud", true);
  }

  function admin_postsave() {
    if($this->input['rpcping']) {
      $this->admin_tagcloud(true);
    }
  }

  function  admin_tagcloud($return = false) {
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

    if($content) {
      $cloudfile = $this->config['config_path'] . '/tagcloud.conf';
      if($this->registry->plugins['admin']->write($cloudfile, $content)) {
	$oldinfo = $this->smarty->get_template_vars('admin_info');
	admin::message('infocfgsaved', 'tagcloud.conf');
      }
      chmod($cloudfile, 0666);
    }
    else {
      $oldinfo = $this->smarty->get_template_vars('admin_info');
      admin::message('infogeneric', 'no tags found in any posting');
    }

    if(! $return) {
      $this->smarty->assign("admin_mode", "admin_extras");
    }
  }

}

?>
