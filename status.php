<html>
<head>
	<title>TR37 Tree Lot | Site Status</title>
	<link rel="icon" href="favicon.png">
	<style>
		th, td {border:1px solid grey; text-align:center;}
    body {font-family:"Arial";}
		h2, p {text-align:center;}
	</style>
</head>

<body>
<h2>Page Site Status</h2>
<p>A check indicates the site is up to date with GitHub.</p>

<?php
if(isset($_POST["pass"])) {
	$files = json_decode(file_get_contents("directory.json"), true);
  $serverurlprefix = 'ftp://b4_22868853:' . $_POST["pass"] . '@ftp.byethost4.com/htdocs/';
  $giturlprefix = "https://raw.githubusercontent.com/Boomaa23/treelot-web/master/";
  
  $valid = array();
	foreach($files as $group => $filegroup) {
	  foreach($filegroup as $file) {
				$fqfn = ($group !== "root") ? ($group . '/' . $file) : $file; //fqfn = fully qualified file name :P
				$server_file = md5(file_get_contents($serverurlprefix . $fqfn));
		    $git_file = md5(file_get_contents($giturlprefix . $fqfn));
		    array_push($valid, ($server_file === $git_file) ? " &check;" : " &cross;");
		    sleep(0.01);
	  }
	}

  echo '<div class="files"><ul>';
	$v_ct = 0;
	foreach($files as $group => $filegroup) {
		if($group !== "root") { echo '<li>' . $group . '<ul>'; }
	  foreach($filegroup as $file) { echo '<li>' . $file . $valid[$v_ct] . '</li>'; $v_ct++; }
		if($group !== "root") { echo '</li></ul>'; }
	}
	echo '</ul></div>';
} else {
  echo '
  <form action="' . $_SERVER["PHP_SELF"] . '" method="post">
  Password: <input name="pass" type="password"></input><br />
  <input type="submit">
  </form>';
}
?>

</body>
</html>