<?php
if(isset($_POST["confirm"])) {
	//reads shift data from file
	$handle = fopen("../data.json", "r+");
	$data = array(array());
	$linecount = 0;
	while(!feof($handle)){
		$data[$linecount] = json_decode(fgets($handle));
		$linecount++;
	}

	//checks for correct entered value
	for($i = 0;$i < sizeof($_POST["cval"]);$i++) {
		$val = $_POST["cval"][$i];
		$j = $_POST["ival"][$i];
		if($_POST["confirm"][$i] == $data[$val[$j]{0}][(int)(substr($val[$j], 2, strlen($val[$j]) - 1))]) {
			$data[$val[$i]{0}][(int)(substr($val[$i], 2, strlen($val[$i]) - 1))] = "";
		} else {
			echo '<a>The scout name <b>' . $_POST["confirm"][$i] . '</b> did not match the correct scout name of <b>' . $data[$val[$j]{0}][(int)(substr($val[$j], 2, strlen($val[$j]) - 1))];
			echo '</b><br /><a>You will be redirected in </a><span id="seconds">10</span> <a> seconds</a><script src="redirect.js" type="text/javascript"></script>';
		}
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