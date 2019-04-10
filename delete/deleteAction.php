<?php
if($_GET["ts"] == file_get_contents("../timestamp.txt")) {

	$handle = fopen("../data.json", "r+");
	$data = array(array());
	$linecount = 0;
	while(!feof($handle)){
		$data[$linecount] = json_decode(fgets($handle));
		$linecount++;
	}

	ftruncate(fopen("../timestamp.txt", "r+"), 0);

	//writes inputs from html to file
  $out = "";
	for($i = 0;$i < sizeof($data);$i++) {
		$out = $out . json_encode($data[$i]) . PHP_EOL;
	}
	
	file_put_contents("../data.json", $out, FILE_APPEND);
	file_put_contents("../timestamp.txt", time(), FILE_APPEND | LOCK_EX);
} else {
	die('There was an error submitting your request. Please try again. <a href="index.php">Back to main page</a>');
}

if(isset($_GET["admin"])) {
	header("refresh:0;url=index.php?admin");
} else {
	header("refresh:0;url=index.php");
}
?>