<?

class log extends Plugin {
  function register() {
    $cfg = parse_config("log.conf");
    $now = time();
    $source = array(
               "%path"      => $_SERVER["PATH_INFO"],
	       "%agent"     => $_SERVER["HTTP_USER_AGENT"],
	       "%remote"    => $_SERVER["REMOTE_ADDR"],
	       "%referrer"  => $_SERVER["HTTP_REFERER"],
	       "%request"   => $_SERVER["REQUEST_METHOD"],
	       "%uri"       => $_SERVER["REQUEST_URI"],
	       "%status"    => $_SERVER["REDIRECT_STATUS"],
	       "%date"      => date("r", $now),
	       "%timestamp" => $now
	          );
    if ($cfg["logdir"] and $cfg["format"] and $cfg["mode"]) {
      if (is_writable($cfg["logdir"])) {
	$logfile = "plosxom.log"; # continuous
	if($cfg["mode"] == "perday") {
          $ts = date("Y-m-d", $now);
	  $logfile = "plosxom-$ts.log";
	}
	elseif($cfg["mode"] == "perweek") {
	  $ts = date("Y-W");
	  $logfile = "plosxom-$ts.log";
	}
	elseif($cfg["mode"] == "permonth") {
	  $ts = date("Y-m");
	  $logfile = "plosxom-$ts.log";
        }
        $message = str_replace(array_keys($source), array_values($source), $cfg["format"]);

	$fd = fopen($cfg["logdir"] . "/$logfile", "a");
	fwrite($fd, $message . "\n");
	fclose($fd);
      }
    }
  }
  
}

?>
