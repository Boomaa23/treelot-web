<?php 
include "../auth.php";
if ((authMain() != "admin") && (authMain() != "user")) {
	die("You do not have the adequate credentials to view this page.");
}	
?>

<html>
<head>
<title>TR37 Tree Lot | Shift Comments</title>
<link rel="icon" href="../favicon.png">
<style>
	body {margin-left:20px;font-family:"Arial";}
	#title {font-weight:bold;font-size:28px;}
	#date {font-size:20px;}
	#nostyle {text-decoration:none;color:black;}
</style>
</head>

<body>
<h2 style="text-align:center;">Troop 37 Shift Comments Viewer</h2>
<p style="text-align:center;">This is a page to view or add any comments or concerns you may have about a specific shift. For example, if you want an older scout to accompany your younger scout to a shift, please type as such here. Some comments may be shortened, so you can click on any one to view the full length.</p>
<p style="text-align:center;"><button><a href="add.php" id="nostyle"><b>Add a Comment</b></a></button>
<button><a href="../index.php<?php if(authCheck()) {echo "?admin";} ?>" id="nostyle"><b>Back to main page</b></a></button></p>
<div id="content"><hr /></div>

<?php
function trim_text($input, $length) {
    if (strlen($input) <= $length)
        return $input;

    $last_space = strrpos(substr($input, 0, $length), ' ');
    return substr($input, 0, $last_space) . '...';
}

$files = glob('data/*.txt', GLOB_BRACE);
foreach($files as $file) {
	$fileArray = file("$file");
	$dir_file = str_replace("data/", "", $file);
	$dir_file = str_replace(".txt", "", $dir_file);
	echo '<a id="nostyle" href="view.php?file=' . $dir_file . '&src=current"><div id="title">' . trim_text($fileArray[3] . ' - ' . $fileArray[0],70);
	echo '</div><div id="date">' . $fileArray[1];
	echo '</div><br><div id="content">' . trim_text($fileArray[2],500);
	echo "</a><hr /></div>";
}
?>
<br />
</body>
</html>


