<html>
<head>
<title>Plosxom Installation and Sanity Checks</title>

<style type="text/css">
<!--
body, input {
  font-family: Verdana, Arial, Helvetica, sans-serif;
  font-size: 9px;
}
input {
  border: 1px solid #6DA6E2;
}
td,th {
  white-space: nowrap;
  text-align: left;
  font-family: Verdana, Arial, Helvetica, sans-serif;
  font-size: 9px;
  border-bottom: 1px solid #c4c4c4;
  margin-bottom: 5px;
}
a {
 color: #6DA6E2;
 background-color: #FFFFFF;
 text-decoration: none;
 font-weight: bold;
}
a:hover {
  background-color: #FFFFFF;
  color: inherit;
  font-weight: bold;
  text-decoration: underline;
}
h1 {
 color: #6DA6E2;
 font-size: 12px;
}
-->
</style>

</head>
<body>
    <h1>Welcome to the plosxom installation</h1>
<?

include('lib/plosxom-lib.php');

// fetch input                                                                                                                                                                                                  
foreach ($_GET as $option => $value) {
  $input[$option] = $value;
}
foreach ($_POST as $option => $value) {
  $input[$option] = $value;
}

if($_SERVER['SERVER_PORT'] !== 80) {
  $port = ':' . $_SERVER['SERVER_PORT'];
}
$proto = "http://";
if($_SERVER['HTTPS']) {
  $proto = "https://";
}
$path       = dirname($_SERVER['SCRIPT_NAME']);
$lib_path   = dirname(__FILE__) . "/lib";
$baseurl    = rtrim($proto . $_SERVER['HTTP_HOST'] . $path, '/');
$imgurl     = $baseurl . '/images';
$whoami     = $baseurl . '/plosxom.php';
$configfile = dirname(__FILE__) . "/etc/plosxom.conf";

if(file_exists(dirname(__FILE__) . "/etc/plosxom.conf")) {
  $config = parse_config($configfile);
}
else {
  /* generate config from dist file */
  $content = implode ('', file(dirname(__FILE__) . "/etc/plosxom.conf.dist"));
  $cfgvars = array ('[template_path]', '[plugin_path]', '[tmp_path]',
		    '[image_path]', '[data_path]', '[author_link]',
		    '[whoami]', '[baseurl]', '[imgurl]');
  $dirs = array(
		dirname(__FILE__) . "/templates",
		dirname(__FILE__) . "/plugins",
		"/tmp",
		dirname(__FILE__) . "/images",
		dirname(__FILE__) . "/data",
		$whoami, $whoami, $baseurl, $imgurl
		);
  
  $generated = str_replace($cfgvars, $dirs, $content);

  if(is_writable(dirname(__FILE__) . "/etc")) {
    if(write_config($generated)) {
      print "<h1>Generated config $configfile</h1>";
      $config = parse_config($configfile);
    }
  }
  else {
    print "<h1>Generated config, copy the following text to $configfile:</h1>";
    print "<pre style='border: 1px solid #c4c4c4;'>$generated</pre>";
    exit;
  }
}

if($input['stage'] == 'var') {
  if(is_readable($configfile)) {
    $content = implode ('', file($configfile));
    $changed = preg_replace("/^" . $input['var'] . " = .*$/m", $input['var'] . ' = ' . $input['value'], $content);
    if($changed !== $content) {
      if(is_writable($configfile)) {
	write_config($changed);
      }
      else {
	print "<h1>Could not write to $configfile! Please fix the permissions and retry!</h1>";
      }
    }
    else {
      print "<h1>Config unchanged!</h1>";
    }
  }
  else {
    print "<h1>Failed to open $configfile for reading!</h1>";
  }
}

