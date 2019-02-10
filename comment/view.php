<style>
	#title {font-weight:bold;font-size:28px;}
	#date {font-size:20px;}
	#nostyle {text-decoration:none;color:black;}
	body{font-family:Arial;margin-left:20px;}
</style>

<?php
if(isset($_GET["file"]) && isset($_GET["src"])) {
	if($_GET["src"] == "archive" && isset($_GET["year"])) {
		$file = file('archive/' .$_GET["year"] . '/comment/' . $_GET["file"] . '.txt');
	} else {
		$file = file("data/" . $_GET["file"]. ".txt");
	}
	echo '<div id="title">' . $file[3] . ' - '. $file[0] . '</div><div id="date">' . $file[1] . '</div><br><div id="content">' . $file[2] . '</div>';
	echo '<br><button value=""><a href="index.php" id="nostyle">Back to comment viewer</a></button>';
} else {
	die('There was an error submitting your request. Please try again. <a href="index.php">Back to main page</a>');
}
?>