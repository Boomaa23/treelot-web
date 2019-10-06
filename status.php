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
<h2>TR37 Page Site Status</h2>
<p>A check indicates the site is up to date with GitHub.</p>

<?php
if(isset($_POST["pass"])) {
  $serverurlprefix = 'ftp://b4_22868853:' . $_POST["pass"] . '@ftp.byethost4.com/htdocs/';
  $giturlprefix = "https://raw.githubusercontent.com/Boomaa23/treelot-web/master/";
  $files = array(
    "archive/index.php",
    "comment/add.php",
    "comment/addAction.php",
    "comment/commentstyle.css",
    "comment/index.php",
    "comment/view.php",
    "delete/deleteAction.php",
    "delete/index.php",
    "delete/redirect.js",
    ".gitignore",
    ".htaccess",
    "action.php",
    "adminAuth.php",
    "auth.php",
    "error.php",
    "index.php",
    "preferences.php",
    "reset.php",
    "status.php"
  );
  $valid = array();
  
  for($i = 0;$i < sizeof($files); $i++) {
    $server_file = file_get_contents($serverurlprefix . $files[$i]);
    $git_file = file_get_contents($giturlprefix . $files[$i]);
    $valid[$i] = ($server_file === $git_file) ? " &check;" : " &cross;";
    sleep(0.01);
  }

  echo '
  <div class="files">
    <ul>
      <li>archive<ul>
        <li>index.php' . $valid[0] . '</li>
      </ul></li>
      <li>comment<ul>
        <li>add.php' . $valid[1] . '</li>
        <li>addAction.php' . $valid[2] . '</li>
        <li>commentstyle.css' . $valid[3] . '</li>
        <li>index.php' . $valid[4] . '</li>
        <li>view.php' . $valid[5] . '</li>
      </ul></li>
      <li>delete<ul>
        <li>deleteAction.php' . $valid[6] . '</li>
        <li>index.php' . $valid[7] . '</li>
        <li>redirect.js' . $valid[8] . '</li>
      </ul></li>
      <li>.gitignore' . $valid[9] . '</li>
      <li>.htaccess' . $valid[10] . '</li>
      <li>action.php' . $valid[11] . '</li>
      <li>adminAuth.php' . $valid[12] . '</li>
      <li>auth.php' . $valid[13] . '</li>
      <li>error.php' . $valid[14] . '</li>
      <li>index.php' . $valid[15] . '</li>
      <li>preferences.php' . $valid[16] . '</li>
      <li>reset.php' . $valid[17] . '</li>
      <li>status.php' . $valid[18] . '</li>
    </ul>
  </div>';
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