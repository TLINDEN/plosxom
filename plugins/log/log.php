<?
 #
 #   $Id$
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

/*

To install:

1. copy log.php to your plugins directory.

2. copy log.conf to  your etc directory and configure it.
   the config contains hints what can be changed. Most important
   is "logdir", see below

3. create the log directory (configured as "logdir" in log.conf,
   make sure, the webserver has write access. If your user/group
   membership differs from weberserver, add the setguid bit, eg:

   % chmod 2777 logs/

   This way, at least the group ownerships of files created there
   in will be preserved so you can still maintain the logs.

*/

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
