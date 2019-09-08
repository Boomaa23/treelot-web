<?php
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
	print_r($diff_array);
	
	//loop through array and attach a corresponding ip to shift
	$ipmap = json_decode(file_get_contents("shiftipmap.json"), true);
	for($i = 0;$i < sizeof($diff_array);$i++) {
		$ipmap[$diff_array[$i][0]][$diff_array[$i][1]] = $allip[sizeof($allip) - 1];
	}
	file_put_contents("shiftipmap.json", json_encode($ipmap));

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