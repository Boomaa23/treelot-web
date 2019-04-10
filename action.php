<?php
if(!file_exists("timestamp.txt")) {
	file_put_contents("timestamp.txt", null, FILE_APPEND);
}

if(isset($_POST["AA"]) && ($_GET["ts"] == file_get_contents("timestamp.txt"))) {
	//clears shift data file and timestamp
	if(file_exists("data.json")) { ftruncate(fopen("data.json", "r+"), 0); }
	ftruncate(fopen("timestamp.txt", "r+"), 0);
	
	//writes inputs from html to file
	$data = json_encode($_POST["AA"]) . PHP_EOL . json_encode($_POST["AB"]) . PHP_EOL . 
	json_encode($_POST["BA"]) . PHP_EOL . json_encode($_POST["BB"]) . PHP_EOL . 
	json_encode($_POST["CA"]) . PHP_EOL . json_encode($_POST["CB"]);
	file_put_contents("data.json", $data, FILE_APPEND);
	
	//writes new timestamp to file
	file_put_contents("timestamp.txt", time() , FILE_APPEND | LOCK_EX);
}

if(isset($_GET["admin"])) {
	header("refresh:0;url=index.php?admin");
} else {
	header("refresh:0;url=index.php");
}
?>