<?php
function generateRandomString($length = 8) {
    return substr(str_shuffle(str_repeat($x='0123456789ABCDEFGHIJKLMNOPQRSTUVWXYZ', ceil($length/strlen($x)) )),1,$length);
}

if(isset($_POST["date"]) && isset($_POST["comment"])) {
	$filename = generateRandomString();
    file_put_contents("data/" . $filename . ".txt", $_POST["title"] . PHP_EOL . $_POST["date"] . PHP_EOL . $_POST["comment"], FILE_APPEND);
	
} else {
	die('There was an error submitting your request. Please try again. <a href="index.php">Back to main page</a>');
}

header("refresh:0;url=index.php");
?>