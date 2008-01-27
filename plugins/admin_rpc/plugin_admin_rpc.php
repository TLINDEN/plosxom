<?

class plugin_admin_rpc extends Plugin{
  var $options;

  function register() {
    $this->add_handler("admin_rpcping", "plugin_admin_rpc");
    $this->add_template("extra", "shared/extra_rpc.tpl");
    $this->smarty->assign("plugin_admin_rpcping", true);
    $this->options = parse_config("rpcsites.conf");
  }

  function admin_rpcping() {
    include_once($this->config["lib_path"] . "/IXR_Library.inc.php");

    $this->smarty->assign("admin_mode", "admin_extras");

    if($this->options['rpcsite']) {
      if(! is_array($this->options['rpcsite']) ) {
	$this->options['rpcsite'] = array($this->options['rpcsite']);
      }
    }
    else {
      $this->smarty->assign("admin_error", "no rpc sites defined in rpcsites.conf!");
      return;
    }

    $info = 'Result:<br/>';
    foreach ( $this->options['rpcsite'] as $url ) {
      $client = new IXR_Client($url);
      $client->query('weblogUpdates.ping', $this->config['blog_name'] . ' - ' . $this->config['blog_title'], $this->config['whoami']);
      $reponse = $client->getResponse();
      if($response[0]) {
	$info .= "RPC Ping $url response error: <i>" . $response[1] . "</i><br/>";
      }
      else {
	$info .= "RPC Ping $url successfull.<br/>";
      }
    }
    $this->smarty->assign("admin_msg", $info);
  }

}
?>