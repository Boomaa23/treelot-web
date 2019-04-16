<?php
if($_GET["ts"] == file_get_contents("../timestamp.txt")) {
	//gets shift data from file
	$handle = fopen("../data.json", "r+");
	$data = array(array());
	$linecount = 0;
	while(!feof($handle)){
		$data[$linecount] = json_decode(fgets($handle));
		$linecount++;
	}
	
	//removes selected values from internal data
	$val = $_POST["val"];
	$x = is_array($val) ? sizeof($val) : 1;
	for($i = 0;$i < $x;$i++) {
		//TODO add live checking for correct name
		$data[$val[$i]{0}][(int)(substr($val[$i], 2, strlen($val[$i]) - 1))] = "";
	}
	
	//clears old shift data file
	ftruncate(fopen("../data.json", "r+"), 0);
	
	//writes inputs from html to file
  $out = "";
	for($i = 0;$i < sizeof($data);$i++) {
		$add = json_encode($data[$i]);
		if(!is_null($add)) {
			$out = $out . $add . PHP_EOL;
		}
	}
	file_put_contents("../data.json", trim($out), FILE_APPEND);
	
	//clears and resets timestamp
	ftruncate(fopen("../timestamp.txt", "r+"), 0);
	file_put_contents("../timestamp.txt", time(), FILE_APPEND | LOCK_EX);
}

if(isset($_GET["admin"])) {
	header("refresh:0;url=index.php?admin");
} else {
	header("refresh:0;url=index.php");
}
?>