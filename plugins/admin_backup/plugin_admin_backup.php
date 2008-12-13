<?

class plugin_admin_backup extends Plugin{
  var $options;

  function register() {
    $this->add_handler("admin_create_backup",  "plugin_admin_backup");
    $this->add_handler("admin_restore_backup", "plugin_admin_backup");
    $this->add_template("extra", "shared/extra_backup.tpl");
    $this->smarty->assign("plugin_admin_backup", true);
  }


  function admin_create_backup() {
    $timestamp = date('Y-m-d');
    $dirname   = 'plosxom-' . $timestamp;
    $filename  = $dirname . '.zip';
    $archive   = $this->config['tmp_path'] . '/' . $dirname;

    /* fetch all postings */
    $posts = $this->getfiles($this->config['data_path']);

    /* pages */
    $pconfig = parse_config('page.conf');    
    $pages   = $this->getfiles($pconfig['data']);

    /* images */
    $images  = $this->getfiles($this->config['image_path']);

  }


  function getfiles($dir) {
    if(is_readable($dir)) {
      $dh = opendir($dir);
      $files = array();
      while ( $file = readdir($dh) ) {
	if($file == "." or $file == "..") { continue; }
	if(is_dir($dir . '/' . $file) and is_readable($dir . '/' . $file)) {
	  $subdh = opendir($dir . '/' . $file);
	  while ( $subfile = readdir($subdh) ) {
	    if($subfile == "." or $subfile == "..") { continue; }
	    $files[] = $this->getfile($dir . '/' . $file . '/' . $subfile, $file . '/' . $subfile);
	  }
	}
	else {
	  if(is_readable($dir . '/' . $file)) {
	    $files[] = $this->getfile($dir . '/' . $file, $file);
	  }
	}
      }
      return $files;
    }
  }

  function getfile($physicalfile, $relativefile) {
    $entry = array(
		   'name'     => $relativefile,
		   'file'     => basename($relativefile),
		   'dir'      => dirname($relativefile),
		   'location' => $physicalfile,
		   'mtime'    => filemtime($physicalfile)
		   );
    print "file mtime is $mtime<br>";
    return $entry;
  }


  function admin_restore_backup() {
    /* look in D/small_scripts/upload.php! */
    if(is_uploaded_file($_FILES["file"]["tmp_name"])) {

    }
  }

}
?>