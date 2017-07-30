<?

class tagcloud extends Plugin {

  function register() {
    $this->smarty->register_function("cloud", array(&$this, "cloud"));
    return true;
  }
  
  function cloud($params, &$smarty) {
    $tpl = '<li>%1(%2)</li>';
    $cfg = 'tags.conf';

    if( $params["config"]) {
      $cfg = $params["config"];
    }

    if( $params["template"] ) {
      $tpl = $params["template"];
    }
    
    $tags = parse_config($cfg);
    ksort($tags);

    $out = "";
    foreach ($tags as $tag => $nnumber) {
      $out .= str_replace(array("%1", "%2"), array($tag, $number), $tpl); 
    }

    return $out;
  }
  
}

?>
