<?
 #
 #   $Id$
 #
 #   This  file  is  part of the  Plosxom  web-based authoring tool.
 #
 #   By  accessing  this software,  Plosxom,  you are  duly informed
 #   of and  agree to be  bound  by the  conditions  described below
 #   in this notice:
 #
 #   This software product, Plosxom, is developed  by Pali Dhar  and
 #   copyrighted (C) 2007  by  Pali Dhar,  with all rights reserved.
 #
 #   There is  no charge for  Plosxom software. You can redistribute
 #   it and/or modify it under the terms of the Artistic License 2.0
 #   which is incorporated by reference herein.
 #
 #   PLOSXOM IS PROVIDED "AS IS" AND WITHOUT  ANY EXPRESS OR IMPLIED
 #   WARRANTIES,   INCLUDING,   WITHOUT  LIMITATION,   THE   IMPLIED 
 #   WARRANTIES  OF  MERCHANTIBILITY  AND  FITNESS  FOR A PARTICULAR
 #   PURPOSE.
 #
 #   You   should  have  received a  copy  of  the  artistic license
 #   along with Plosxom.  You may download a copy of the license at:
 #
 #     http://dev.perl.org/licenses/artistic.html
 #
 #   Or contact:
 #
 #    "Pali Dhar" <nobrain@bk.ru>
 #

 #
 # this is the standard plugin of plosxom.
 # it is required for most actions, so don't de-install it!
 #


class standard extends Plugin {
  var $category;
  var $archive;
  var $archivelist;
  var $archivedates;
  var $archivestamp;
  var $files;

  function register() {
    $this->add_handler("hook_storage_fetchall", "standard");
    $this->add_handler("hook_storage_fetch", "standard");
    $this->add_handler("hook_send_header", "standard");
    $this->add_handler("hook_url_filter", "standard");
    $this->files = array();
    $this->archivedates = array();
  }

  function hook_send_header() {
    if($this->posts[0]) {
      header("Last-modified: " . date('r', $this->posts[0]["mtime"]));
    }
  }

  function hook_url_filter($path) {
    #
    # we look for category and archive
    if(preg_match("/^\/category\/([a-zA-Z0-9\_\-]*)$/", $path, $match)) {
      $this->category = $match[1];
      $this->smarty->assign("category", $this->category);
      return true;
    }
    if(preg_match("/^\/category\/([a-zA-Z0-9\_\-]*)\/past\/(\d*)$/", $path, $match)) {
      $this->category = $match[1];
      $this->smarty->assign("category", $this->category);
      $this->input["past"] = $match[2];
      return true;
    }
    else if(preg_match("/^\/archive$/", $path)) {
      $this->archivelist = 1;
      return true;
    }
    else if(preg_match("/^\/archive\/(\d{4})(\d{2})(\d{2})$/", $path, $match)) {
      $this->archtime($match[1], $match[2], $match[3]);
      $this->smarty->assign("archive", $this->archive);
      $this->smarty->assign("archivestamp", $this->archivestamp);
      return true;
    }
    else if(preg_match("/^\/archive\/(\d{4})(\d{2})(\d{2})\/past\/(\d*)$/", $path, $match)) {
      $this->archtime($match[1], $match[2], $match[3]);
      $this->smarty->assign("archive", $this->archive);
      $this->smarty->assign("archivestamp", $this->archivestamp);
      $this->input["past"] = $match[4];
      return true;
    }
    else {
      return false;
    }
  }

  function archtime ($year, $mon, $day) {
    $this->archive = mktime(0, 0, 0, $mon, $day, $year);
    $this->archivestamp = $year . $mon . $day;
  }

  function fetch_dir($dir) {
    # traverse a subirectory, aka category
    $dh = opendir($this->config["data_path"] . "/" . $dir);
    while ( $file = readdir($dh) ) {
      if($file != "." and $file != "..") {
	$this->fetch_file($file, $dir);
      }
    }
  }


  function fetch_file($file, $dir="") {
    # fetch info about file (not the content of, this
    # will be done later, after sorting of it)
    $entry = getfile($this->config["data_path"], $file, $dir, false);
    if($entry) {
      if( $this->archive ) {
        if( $entry["htime"] == $this->archivestamp ) {
          # add it to the array, we'll sort it recursively later
          $this->files[] = $entry;
	}
      }
      else if($this->archivelist) {
        # we store the unix timestamp of 00:00:00 of the posting date
        $this->archivedates[mktime(0, 0, 0, date("m", $entry["mtime"]),
	                    date("d", $entry["mtime"]), date("Y", $entry["mtime"]))] = 1;
      }
      else {
        $this->files[] = $entry;
      }
    }
  }

