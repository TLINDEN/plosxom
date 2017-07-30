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

1. copy categories to your plugin directory.

2. add the following code somewhere to your template:

{if $categories}
  <ul class="catlist">
  {foreach item=cat from=$categories}
    <li><a href="{$config.whoami}/category/{$cat}">{$cat}</a></li>
  {/foreach}
  </ul>
{/if}

That's it.

*/

class categories extends Plugin {

  function register() {
    # we do not register any hooks

    $this->smarty->assign("categories", standard::fetch_categories());

    return;
  }


}