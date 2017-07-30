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

1. copy links.php to your plugins directory.

2. create one or more link configs in your etc directory. In your template
   you can then loop over links by config. This makes it possible
   to maintain page links separately from blogroll or the like.

   Format is simple:

     link name = link url

   for example:

     Peppos Incarnation = http://foo.bar/

   Empty lines and lines starting with # will be ignored as
   always in in configs.

3. add some code to your template to loop over the links of
   a particular links config file, eg:

   <div class="links">
     <h4>Blogroll</h4>
     <ul>
      
      {links config="blogroll.conf" template="<a href='%1'>%2</a> "}
    </ul>
   </div>

   The template function "links" exported by the links module
   requires 2 parameters:

    config   - the link config file you are referring to
    template - how the link shall appear. %1 will be used for the
               url and %2 for the link name.

That's it - now add links to your link configs as you like!

*/

class links extends Plugin {
  var $links;
  function register() {
    $this->smarty->register_function("links", array(&$this, "getlinks"));
  }
  
  function getlinks($params, &$smarty) {
    $tpl = '<li><a href="%1">%2</a></li>';
    $cfg = 'links.conf';

    if( $params["config"]) {
      $cfg = $params["config"];
    }

    if( $params["template"] ) {
      $tpl = $params["template"];
    }
    
    $links = parse_config($cfg);
    ksort($links);

    $out = "";
    foreach ($links as $name => $link) {
      $out .= str_replace(array("%1", "%2"), array($link, $name), $tpl); 
    }

    return $out;
  }
  
}

?>
