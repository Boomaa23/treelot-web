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
	
	echo '<div id="title">' . $file[$_GET["line"]][4] . ' - '. $file[$_GET["line"]][0] . '</div>
	<div id="date">' . $file[$_GET["line"]][1] . ' | ' . $file[$_GET["line"]][2] . '</div><br>
	<div id="content">' . $file[$_GET["line"]][3] . '</div>';
	$redir = "index.php";
	if($_GET["src"] == "main") {
		$redir = "../" . $redir;
	}
	if(isset($_GET["admin"])) {
		$redir = $redir . "&admin";
	}
	echo '<br><button value="" onclick="parent.window.location.reload();"><a href="' . $redir . '" id="nostyle">Back to previous page</a></button>';
} else {
	die('There was an error submitting your request. Please try again. <a href="index.php">Back to main page</a>');
}
?>