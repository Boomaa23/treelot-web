<?php
function generateRandomString($length = 8) {
    return substr(str_shuffle(str_repeat($x='0123456789ABCDEFGHIJKLMNOPQRSTUVWXYZ', ceil($length/strlen($x)) )),1,$length);
}

if(isset($_POST["date"]) && isset($_POST["comment"])) {
  $data = array($_POST["title"], $_POST["date"], $_POST["time"], $_POST["comment"], $_POST["name"], generateRandomString());
  file_put_contents("allcomments.json", json_encode($data) . PHP_EOL, FILE_APPEND);
} else {
	die('There was an error submitting your request. Please try again. <a href="index.php">Back to main page</a>');
}

header("refresh:0;url=index.php");
?>