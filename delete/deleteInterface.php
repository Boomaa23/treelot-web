<?php
if($_GET["ts"] == file_get_contents("../timestamp.txt") && isset($_POST["loc"])) {
	//gets shift data from file
	$handle = fopen("../data.json", "r+");
	$data = array(array());
	for($l = 0;!feof($handle);$l++) {
		$data[$l] = json_decode(fgets($handle));
	}
	
	//removes selected value from internal data
	$loc = trim($data[$_POST["loc"]{0}][(int)(substr($_POST["loc"], 2, strlen($_POST["loc"]) - 1))]);
	echo '<form action="deleteAction.php?loc=' . $_POST["loc"] . '" method="post"><a>Confirm the scouts to remove are correct</a><br />';
	echo '<input type="text" name="confirm"> ' . $loc . '</input><br />';
	echo '<br /><input type="submit"></form>';
} else {
	if(isset($_GET["admin"])) {
		header("refresh:0;url=index.php?admin");
	} else {
		header("refresh:0;url=index.php");
	}
}
?>