  function hook_storage_fetch($category, $id) {
    #
    # fetch a single posting
    $post = getfile($this->config["data_path"], $id . '.txt', $category);
    return ($post);
  }

  function hook_storage_fetchall() {
    #
    # traverse directory tree and read all files therein
    # matching certain criteria (category, archive, none)
    # returns an array ready for smarty production
    if(file_exists($this->config["data_path"])) {
      $dh = opendir($this->config["data_path"]);
      while ( $file = readdir($dh) ) {
	if($file == "." or $file == "..") { continue; }
	if(is_dir($this->config["data_path"] . "/" . $file) and is_readable($this->config["data_path"] . "/" . $file)) {
	  if($this->category) {
	    # category filter in use
	    if( $this->category == $file ) {
	      $this->fetch_dir($file);
	    }
	  }
	  else {
	    # no filter in use, fetch anyway
	    $this->fetch_dir($file);
	  }
	}
	else {
	  $this->fetch_file($file);
	}
      }

      if($this->archivelist) {
        $dates = array();
	krsort($this->archivedates);
        foreach ($this->archivedates as $date => $value) {
	  $dates[] = $date;
        }
	$this->smarty->assign("archivelist", 1);
	$this->smarty->assign("archivedates", $dates);

	return array();
      }
      else {
        # so reverse sort the files
        $sort = array();
        foreach ($this->files as $pos => $entry) {
          $sort[$pos] = $entry["mtime"];
        }
        array_multisort($sort, SORT_DESC, $this->files);

        # now look if there is a content filter registered
        # if yes, then let it now filter the content
        if($this->filter_plugin and !$this->category and !$this->archivelist and !$this->archive) {
          return $this->filter_plugin->hook_content_filter($this->files);
        }

        $posts = array();
        $lastdate = 0;
	$numfiles = count($this->files);
	$this->input["numfiles"] = $numfiles;
	$maxfiles = $this->input["past"] + $this->config["postings"];
        for($pos = $this->input["past"]; $pos < $maxfiles; $pos++) {
          if(array_key_exists($pos, $this->files)) {
            $entry = getfile($this->config["data_path"], $this->files[$pos]["file"], $this->files[$pos]["category"]);
	    if($entry) {
	      $this->files[$pos] = $entry;
	    }
	    else {
	      continue;
	    }
	    $blogdate = mktime(0, 0, 0,    date("m", $this->files[$pos]["mtime"]),
					   date("d", $this->files[$pos]["mtime"]),
					   date("Y", $this->files[$pos]["mtime"]));
	   if($blogdate != $lastdate) {
	      # only save blogdate, if it has changed prior to previous posts
	      $this->files[$pos]["blogdate"] = $blogdate;
	      $lastdate = $blogdate;
	    }

	    $posts[] = $this->files[$pos];
	  }
	  else {
	    break;
	  }
        }
        return $posts;
      }
    }
    else {
      report_error("Data directory " . $this->config["data_path"] . " does not exists or is not readable!");
    }
  }


}


function getfile($datadir, $file, $dir="", $read=true) {
  #
  # actual open and read the file content and
  # place it into an array
  # if $read == true(default), the file content will be read.
  # set it to true to save runtime if you only need file attributes
  $filename = $datadir . "/" . $dir . "/" . $file;
  $entry = array();
  if(is_readable($filename) and ereg('\.txt$', $file)) {
    # dont stat() the file if this has already been done
    $mtime         = filemtime($filename);
    $human_mtime   = date("Ymd", $mtime);
    $id            = preg_replace("/\.txt$/", "", $file);
    $entry         = array(
                           "filename" => $filename,
		           "mtime"    => $mtime,
		           "htime"    => $human_mtime,
		           "category" => $dir,
		           "file"     => $file,
		           "id"       => $id,
    );

    if($read) {
       # also read the file content
       # circumvent reading file content twice
       $lines          = file($filename);
       $entry["title"] = trim(array_shift($lines));
       $entry["text"]  = paragraph(implode('', $lines));
    }

    return $entry;
  }
  else {
    #print "$datadir/$dir/$file.txt is not readable\n";
    report_error("$datadir/$dir/$file.txt is not readable");
    return null;
  }
}

function paragraph(&$text) {
  $text = preg_replace("/(\r\n\r\n|\n\n)/", "</p><p class=\"blogparagraph\">", $text);
  return '<p class="blogparagraph">' . $text . '</p>';
}
