<html>
<head>
<title>TR37 Tree Lot | Shift Deletion Request</title>
<link rel="icon" href="../favicon.png">
<style>
	th, td {border:1px solid grey; text-align:center;}
	#dis {background-color:#eaeaea;}
	body {text-align:center;font-family:"Arial";}
</style>
</head>

<body>
<h1>TR37 Shift Deletion Request</h1>
<p>If you have changed your mind about a certain shift, you can select all of them which you want to change here. This will automatically delete them from the shift signup.</p>
<table cellspacing="0" cellpadding="5" align="center">
<tr> <!--times-->
	<th></th>
	<th>9am-1pm</th>
	<th>1pm/3pm-5pm</th>
	<th>5pm-9pm</th>
</tr>

<!--enter box fields-->
<form action="deleteAction.php?ts=<?php echo file_get_contents("../timestamp.txt") . PHP_EOL; if(isset($_GET["admin"])) {echo "&admin";}?>" method="post">
<?php

//reads existing signups from file
$dataAA = json_decode(file_get_contents("../data/dataShiftsAA.json"));
$dataAB = json_decode(file_get_contents("../data/dataShiftsAB.json"));
$dataBA = json_decode(file_get_contents("../data/dataShiftsBA.json"));
$dataBB = json_decode(file_get_contents("../data/dataShiftsBB.json"));
$dataCA = json_decode(file_get_contents("../data/dataShiftsCA.json"));
$dataCB = json_decode(file_get_contents("../data/dataShiftsCB.json"));

//read values from reset page
$dates = file("../resetDates.txt");

//setup of date counter
$begin = new DateTime($dates[0]);
$end = new DateTime($dates[1]);
$interval = DateInterval::createFromDateString('1 day');
$period = new DatePeriod($begin, $interval, $end);
$wk=0;
$readAA = $readAB = $readBA = $readBB = $readCA = $readCB = false;

//setup of shift form boxes
foreach ($period as $dt) {
	
	//checks to disable weekend shifts
	$wkCk = (int)$dates[2];
	if ($wk%7 == $wkCk || $wk%7 == $wkCk+1) {
		echo '
		<tr><td>' . $dt->format("l, m/d/Y\n") . '</td>
		<td><input type="checkbox" name="AA[]" value="">' . $dataAA[$wk] . '<br>
		<input type="checkbox" name="AB[]" value="">' . $dataAB[$wk] . '<br></td>';
	} else {
		echo '
		<tr><td>' . $dt->format("l, m/d/Y\n") . '</td>
		<td id="dis"></td>';
	}
	
	//finishes input box setup
	echo '
	<td><input type="checkbox" name="BA[]">' . $dataBA[$wk] . '<br>
	<input type="checkbox" name="BB[]">' . $dataBB[$wk] . '<br></td>
	<td><input type="checkbox" name="CA[]">' . $dataCA[$wk] . '<br>
	<input type="checkbox" name="CB[]">' . $dataCB[$wk] . '<br></td>
	</tr>';
	$wk++;
	
}
?>
</table>
<br /><input type="submit">
</form>
</body>
</html>