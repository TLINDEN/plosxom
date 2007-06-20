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
 # based on phpblosxom by Robert Daeley <robert@celsius1414.com>
 # http://www.celsius1414.com/phposxom/. phpblosxom copyright
 # not included, because this is a complete rewrite, no code pieces
 # were re-used at all.
 #

# turn on error output, some installations of php
# have it turned off, for some obscure reason
ini_set("display_errors", "on");

# load configuration
$stderr = "";
$config_path = dirname($_SERVER["SCRIPT_FILENAME"]) . "/etc";
$config = parse_config($config_path . "/plosxom.conf");
$config["config_path"] = $config_path;

# load smarty template engine
define('SMARTY_DIR', $config["lib_path"] . "/"); 
include(SMARTY_DIR . 'Smarty.class.php');

# initialize the smarty engine
$smarty = new Smarty;
$smarty->template_dir = $config["template_path"];
$smarty->config_dir   = "$pwd/etc";
$smarty->compile_dir  = $config["tmp_path"];

# initialize plosxom
$plosxom = new Plosxom($config, $smarty);

# and go
$plosxom->engine();






#################### classes ##################

class Plosxom {

  var $config, $smarty, $plugins, $handler, $template, $filter_plugin;
  var $posting, $category, $input, $posts;

  function Plosxom(&$conf, &$smart) {
    $this->config = &$conf;
    $this->smarty = &$smart;
    
    $this->handler = array();
    $this->plugins = array();
    $this->input   = array("past" => 0);

    # load available plugins
    $this->load_plugins();

    # initialize runtime variables
    $this->init_runtime();

    # parse input coming from GET or POST
    $this->parse_input();
  }

  function load_plugins() {
    if ( file_exists($this->config["plugin_path"]) ) {
      $plugin_dh = opendir($this->config["plugin_path"]);
      while ( ($plugin = readdir( $plugin_dh )) !== false) {
        if ( preg_match( '/^(.+?)\.php$/', $plugin, $match ) ) {
	  # ok, try to load the plugin
	  include_once($this->config["plugin_path"] . "/" . $plugin);
	  # we got here, so try to register the plugin
	    $plugin_name = $match[1];
	    $this->plugins[$plugin_name] = new $plugin_name     (
	                                   $this->handler,
					   $this->config,
					   $this->input,
					   $this->template,
					   $this->filter_plugin,
					   $this->smarty,
					   $this->posts ); 
            $this->plugins[$plugin_name]->register();
	}
      }
    }
    else {
      die("Plugin directory " .  $this->config["plugin_path"] .  " does not exist or is not readable!");
    }
  }

  function parse_input() {
    # we let plugins parse the url input
    $path = $_SERVER['PATH_INFO'];

    if ( ereg('^[a-zA-Z0-9\/\_\-\.\;\:]*$', $path) ) {
      $gotfilter = 0;
      foreach ($this->get_handlers("hook_url_filter") as $handler) {
        if ( $this->plugins[$handler]->hook_url_filter($path) ) {
	  # add the first matching url_filter plugin as filter
	  # as only one filter can be in effect in the same time,
	  # we skip the next installed handlers for this hook
	  # plugins define this themselfes! # $this->filter_plugin = $this->plugins[$handler];
	  $gotfilter = 1;
	  break;
	}
      }

      if(! $gotfilter) {
        # no filters matched, consider the entire uri as posting, if any
        # optionally including $past variable
	$items   = explode("/", trim($path, "/") );
        $entries = count($items);
	if ( $entries == 1 ) {
          list($this->posting) = $items;
        }
	else if( $entries == 2 ) {
          if( $items[0] == "past" ) {
	    $this->input["past"] = $items[1];
	  }
	  else {
	    list($this->category, $this->posting) = $items;
	  }
        }
      }
    }
  }

