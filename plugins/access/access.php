<?

class access extends Plugin {
  function register() {
    $me = "http://";
    if($_SERVER["HTTPS"]) {
	$me = "https://";
    }
    $me .= $_SERVER["HTTP_HOST"];
    if($_SERVER["HTTP_PORT"]) {
	$me .= ":" . $_SERVER["HTTP_PORT"];
    }
    $me .= $_SERVER["REQUEST_URI"];
    
    $cfg = parse_config("access.conf");
    if ($cfg["redirect"]) {
      if(array_key_exists($_SERVER["REMOTE_ADDR"], $cfg) and $me != $cfg["redirect"]) {
        # access denied
	header("Location: " . $cfg["redirect"]);
	exit();
      }
    }
  }
}

?>
