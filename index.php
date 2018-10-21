<?php 
include "auth.php";
if ((authMain() != "admin") && (authMain() != "user")) {
	die("You do not have the adequate credentials to view this page.");
}
if (authMain() == "admin") {
	header("refresh:0;url=adminAuth.php");
}
?>

<html>
<head>
	<title>TR37 Tree Lot Signup</title>
	<link rel="icon" href="favicon.png">
<style>
	th, td {border:1px solid grey; text-align:center;}
	body {text-align:center;font-family:"Arial";}
	#in1 {background-color:grey;}
	#par {width:740px;margin:auto;}
	#dis {background-color:#eaeaea;}
</style>
</head>
<body>
	<h1>Troop 37 Tree Lot Signup</h1>
	<div id="par">
	<p>This is the website to sign up for tree lot shifts for 2018. On the weekdays, the tree lot is only open from 3pm to 9pm, so the afternoon shift is reduced to 3pm-5pm and there is no morning shift. There is space for two scouts (and their parents) to sign up for each shift. Each scout must sign up for at least 16 hours worth of shifts.</p>
	<p><b> Do not delete filled in shifts from other scouts.</b> Please contact Nikhil Ograin by email at <a href="mailto:nikhil.ograin@gmail.com">nikhil.ograin@gmail.com</a> or by phone at (805) 350-8503 if you have any issues with signups. Thank you!</p>
	</div>
	<br />
	<table cellspacing="0" cellpadding="5" align="center">
	<tr> <!--times-->
		<th></th>
		<th>9am-1pm</th>
		<th>1pm/3pm-5pm</th>
		<th>5pm-9pm</th>
	</tr>
	
	<!--enter box fields-->
	<form action="action.php?ts=<?php echo file_get_contents("timestamp.txt"); ?>" method="post">
	<?php
	//logs ip and time of access
	$ip = $_SERVER['REMOTE_ADDR'];
	$dateTime = date('m/d/Y G:i:s');
	$date = $dateTime . " - " . $ip ;
	file_put_contents("iplog.txt", $date . PHP_EOL, FILE_APPEND);
	
	//reads existing signups from file
	$dataAA = json_decode(file_get_contents("data/dataShiftsAA.json"));
	$dataAB = json_decode(file_get_contents("data/dataShiftsAB.json"));
	$dataBA = json_decode(file_get_contents("data/dataShiftsBA.json"));
	$dataBB = json_decode(file_get_contents("data/dataShiftsBB.json"));
	$dataCA = json_decode(file_get_contents("data/dataShiftsCA.json"));
	$dataCB = json_decode(file_get_contents("data/dataShiftsCB.json"));
	
	//read values from reset page
	$dates = file("resetDates.txt");
	
	//setup of date counter
	$begin = new DateTime($dates[0]);
	$end = new DateTime($dates[1]);
	$interval = DateInterval::createFromDateString('1 day');
	$period = new DatePeriod($begin, $interval, $end);
	$wk=0;

	//setup of shift form boxes
	foreach ($period as $dt) {
		
		//checks to disable weekend shifts
		$wkCk = (int)$dates[2];
		if ($wk%7 == $wkCk || $wk%7 == $wkCk+1) {
			echo '
			<tr><td>' . $dt->format("l, m/d/Y\n") . '</td>
			<td><input type="text" name="AA[]" value="' . $dataAA[$wk] . '"><br>
			<input type="text" name="AB[]" value="' . $dataAB[$wk] . '"><br></td>';
		} else {
			echo '
			<tr><td>' . $dt->format("l, m/d/Y\n") . '</td>
			<td><input id="dis" type="text" name="AA[]" value="' . $dataAA[$wk] . '" readonly><br>
			<input id="dis" type="text" name="AB[]" value="' . $dataAB[$wk] . '" readonly><br></td>';
		}
		
		//finishes input box setup
		echo '
		<td><input type="text" name="BA[]" value="' . $dataBA[$wk] . '"><br>
		<input type="text" name="BB[]" value="' . $dataBB[$wk] . '"><br></td>
		<td><input type="text" name="CA[]" value="' . $dataCA[$wk] . '"><br>
		<input type="text" name="CB[]" value="' . $dataCB[$wk] . '"><br></td>
		</tr>';
		$wk++;
	}
	?>
	</table>
	<br /><input type="submit">
	</form>
</body>
</html>