<?
/*
 *
 *   $Id$
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

 *
 * based on phpblosxom by Robert Daeley <robert@celsius1414.com>
 * http://www.celsius1414.com/phposxom/. phpblosxom copyright
 * not included, because this is a complete rewrite, no code pieces
 * were re-used at all.
 *
 */

/* turn on error output, some installations of php
 * have it turned off, for some obscure reason
 */
ini_set("display_errors", "on");

/* load classes and config */
$config_path = dirname(__FILE__) . "/etc";
$lib_path    = dirname(__FILE__) . "/lib";

include($lib_path . "/Plosxom-functions.php");
include($lib_path . "/Plosxom-class.php");
include($lib_path . "/Registry-class.php");
include($lib_path . "/Plugin-class.php");

if(file_exists("install.php")) {
  print("Remove the file 'install.php' first!");
  exit;
}

$config = parse_config($config_path . "/plosxom.conf");
$config["config_path"] = $config_path;
$config["lib_path"]    = $lib_path;
$config["version"]     = 1.06;

/* load smarty template engine */
define('SMARTY_DIR', $config["lib_path"] . "/"); 
include(SMARTY_DIR . 'Smarty.class.php');

/* initialize the smarty engine */
$smarty = new Smarty;
$smarty->template_dir = $config["template_path"];
$smarty->config_dir   = $config["config_path"];
$smarty->compile_dir  = $config["tmp_path"];
$smarty->caching      = 0;

/* initialize plosxom */
$plosxom = new Plosxom($config, $smarty);

/* and go */
$plosxom->engine();


?>
