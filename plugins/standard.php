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
 #   This software product, Plosxom,  is developed by  Thomas Linden
 #   and   copyrighted  (C) 2007-2007   by  Thomas Linden,  with all
 #   rights reserved.
 #
 #   There is  no charge for  Plosxom software. You can redistribute
 #   it and/or modify it under  the terms of the  GNU General Public
 #   License, which is incorporated by reference herein.
 #
 #   Plosxom is distributed WITHOUT ANY WARRANTY,IMPLIED OR EXPRESS,
 #   OF  MERCHANTABILITY OR FITNESS FOR A PARTICULAR PURPOSE or that
 #   the  use of it will not infringe on any third party's intellec-
 #   tual property rights.
 #
 #   You  should  have  received a  copy  of  the GNU General Public
 #   License along with Plosxom.  Copies  can also be obtained from:
 #
 #     http://www.gnu.org/copyleft/gpl.html
 #
 #   or by writing to:
 #
 #     Free Software Foundation, Inc.
 #     59 Temple Place, Suite 330
 #     Boston, MA 02111-1307
 #     USA
 #
 #   Or contact:
 #
 #    "Thomas Linden" <tom@daemon.de>
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
    header('Content-type: text/html');
    # if $this->lastmodified, had be set in fetchall()
    # header("Last-modified: " . date('r', $usedates[0]));
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
      $this->archive = mktime(0, 0, 0, $match[2], $match[3], $match[1]);
      $this->archivestamp = $match[1] . $match[2] . $match[3];
      $this->smarty->assign("archive", $this->archive);
      $this->smarty->assign("archivestamp", $this->archivestamp);
      return true;
    }
    else if(preg_match("/^\/archive\/(\d{4})(\d{2})(\d{2})\/past\/(\d*)$/", $path, $match)) {
      $this->archive = mktime(0, 0, 0, $match[2], $match[3], $match[1]);
      $this->archivestamp = $match[1] . $match[2] . $match[3];
      $this->smarty->assign("archive", $this->archive);
      $this->smarty->assign("archivestamp", $this->archivestamp);
      $this->input["past"] = $match[4];
      return true;
    }
    else {
      return false;
    }
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
    $filename = $this->config["data_path"] . "/" . $dir . "/" . $file;

    if(is_readable($filename) and ereg('\.txt$', $file)) {
      $mtime = filemtime($filename);
      $human_mtime = date("Ymd", $mtime);
      $id = preg_replace("/\.txt$/", "", $file);
      $entry = array("filename" => $filename, "mtime" => $mtime, "category" => $dir, "file" => $file, "id" => $id);
      if( $this->archive ) {
        if( $human_mtime == $this->archivestamp ) {
          # add it to the array, we'll sort it recursively later
          $this->files[] = $entry;
	}
      }
      else if($this->archivelist) {
        # we store the unix timestamp of 00:00:00 of the posting date
        $this->archivedates[mktime(0, 0, 0, date("m", $mtime), date("d", $mtime), date("Y", $mtime))] = 1;
      }
      else {
        $this->files[] = $entry;
      }
    }
  }

  function hook_storage_fetch($category, $id) {
    $file = $id . ".txt";
    $this->fetch_file($file, $category);
    $post          = $this->files[0];
    $lines         = file($post["filename"]);
    $post["title"] = trim(array_shift($lines));
    $post["text"]  = $this->paragraph(implode('', $lines));
    return $post;
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
          return $this->filter_plugin->hook_content_filter();
        }

        $posts = array();
        $lastdate = 0;
	$numfiles = count($this->files);
	$this->input["numfiles"] = $numfiles;
	$maxfiles = $this->input["past"] + $this->config["postings"];
        for($pos = $this->input["past"]; $pos < $maxfiles; $pos++) {
          if(array_key_exists($pos, $this->files)) {
	    $lines                         = file($this->files[$pos]["filename"]);
	    $this->files[$pos]["title"]    = trim(array_shift($lines));
	    $this->files[$pos]["text"]     = $this->paragraph(implode('', $lines));

	    $blogdate = mktime(0, 0, 0, date("m", $this->files[$pos]["mtime"]), date("d", $this->files[$pos]["mtime"]), date("Y", $this->files[$pos]["mtime"]));
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

  function paragraph(&$text) {
    $text = preg_replace("/(\r\n\r\n|\n\n)/", "</p><p class=\"blogparagraph\">", $text);
    return '<p class="blogparagraph">' . $text . '</p>';
  }
}

