<html>
<head>
<title>Plosxom Installation and Sanity Checks</title>

<style type="text/css">
<!--
body {
  font-family: Verdana, Arial, Helvetica, sans-serif;
  font-size: 10px;
}
td,th {
  text-align: left;
}
-->
</style>

</head>

<?

include('lib/plosxom-lib.php');

// fetch input                                                                                                                                                                                                  
foreach ($_GET as $option => $value) {
  $input[$option] = $value;
}
foreach ($_POST as $option => $value) {
  $input[$option] = $value;
}

if(!$input['stage']) {
  // start
  ?>
    <h1>Welcome to the plosxom installation</h1>
    <table width="800" border="0" cellspacing="2" cellpadding="1">
    <tr>
       <td colspan="2">Loading plosxom.conf</th>
       <td>
  <?
    $config = parse_config(dirname($_SERVER["SCRIPT_FILENAME"]) . "/etc/plosxom.conf");
    if($config) {
      print '<font color=green>works</font>';
    }
    else {
      print '<font color=red>failed</font>';
    }
  ?>
      </td>
    </tr>
    <tr><th colspan="3"><hr noshade/></th></tr>
     <tr>
      <th>Directory</th>
      <th>Requirements</th>
      <th>Status</th>
     </tr>
  <?
       $directories = array(
			    'config-dir'   => dirname($_SERVER["SCRIPT_FILENAME"]) . "/etc",
			    'template-dir' => $config['template_path'],
			    'plugin-dir'   => $config['plugin_path'],
			    'tmp-dir'      => $config['tmp_path'],
			    'image-dir'    => $config['image_path'],
			    'data-dir'     => $config['data_path']
			    );
    foreach ($directories as $type => $dir) {
      print "<tr><td>$type: $dir</td><td>Must be readable for plosxom to work<td><font color=";
      if(is_readable($dir)) {
	print "green>is readable</td></tr>\n";
      }
      else {
        $notreadable++;
	print "red>not readable</td></tr>\n";
      }

      print "<tr><td>&nbsp;</td><td>Must be writable for admin backend to work<td><font color=";
      if(is_writable($dir)) {
        print "green>is writable</td></tr>\n";
      }
      else {
        $notwritable++;
        print "red>not writable</td></tr>\n";
      }
      print '<tr><th colspan="3"><hr noshade/></th></tr>';
    }

    ?>
      </table>
    <?
	  
    if($notreadable) {
      print "<h2>Some directories are not readable! Please fix the permissions and reload this page to continue!</h2>";
    }
    else {
      print "<h2>All directories are readable, so your new blog is now working</h2>";
      print "<a href=\"" . $config{whoami} . "\">You may now visit your blog.</a>";
      if($notwritable) {
	print "<h2>Some directories are not writable! The admin backend may not work, please fix the permissions and reload this page to continue!</h2>";
      }
      else {
	?>
	<h2>Enter password to create an admin account</h2>
	<form name="admin" action="install.php" method="post">
	<input type="hidden" name="stage" value="admin">
	<table border="0" cellspacing="2" cellpadding="2" width="400">
	 <tr>
	  <th>Password</th>
	  <td><input type="password" name="password" size="20"></td>
	 </tr>
	 <tr>
          <th>Repeat Password</th>
          <td><input type="password" name="password2" size="20"></td>
         </tr>
	 <tr>
	   <td colspan="2"><input type="submit" name="submit" value="create admin user"></td>
	 </tr>
	</table>
        <?
      }
    }
}
elseif($input['stage'] == 'admin') {
  if($input['password'] == $input['password2']) {
    if(strlen($input['password']) < 6) {
      print "<h1>Password too short, go back and retry!</h1>";
    }
    else {
      $md5 = md5($input['password']);
      $users = dirname($_SERVER["SCRIPT_FILENAME"]) . "/etc/admin-users.conf";
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
    print "<a href=\"" . $config{whoami} . "/admin\">Login to the admin backend now!</a>";
    print "<br/><a href=\"" . $config{whoami} . "\">Visit your blog now!</a>";
    print "<h2>Please remove the file <b>install.php</b></h2>";
  }

}

?>

</html>