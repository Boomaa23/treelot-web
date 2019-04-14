<style>
	#title {font-weight:bold;font-size:28px;}
	#date {font-size:20px;}
	#nostyle {text-decoration:none;color:black;}
	body{font-family:Arial;margin-left:20px;}
</style>

<?php
if(isset($_GET["line"]) && isset($_GET["src"])) {
	$filename = "allcomments.json";
	if($_GET["src"] == "archive" && isset($_GET["year"])) {
		$filename = 'archive/' . $_GET["year"] . '/';
	}
	
	$handle = fopen($filename, "r+");
	$file = array(array());
	$linecount = 0;
	while(!feof($handle)){
		$file[$linecount] = json_decode(fgets($handle));
		$linecount++;
	}
	
	echo '<div id="title">' . $file[$_GET["line"]][3] . ' - '. $file[$_GET["line"]][0] . '</div>
	<div id="date">' . $file[$_GET["line"]][1] . '</div><br>
	<div id="content">' . $file[$_GET["line"]][2] . '</div>';
	echo '<br><button value=""><a href="index.php" id="nostyle">Back to comment viewer</a></button>';
} else {
	die('There was an error submitting your request. Please try again. <a href="index.php">Back to main page</a>');
}
?>