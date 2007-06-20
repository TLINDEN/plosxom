<?

class links extends Plugin {
  var $links;
  function register() {
    $this->smarty->register_function("links", array(&$this, "getlinks"));
  }
  
  function getlinks($params, &$smarty) {
    $tpl = '<li><a href="%1">%2</a></li>';
    $cfg = 'links.conf';

    if( $params["config"]) {
      $cfg = $params["config"];
    }

    if( $params["template"] ) {
      $tpl = $params["template"];
    }
    
    $links = parse_config($cfg);
    ksort($links);

    $out = "";
    foreach ($links as $name => $link) {
      $out .= str_replace(array("%1", "%2"), array($link, $name), $tpl); 
    }

    return $out;
  }
  
}

?>
