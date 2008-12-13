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
    $archive   = $this->config['tmp_path'] . '/' . $dirname . '.zip';

    /* this is where we go afterwards */
    $this->smarty->assign("admin_mode", "admin_extras");

    /* fetch all postings */
    $posts = $this->getfiles($this->config['data_path']);

    /* pages */
    $pconfig = parse_config('page.conf');    
    $pages   = $this->getfiles($pconfig['data']);

    /* images */
    $images  = $this->getfiles($this->config['image_path']);

    $lists = array();
    if($posts)  { $lists[] = $posts;  }
    if($pages)  { $lists[] = $pages;  }
    if($images) { $lists[] = $images; }

    /* create archive */
    if( class_exists('ZipArchive') ) {
      $zip = new ZipArchive();
      if ($zip->open($archive, ZIPARCHIVE::CREATE)!==TRUE) {
	admin::message('errorfileopen', $archive);
	return;
      }
    
      $mtimelog = '';
      /* add the files and log mtimes */
      foreach ($lists as $list) {
	foreach ($list as $entry) {
	  if(preg_match("/^plosxom-\d\d\d\d-\d\d-\d\d\.zip$/", $entry['name'])) { continue; }
	  $zip->addFile($entry['location'], $entry['name']);
	  $mtimelog .= $entry['name'] . ';' . $entry['location'] . ';' . $entry['mtime'] . "\n";
	  admin::message('infogeneric', 'Added ' . $entry['location'] . ' to archive');
	}
      }

      $zip->addFromString('mtime.log', $mtimelog);
      admin::message('infogeneric', 'Added mtime.log to archive');

      if($zip->close()) {
	if(is_writable($this->config['image_path'])) {
	  copy($archive, $this->config['image_path'] . '/' . $filename);
	  unlink($archive);
	  $this->smarty->assign('admin_backup_created', 1);
	  $this->smarty->assign('admin_backup_ziplink', $this->config['imgurl'] . '/' . $filename);
	  $this->smarty->assign('admin_backup_zipfile', $filename);
	}
	else {
	  $this->smarty->assign('admin_backup_created', 1);
	  $this->smarty->assign('admin_backup_zipfile', $archive);
	}
      }
      else {
	admin::message('errorfilesave', $archive);
      }
    }
    else {
      /* try it using system cmd zip */
      $cmd = "zip -q $archive ";
      $mtimelog = '';
      foreach ($lists as $list) {
	foreach ($list as $entry) {
	  if(preg_match("/^plosxom-\d\d\d\d-\d\d-\d\d\.zip$/", $entry['name'])) { continue; }
	  $mtimelog .= $entry['name'] . ';' . $entry['location'] . ';' . $entry['mtime'] . "\n";
	  $ret = system("$cmd" . $entry['location'], &$code);
	  if($ret != 0) {
	    admin::message('errorgeneric', "Could not execute: $cmd" . $entry['location'] . ": $code");
	    return;
	  }
	  admin::message('infogeneric', 'Added ' . $entry['location'] . ' to archive');
	}
      }

      $fd = fopen($this->config['tmp_path'] . '/mtime.log', 'w');
      if( !$fd) {
	admin::message('errorfileopen', $this->config['tmp_path'] . '/mtime.log');
	return;
      }
      else {
	if (! fwrite($fd, $mtimelog)) {
	  $this->message('errorfilesave', $this->config['tmp_path'] . '/mtime.log');
	  return;
	}
	else {
	  fclose($fd); 
	  system("zip -j -q $archive " . $this->config['tmp_path'] . '/mtime.log');
	  admin::message('infogeneric', 'Added mtime.log to archive');
	  unlink($this->config['tmp_path'] . '/mtime.log');
	}
      }

      if(file_exists($archive)) {
	admin::message('infogeneric', 'Archive ' . $archive . ' successfully written');
	if(is_writable($this->config['image_path'])) {
	  copy($archive, $this->config['image_path'] . '/' . $filename);
	  unlink($archive);
	  $this->smarty->assign('admin_backup_created', 1);
	  $this->smarty->assign('admin_backup_ziplink', $this->config['imgurl'] . '/' . $filename);
	  $this->smarty->assign('admin_backup_zipfile', $filename);
	}
	else {
	  $this->smarty->assign('admin_backup_created', 1);
	  $this->smarty->assign('admin_backup_zipfile', $archive);
	}
      }
      else {
	admin::message('errorgeneric',
		       'zip command and ZipArchive PHP extension not installed. See: ' .
		       '<a href="http://www.php.net/manual/en/book.zip.php">http://www.php.net/manual/en/book.zip.php</a>');
      }
    }
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
    return $entry;
  }


  function admin_restore_backup() {
    /* look in D/small_scripts/upload.php! */
    if(is_uploaded_file($_FILES["file"]["tmp_name"])) {

    }
  }

}
?>