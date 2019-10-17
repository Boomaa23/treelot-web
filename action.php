<?php
session_start();
include "../auth.php";
if ((authMain() != "admin") && (authMain() != "user")) {
	die("You do not have the adequate credentials to view this page.");
}	
if(!file_exists("timestamp.txt")) {
	file_put_contents("timestamp.txt", null, FILE_APPEND);
}

if(isset($_POST["AA"]) && ($_GET["ts"] == file_get_contents("timestamp.txt"))) {
	//grab ip for ip-based shift rewrites
	$rawrtn = file_get_contents("https://httpbin.org/ip");
	$rtnip = json_decode($rawrtn)->origin;
	$allip = explode (", ", $rtnip);
	
	//open existing shift data
	$handle = fopen("data.json", "r+");
	$data = array(array());
	$linecount = 0;
	while(!feof($handle)){
		$data[$linecount] = json_decode(fgets($handle));
		$linecount++;
	}
	
	//form new posted data into 2d array and note differences
	$data_array = array($_POST["AA"], $_POST["AB"], $_POST["BA"], $_POST["BB"], $_POST["CA"], $_POST["CB"]);
	$diff_array = array();
	for($i = 0;$i < sizeof($data_array);$i++) {
		for($j = 0;$j < sizeof($data_array[$i]);$j++) {
			if($data_array[$i][$j] !== $data[$i][$j])
			array_push($diff_array, array($i, $j));
		}
	}
	
	if(!isset($_SESSION['filled'])) {
		$_SESSION['filled'] = array("");
	}
	
	//loop through array and add entered shifts to session var
	for($i = 0;$i < sizeof($diff_array);$i++) {
		array_push($_SESSION['filled'], $diff_array[$i][0] . '-' . $diff_array[$i][1]);
	}

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