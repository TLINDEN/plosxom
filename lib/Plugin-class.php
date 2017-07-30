<?
/*
 *
 *   $Id: Plugin-class.php 72 2007-06-20 10:46:31Z palidhar $
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

class Plugin {
  #
  # exists as a base register to inherited by plugins.
  # each plugin must inherit from it and register its
  # handlers.

  var $handler;
  var $registry;
  var $filter_plugin;
  var $template;
  var $config;
  var $input;
  var $smarty;
  var $posts;

  function Plugin (&$H, &$C, &$I, &$T, &$F, &$S, &$P, &$R) {
    $this->handler       = &$H;
    $this->config        = &$C;
    $this->input         = &$I;
    $this->template      = &$T;
    $this->filter_plugin = &$F;
    $this->smarty        = &$S;
    $this->posts         = &$P;
    $this->registry      = &$R;
  }

  function get_handler($type) {
    return $this->registry->get_handler($type);
  }

  function get_handlers($type, $onlyfirst = 0) {
    return $this->registry->get_handlers($type, $onlyfirst);
  }

  function add_handler($type, $hdl) {
    $this->registry->add_handler($type, $hdl);
  }

  function add_template($type, $template) {
    $this->registry->add_template($type, $template);
  }

  function get_templates($type) {
    if(array_key_exists($type, $this->registry->templates)) {
      return $this->registry->templates[$type];
    }
    else {
      return array();
    }
  }

  function replace_template($tpl) {
    $this->template = $tpl;
  }
}


?>