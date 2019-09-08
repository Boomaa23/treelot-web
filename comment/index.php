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
<link rel="stylesheet" type="text/css" href="commentstyle.css">
</head>

<body>
<h1 style="text-align:center;">Troop 37 Shift Comments</h1>
<p style="text-align:center;margin:0 20% 0 20%;">This is a page to view or add any comments or concerns you may have about a specific shift. For example, if you want an older scout to accompany your younger scout to a shift, please type as such here. Some comments may be shortened, so you can click on any one to view the full length.</p>
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

	//reads existing signups from file
	$handle = fopen("allcomments.json", "r+");
	$data = array(array());
	$linecount = 0;
	while(!feof($handle)){
		$data[$linecount] = json_decode(fgets($handle));
		$linecount++;
	}

	for($i = 0;$i < $linecount;$i++) {
		if($data[$i] != "") {
			echo '<a id="nostyle" href="view.php?line=' . $i . '&src=current"><div id="title">' . trim_text($data[$i][4] . ' - ' . $data[$i][0],70);
			echo '</div><div id="date">' . $data[$i][1] . ' | ' . $data[$i][2];
			echo '</div><br><div id="content">' . trim_text($data[$i][3],500);
			echo "</a><hr /></div>";
		}
	}
	?>
	<br />
</body>
</html>


