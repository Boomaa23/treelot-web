<?php
if($_GET["ts"] == file_get_contents("../timestamp.txt") && isset($_POST["val"])) {
	//gets shift data from file
	$handle = fopen("../data.json", "r+");
	$data = array(array());
	$linecount = 0;
	while(!feof($handle)){
		$data[$linecount] = json_decode(fgets($handle));
		$linecount++;
	}
	
	//removes selected values from internal data
	echo '<form action="deleteAction.php" method="post"><a>Confirm the scouts to remove are correct</a><br />';
	$val = $_POST["val"];
	$x = is_array($val) ? sizeof($val) : 1;
	for($i = 0;$i < $x;$i++) {
		echo '<input style="display:none;" type="text" name="cval[]" value="' . $val[$i] . '"></input>';
		echo '<input style="display:none;" type="text" name="ival[]" value="' . $i . '"></input>';
		echo '<input type="text" name="confirm[]"> ' . $data[$val[$i]{0}][(int)(substr($val[$i], 2, strlen($val[$i]) - 1))] . '</input>';
	}
	echo '<br /><br /><input type="submit"></form>';
} else {
	if(isset($_GET["admin"])) {
		header("refresh:0;url=index.php?admin");
	} else {
		header("refresh:0;url=index.php");
	}
}
?>