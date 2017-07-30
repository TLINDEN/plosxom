<?
/*
 *
 *   $Id: Plosxom-functions.php 72 2007-06-20 10:46:31Z palidhar $
 *
 *   This  file  is  part of the  Plosxom  web-based authoring tool.
 *
 *   By  accessing  this software,  Plosxom,  you are  duly informed
 *   of and  agree to be  bound  by the  conditions  described below
 *   in this notice:
 *
 *   This software product, Plosxom, is developed  by Pali Dhar  and
 *   copyrighted (C) 2007  by  Pali Dhar,  with all rights reserved.
 *
 *   There is  no charge for  Plosxom software. You can redistribute
 *   it and/or modify it under the terms of the Artistic License 2.0
 *   which is incorporated by reference herein.
 *
 *   PLOSXOM IS PROVIDED "AS IS" AND WITHOUT  ANY EXPRESS OR IMPLIED
 *   WARRANTIES,   INCLUDING,   WITHOUT  LIMITATION,   THE   IMPLIED 
 *   WARRANTIES  OF  MERCHANTIBILITY  AND  FITNESS  FOR A PARTICULAR
 *   PURPOSE.
 *
 *   You   should  have  received a  copy  of  the  artistic license
 *   along with Plosxom.  You may download a copy of the license at:
 *
 *     http://dev.perl.org/licenses/artistic.html
 *
 *   Or contact:
 *
 *    "Pali Dhar" <nobrain@bk.ru>
 *
 */

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



function report_error($message) {
  # for now we just throw uncritical error messages to stderr
  # critical errors are handled by exceptions and halt the program
  global $stderr;
  if(! $stderr) {
    $stderr = fopen('php://stderr', 'w');
  }
  fwrite($stderr, "$message\n");
}

?>