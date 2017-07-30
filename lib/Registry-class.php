<?
/*
 *
 *   $Id: Registry-class.php 72 2007-06-20 10:46:31Z palidhar $
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


class Registry {
    var $plugins;
    var $handler;
    var $templates;
    
    function Registry() {
      $this->plugins   = array();
      $this->handler   = array();
      $this->templates = array();
    }
    
    function get_handlers($type, $onlyfirst = 0) {
      # used by the core to access installed handlers
      # which get called in the appropriate state
      #
      # if a handler is requested with $onlyfirst set to true
      # then this is a required handler, it must exist
      if( array_key_exists($type, $this->handler) ) {
        if( $onlyfirst ) {
          return $this->handler[$type][0];
        }
        else {
          return $this->handler[$type];
        }
      }
      else {
        # no handlers for this type installed
        if ( $onlyfirst ) {
          # we consider this an error
          die("Required plugin handler \"$type\" is not installed!");
        }
        else {
          return array();
        }
      }
    }

    function get_handler($type) {
      # return a single handler, in fact this is the first
      # one installed, other handlers of this type will
      # be ignored. If one wants to replace a required
      # handler, he has to de-install the plugin in question
      # and install his own one.
      return $this->get_handlers($type, 1);
    }
  
  function add_handler($type, $hdl) {
    # $type is a handler type
    # $hdl is a plugin name
    if(! array_key_exists($type, $this->handler) ) {
      # create array of handlers for this type
      $this->handler[$type] = array();
    }
    
    # install the handler plugin
    array_push($this->handler[$type], $hdl);
  }

  function add_template($type, $template) {
    $this->templates[$type][] = $template;
  }
}

?>