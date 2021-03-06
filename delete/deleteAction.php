<?php
	include "../auth.php";
	if ((authMain() != "admin") && (authMain() != "user")) {
		die("You do not have the adequate credentials to view this page.");
	}	
	
	$queue = json_decode(file_get_contents('../preferences.json'), true)["requests"] == "true" ? false : true;
	
	//reads shift data from file
	$handle = fopen("../data.json", "r+");
	$data = array(array());
	$linecount = 0;
	while(!feof($handle)){
		$data[$linecount] = json_decode(fgets($handle));
		$linecount++;
	}

	//checks for correct entered value
	$dy_loc = (int)(substr($_GET["loc"], 2, strlen($_GET["loc"]) - 1));
	if(trim($_POST["confirm"]) == trim($data[$_GET["loc"]{0}][$dy_loc])) {
		$loc = trim($data[$_GET["loc"]{0}][$dy_loc]);
		$removed = array("name" => $loc, "shiftlocation" => $_GET["loc"]{0} . "-" . $dy_loc, "accessed" => date("m-d-Y H:i:s T"));
		if($queue || isset($_GET["line"])) {
			file_put_contents("removelog.json", json_encode($removed) . PHP_EOL, FILE_APPEND);
			$data[$_GET["loc"]{0}][$dy_loc] = "";
		} else {
			file_put_contents("removequeue.json", json_encode($removed) . PHP_EOL, FILE_APPEND);
		}
	} else if(!isset($_GET["line"])){
		echo '<a>The scout name <b>' . $_POST["confirm"] . '</b> did not match the correct scout name of <b>' . $data[$_GET["loc"]{0}][$dy_loc];
		die('</b><br /><a>You will be redirected in </a><span id="seconds">10</span> <a> seconds</a><script src="redirect.js" type="text/javascript"></script>');
	}
	
if(isset($_POST["confirm"])) {
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

if(isset($_GET["line"])) {
	$removeFile = file("removequeue.json");
	unset($removeFile[(int)$_GET["line"]]);
	file_put_contents("removequeue.json", implode("", $removeFile));
	header("refresh:0;url=requests.php");
} else {
	if(isset($_GET["admin"])) {
		header("refresh:0;url=index.php?admin");
	} else {
		header("refresh:0;url=index.php");
	}
}


?>