<?

class plugin_admin_backup extends Plugin{
  var $options;

  function register() {
    $this->add_handler("admin_create_backup",  "plugin_admin_backup");
    $this->add_handler("admin_restore_backup", "plugin_admin_backup");
    $this->add_template("extra", "shared/extra_backup.tpl");
    $this->smarty->assign("plugin_admin_backup", true);
  }

  function zipFileErrMsg($errno) {
    // using constant name as a string to make this function PHP4 compatible
    $zipFileFunctionsErrors = array(
				    'ZIPARCHIVE::ER_MULTIDISK' => 'Multi-disk zip archives not supported.',
				    'ZIPARCHIVE::ER_RENAME' => 'Renaming temporary file failed.',
				    'ZIPARCHIVE::ER_CLOSE' => 'Closing zip archive failed', 
				    'ZIPARCHIVE::ER_SEEK' => 'Seek error',
				    'ZIPARCHIVE::ER_READ' => 'Read error',
				    'ZIPARCHIVE::ER_WRITE' => 'Write error',
				    'ZIPARCHIVE::ER_CRC' => 'CRC error',
				    'ZIPARCHIVE::ER_ZIPCLOSED' => 'Containing zip archive was closed',
				    'ZIPARCHIVE::ER_NOENT' => 'No such file.',
				    'ZIPARCHIVE::ER_EXISTS' => 'File already exists',
				    'ZIPARCHIVE::ER_OPEN' => 'Can\'t open file', 
				    'ZIPARCHIVE::ER_TMPOPEN' => 'Failure to create temporary file.', 
				    'ZIPARCHIVE::ER_ZLIB' => 'Zlib error',
				    'ZIPARCHIVE::ER_MEMORY' => 'Memory allocation failure', 
				    'ZIPARCHIVE::ER_CHANGED' => 'Entry has been changed',
				    'ZIPARCHIVE::ER_COMPNOTSUPP' => 'Compression method not supported.', 
				    'ZIPARCHIVE::ER_EOF' => 'Premature EOF',
				    'ZIPARCHIVE::ER_INVAL' => 'Invalid argument',
				    'ZIPARCHIVE::ER_NOZIP' => 'Not a zip archive',
				    'ZIPARCHIVE::ER_INTERNAL' => 'Internal error',
				    'ZIPARCHIVE::ER_INCONS' => 'Zip archive inconsistent', 
				    'ZIPARCHIVE::ER_REMOVE' => 'Can\'t remove file',
				    'ZIPARCHIVE::ER_DELETED' => 'Entry has been deleted',
				    );
    $errmsg = 'unknown';
    foreach ($zipFileFunctionsErrors as $constName => $errorMessage) {
      if (defined($constName) and constant($constName) === $errno) {
	return 'Zip File Function error: '.$errorMessage;
      }
    }
    return 'Zip File Function error: unknown';
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
    if($posts)  { $lists['data']   = $posts;  }
    if($pages)  { $lists['pages']  = $pages;  }
    if($images) { $lists['images'] = $images; }

    /* create archive */
    if( class_exists('ZipArchive') ) {
      $zip = new ZipArchive();
      $errno = $zip->open($archive, ZipArchive::CREATE | ZipArchive::OVERWRITE);
      if($errno !== true) {
	admin::message('errorgeneric', 'Failed to create: ' . $archive . ': ' . $this->zipFileErrMsg($errno));
	return;
      }
    
      $mtimelog = '';
      /* add the files and log mtimes */
      foreach ($lists as $type => $list) {
	foreach ($list as $entry) {
	  if(preg_match("/^plosxom-\d\d\d\d-\d\d-\d\d\.zip$/", $entry['name'])) { continue; }
	  if(is_readable( $entry['location'] )) {
	    if(! $zip->addFile($entry['location'], $type . '/' . $entry['name'])) {
	      admin::message('errorgeneric', 'failed to add ' . $entry['location']);
	    }
	    else {
	      $mtimelog .= $type . ';' . $type . '/' . $entry['name'] . ';' . $entry['location'] . ';' . $entry['mtime'] . "\n";
	      admin::message('infogeneric', 'Added ' . $type . '/' . $entry['name'] . ' to archive');
	    }
	  }
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
      foreach ($lists as $type => $list) {
	foreach ($list as $entry) {
	  if(preg_match("/^plosxom-\d\d\d\d-\d\d-\d\d\.zip$/", $entry['name'])) { continue; }
	  $mtimelog .= $type . ';' . $type . '/' . $entry['name'] . ';' . $entry['location'] . ';' . $entry['mtime'] . "\n";
	  chdir($entry['basedir']);
	  $ret = system("$cmd" . $entry['name'], &$code);
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
	    $files[] = $this->getfile($dir . '/' . $file . '/' . $subfile, $file . '/' . $subfile, $dir);
	  }
	}
	else {
	  if(is_readable($dir . '/' . $file)) {
	    $files[] = $this->getfile($dir . '/' . $file, $file, $dir);
	  }
	}
      }
      return $files;
    }
  }

  function getfile($physicalfile, $relativefile, $basedir) {
    $entry = array(
		   'name'     => $relativefile,
		   'file'     => basename($relativefile),
		   'dir'      => dirname($relativefile),
		   'location' => $physicalfile,
		   'mtime'    => filemtime($physicalfile),
		   'basedir'  => $basedir
		   );
    return $entry;
  }


  function admin_parse_mtimelog ($log) {
    $lines = split("\n", $log);
    $files = array();
    foreach ($lines as $line) {
      // post;testing/example.txt;/home/scip/www/data/testing/example.txt;1229217866
      /* type = post | page | image */
      list($type, $relative, $physical, $mtime) = split(";", $line);

      if($relative) {
	/* get the real relative location of file below $type directory */
	$subrel = preg_replace("/^" . $type . "\//", '', $relative);
	$files[$relative] = array(
				  'type'     => $type,
				  'relative' => $subrel,
				  'zippath'  => $relative,
				  'physical' => $physical,
				  'mtime'    => $mtime
				  );
      }
    }
    return $files;
  }


  function admin_restore_backup() {
    /* look in D/small_scripts/upload.php! */

    /* this is where we go afterwards */
    $this->smarty->assign("admin_mode", "admin_extras");

    $pconfig = parse_config('page.conf');
    $dirs = array(
		  'data'   => $this->config['data_path'],
		  'images' => $this->config['image_path'],
		  'pages'  => $pconfig['data']
		  );

    if(is_uploaded_file($_FILES['file']["tmp_name"])) {
      $orig = basename($_FILES['file']['name']);
      $tmp  = $_FILES['file']['tmp_name'];
      $err  = $_FILES['file']['error'];
      $size = $_FILES['file']['size'];

      if($error) {
	$this->message('errorgeneric', $error);
      }
      else {
	if(preg_match("/^(.*)\.zip$/", $orig)) {
	  if($size > 0) {
	    $zipfile = $this->config['tmp_path'] . '/' . $orig;
	    if(move_uploaded_file($tmp, $zipfile)) {
	      /* unpack the moved zipfile */
	      if( class_exists('NO-ZipArchive') ) {
		/* use ZipArchive class */
		$zip = new ZipArchive();
		$zip->open($zipfile);

		/* fetch mtimelog */
		$mtimelog = $zip->getStream("mtime.log");
		if(! $mtimelog) {
		  admin::message('errorgeneric', 'ZIP archive doesn\'t contain the file mtime.log!');
		  return;
		}
		$log = '';
		while (!feof($mtimelog)) {
		  $log .= fread($mtimelog, 2);
		}
		fclose($mtimelog);
		$files = $this->admin_parse_mtimelog($log);

		for ($pos = 0; $pos < $zip->numFiles; $pos++) {
		  $file = $zip->statIndex($pos);
		  if($file['name'] == 'mtime.log') { continue; }

		  $fd = $zip->getStream($file['name']);
		  if($fd) {
		    /* read file content */
		    $content = '';
		    while (!feof($fd)) {
		      $content .= fread($fd, 2);
		    }
		    fclose($fd);

		    $targetdir  = $dirs[$files[$file['name']]['type']];
		    $targetfile = $files[$file['name']]['relative'];
		    $mtime      = $files[$file['name']]['mtime'];

		    chdir($targetdir);
		    $category = dirname($targetfile);
		    if(!file_exists($targetdir . '/' . $category)){
		      mkdir($targetdir . '/' . $category, 0777, true);
		    }

		    $fdl = fopen($targetdir . '/' . $targetfile, 'w');
		    if( !$fdl ) {
		      admin::message('errorfileopen', $file['name']);
		      continue;
		    }
		    else {
		      if (! fwrite($fdl, $content)) {
			$this->message('errorfilesave', $file['name']);
			continue;
		      }
		      else {
			fclose($fdl);
			touch($targetdir . '/' . $targetfile, $mtime);
			admin::message('infogeneric', $file['name'] . ' extracted');
		      }
		    }
		  }
		  else {
		    $this->message('errorzipextract', $file['name'], $zipfile);
		    break;
		  }
		}
		admin::unlink($zipfile);
	      }
	      else {
		/* use /bin/unzip */

		/* extract mtime.log */
		$fd = popen('unzip -c ' . $zipfile . ' mtime.log', 'r');
		if(! $fd ) {
		  admin::message('errorgeneric', 'failed to execute unzip -c ' . $zipfile . ' mtime.log');
		}
		$content = '';
		while (!feof($fd)) {
		  $content .= fread($fd, 2);
		}
		fclose($fd);
		$files = $this->admin_parse_mtimelog($content);

		/* extract the files */
		foreach ($files as $file) {
		  $physicalfile = $dirs[$file['type']] . '/' . dirname($file['relative']) . '/' . basename($file['relative']);
		  $targetdir    = $dirs[$file['type']];
		  $extractdir   = $dirs[$file['type']] . '/' . dirname($file['relative']);
		  $targetfile   = $file['zippath'];
		  $mtime        = $file['mtime'];

		  chdir($targetdir);
		  $category = dirname($targetfile);
		  if(!file_exists($targetdir . '/' . $category)){
		    mkdir($targetdir . '/' . $category, 0777, true);
		  }

		  system('unzip -o -q -j ' . $zipfile . ' -d ' . $extractdir . ' ' . $targetfile);
		  if(file_exists($physicalfile)) {
		    touch($physicalfile, $mtime);
		    admin::message('infogeneric', $file['relative'] . ' extracted');
		  }
		  else {
		    admin::message('errorzipextract', $targetfile, $zipfile);
		  }
		}

		admin::unlink($zipfile);
	      }
	    }
	    else {
	      $this->message('erroruptrick');
	    }
	  }
	  else {
	    $this->message('erroruploadzero');
	  }
	}
	else {
	  $this->message('errorzipno');
	}
      }
    }
  }

}
?>