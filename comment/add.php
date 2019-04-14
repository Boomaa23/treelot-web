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
	th, td {text-align:center;}
</style>
</head>

<body>
	<h2 style="text-align:center;">Add a Shift Comment</h2><br />
	
<form action="addAction.php" method="post">
	<a><b>Your Scout's Name:</a></b><br />
	<input type="text" style="width:300px;" name="name" required>
	<br /><br />
	
	<a><b>Select a date:</b></a>
	<table><tr>
	<?php
	$dates = json_decode(file_get_contents("../resetDates.json"));
	$begin = new DateTime($dates[2] . "-" . $dates[0] . "-" . $dates[1]);
	$end = new DateTime($dates[5] . "-" . $dates[3] . "-" . $dates[4]);
	$interval = DateInterval::createFromDateString('1 day');
	$period = new DatePeriod($begin, $interval, $end);
	$wk = 1;
	
	foreach ($period as $dt) {
		echo '<td><input type="radio" name="date" value = ' . $dt->format("m/d/Y\n") . ' required></td><td>' . $dt->format("l, m/d/Y\n") . '</td>';
		if($wk%3 == 0) {
			echo '</tr><tr>';
		}
		$wk++;
	}
	?>
	</table><br />
	<a><b>Title your comment</a></b><br />
	<input type="text" style="width:300px;" name="title" required>
	<br /><br />
	
	<a><b>Write your comment</b></a><br />
	<textarea name="comment" cols="70" rows="15" required></textarea>
	<br /><br /><input type="submit">
</form>
</body>
</html>