  function send_headers() {
    # send http headers, if any
    foreach ($this->get_handlers("hook_send_header") as $handler) {
      $this->plugins[$handler]->hook_send_header($filter, $params);
    }

    # finaly send the type of content, may have been modified by some plugin
    header("Content-Type: " . $this->config["contenttype"]);
  }

  function init_runtime() {
    # kinda global stuff has to be defined here
    
    # set default content type, there can be only one
    # so if a plugin wants its own content type it has
    # to overwrite it
    $this->config["contenttype"] = 'text/html';
  }

  function engine() {
    # go rendering

    # get list of postings or set single posting
    $posts = array();
    $post  = array();

    if ( $this->posting ) {
      # single posting, fetch it
      $handler = $this->get_handler("hook_storage_fetch");
      $post    = $this->plugins[$handler]->hook_storage_fetch($this->category, $this->posting);
      $this->smarty->assign('singleposting', 1);
    }
    else {
      # fetch all matching postings
      $handler = $this->get_handler("hook_storage_fetchall");
      $posts   = $this->plugins[$handler]->hook_storage_fetchall();
    }

    # manipulate postings, if any
    if($posts) {
      foreach ($this->get_handlers("hook_content") as $handler) {
        foreach ($posts as $pos => $entry) {
          $posts[$pos]["text"] = $this->plugins[$handler]->hook_content($entry["text"]);
        }
        if ( $this->posting ) {
          $post["text"] = $this->plugins[$handler]->hook_content($post["text"]);
        }
      }
      if($this->input["past"]) {
          if($this->input["past"] > $this->config["postings"]) {
            $newer = $this->input["past"] - $this->config["postings"];
          }
          else {
            $newer = "null";
          }
          $this->smarty->assign('newer', $newer);
 
          $this->smarty->assign('past', $this->input["past"] + $this->config["postings"]);
        }
      $this->smarty->assign('posts', $posts);
      $this->smarty->assign('lastmodified', $posts[0]["mtime"]);
      $this->posts = $posts;
    }

    if ( $this->posting ) {
      foreach ($this->get_handlers("hook_content") as $handler) {
	$post["text"] = $this->plugins[$handler]->hook_content($post["text"]);
      }
      $this->smarty->assign('post', $post);
      $this->smarty->assign('lastmodified', $post["mtime"]);
      $this->posts = array($post);
    }

    $this->smarty->assign('config', $this->config);
    $this->smarty->assign('lang', $this->config["lang"]);

    # $this->smarty->debugging = true;

    $tpl = "index.tpl";
    if($this->template) {
      # some plugin has overwritten the default template filename
      $tpl = $this->template;
    }

    $this->send_headers();
    $this->smarty->display($this->config["template"] . "/" . $tpl);
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
}


class Plugin {
  #
  # exists as a base register to inherited by plugins.
  # each plugin must inherit from it and register its
  # handlers.

  var $handler;
  var $filter_plugin;
  var $template;
  var $config;
  var $input;
  var $smarty;
  var $posts;

  function Plugin (&$H, &$C, &$I, &$T, &$F, &$S, &$P) {
    $this->handler       = &$H;
    $this->config        = &$C;
    $this->input         = &$I;
    $this->template      = &$T;
    $this->filter_plugin = &$F;
    $this->smarty        = &$S;
    $this->posts         = &$P;
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

  function replace_template($tpl) {
    $this->template = $tpl;
  }
}


function parse_config($file) {
  global $config_path;
  if(! ereg("^\/", $file)) {
    # add config dir
    $file = $config_path . "/$file";
  }
  if (file_exists($file)) {
    $config = array();
    foreach (file($file) as $line) {
      if(! preg_match("/\s*#/", $line) or preg_match("/^\s*$/", $line)) {
        # ignore comments and empty lines
        $line = preg_replace("/#.+?$/", "", $line);  # remove trailing comment, if any
        if(preg_match("/^(.+?)\s*=\s*(.*)$/", $line, $match)) {
	  # option = value
	  $option = $match[1];
	  $value  = $match[2];
	  $config[$option] = $value;
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
