<?

function parse_config($file) {
  global $config_path;
  if(! ereg("\/", $file)) {
    // filename contains no path parts at all
    $file = $config_path . "/$file";
  }
  elseif( ereg("^\/", $file)) {
    // absolute file path, keep it untouched
    ;
  }
  elseif( ereg(".+\/", $file) ) {
    /* filename contains path parts but is relative
     * go one level up
     */
    $file = $config_path . "/../$file";
  }

  if (file_exists($file)) {
    $config = array();
    foreach (file($file) as $line) {
      if(! preg_match("/\s*#/", $line) or preg_match("/^\s*$/", $line)) {
        # ignore comments and empty lines
        $line = preg_replace("/#.+?$/", "", $line);  # remove trailing comment, if any
	$line = preg_replace("/\r/", "", $line);     # remove line-feed
        if(preg_match("/^(.+?)\s*=\s*(.*)$/", $line, $match)) {
	  # option = value
	  $option = $match[1];
	  $value  = $match[2];
	  if(array_key_exists($option, $config)) {
	    if(! is_array($config[$option])) {
	      // mage it an array
	      $config[$option] = array($config[$option]);
	    }
	    $config[$option][] = $value;
	  }
	  else {
	    $config[$option] = $value;
	  }
	}
      }
    }
    return $config;
  }
  else {
    die("Configfile \"$file\" does not exist or is not readable!");
  }
}

?>