elseif($input['stage'] == 'admin') {
  if($input['password'] == $input['password2']) {
    if(strlen($input['password']) < 6) {
      print "<h1>Password too short, go back and retry!</h1>";
    }
    else {
      $md5 = md5($input['password']);
      $users = dirname(__FILE__) . "/etc/admin-users.conf";
      if(is_writable($users)) {
	$fd = fopen($users, 'w');
	if( $fd ) {
	  if( fwrite($fd, "admin = $md5\n") ) {
	    print "<h1>User 'admin' successfully created. You may now login to the admin backend.</h1>";
	    $success = 1;
	  }
	  else {
	    print "<h1>Failed to write account data to $users! Please fix the permissions and retry!</h1>";
	  }
	}
	else {
	  print "<h1>Failed to open $users for writing! Please fix the permissions and retry!</h1>";
	}
      }
      else {
	print "<h1>Could not write to $users! Please fix the permissions and retry!</h1>";
      }
    }
  }
  else {
    print "<h1>Passwords doesn't match, go back and retry!</h1>";
  }

  if($success) {
    print "<a href=\"" . $config['whoami'] . "/admin\">Login to the admin backend now!</a>";
    print "<br/><a href=\"" . $config['whoami'] . "\">Visit your blog now!</a>";
    print "<h1>Please remove the file <b>install.php</b></h1>";
  }

}

  // start
  ?>

    <table width="800" border="0" cellspacing="0" cellpadding="2">
    <tr>
       <td colspan="3">Loading <? print $configfile; ?></th>
       <td>
  <?

    if($config) {
      print '<font color=green>works</font>';
    }
    else {
      print '<font color=red>failed</font>';
    }
  ?>
      <br/><br/></td>
    </tr>
   
   
   <tr>
      <td colspan="3">Loading Smarty engine</td>
      <td>
  <?

      if($config) {
	define('SMARTY_DIR', $lib_path . "/"); 
	include(SMARTY_DIR . 'Smarty.class.php');
	$smarty = new Smarty;
	if($smarty) {
	  print '<font color=green>works</font>';
	}
	else {
	  print '<font color=red>failed</font>';
	}
      }
      else {
	print '<font color=red>failed</font>';
      }

 ?>
     <br/><br/></td>
   </tr>
   <tr>
     <th><br/>Option</th>
     <th colspan="2"><br/>Current value</th>
     <th>Testlink</th>
   </tr>
   <?

    
    $vars = array('whoami'   => array($config['whoami'],  $config['whoami']),
		  'baseurl'  => array($config['baseurl'], $config['baseurl'] . "/templates/shared/ok.png"),
		  'imgurl'   => array($config['imgurl'],  $config['imgurl'] . "/plosxom.png"));

    $varform    = '<form name="modifyvar" action="install.php" method="post"><input type="hidden" name="stage" value="var">';
    $varformend = '<input type="submit" name="submit" value="change"></form>';

    foreach ($vars as $varname => $value) {
      print "<tr><td valign=top>$varname</td><td colspan='2'>" . $varform . "<input type='hidden' name='var' value='$varname'>"
	. "<input size=60 type=text name='value' value='"
	. $value[0] . "'>" . $varformend . "</td>"
	. "<td><a href=\"$value[1]\">test</a></td>"
	. "</tr>";
    }

   ?>
     <tr>
      <th><br/>Directory</th>
      <th><br/>Current value</th>
      <th><br/>Readable</th>
      <th><br/>Writable</th>
     </tr>
  <?
       $directories = array(
			    'template_path' => $config['template_path'],
			    'plugin_path'   => $config['plugin_path'],
			    'tmp_path'      => $config['tmp_path'],
			    'image_path'    => $config['image_path'],
			    'data_path'     => $config['data_path'],
			    );

    $dirform    = '<form name="modifyvar" action="install.php" method="post"><input type="hidden" name="stage" value="var">';
    $dirformend = '<input type="submit" name="submit" value="change"></form>';
    foreach ($directories as $type => $dir) {
      print "<tr><td valign=top>$type</td><td>" . $dirform . "<input type='hidden' name='var' value='$type'>"
	. "<input size=60 type=text name='value' value='"
	. $dir . "'>" . $dirformend . "</td>"
	. "<td><font color=";
      
      if(is_readable($dir)) {
	print "green>OK</td>\n";

      }
      else {
        $notreadable++;
	print "red>FAILED</td>\n";
      }

      print "<td><font color=";
      if(is_writable($dir)) {
        print "green>OK</td>\n";
      }
      else {
        $notwritable++;
        print "red>FAILED</td>\n";
      }
      print '</tr>';
    }

    if($notreadable) {
      print "<tr><td colspan='4'><br/><h1>Some directories are not readable!<br/>Please fix the permissions and<br/>reload this page to continue!</h1></td></tr>";
    }
    else {
      print "<tr><td colspan='4'><br/><h1>All directories are readable, so your new blog is now working</h1>";
      print "<a href=\"" . $config{whoami} . "\">You may now visit your blog.<br/><br/></a></td></tr>";
      if($notwritable) {
	print "<tr><td colspan='4'><br/><h1>Some directories are not writable!<br/>The admin backend may not work, please fix the permissions<br/>and reload this page to continue!</h1></td></tr>";
      }
      else {
	?>
      <tr>
        <td><h4>Enter password<br/>to create an<br/> admin account</h4></td>
	<td>
	<form name="admin" action="install.php" method="post">
	  <input type="hidden" name="stage" value="admin">
	  Password:
	  <input type="password" name="password" size="10">
	  &nbsp; &nbsp; Repeat Password:
          <input type="password" name="password2" size="10">
       </td>
       <td colspan="2">
	<input type="submit" name="submit" value="create admin account">
       </td>
      </tr>
        <?
      }
    }


function write_config($content) {
  global $configfile;
  $fd = fopen($configfile, 'w');
  if($fd) {
    if( fwrite($fd, $content) ) {
      fclose($fd); 
      chmod($configfile , 0666);
      print "<h1>Configfile successfully written.</h1>";
      $config = parse_config($configfile);
    }
    else {
      print "<h1>Failed to write data to $configfile! Please fix the permissions and retry!</h1>";
      return false;
    }
  }
  else {
    print "<h1>Failed to open $configfile for writing! Please fix the permissions and retry!</h1>";
    return false;
  }

  return true;
}

?>
</table>
</body>
</html>