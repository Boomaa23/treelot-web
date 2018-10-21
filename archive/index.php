<?php 
include "../auth.php";
if (authMain() != "admin") {
	die("You do not have the adequate credentials to view this page.");
}
?>

<html>
<head>
<title>TR37 Tree Lot | Archive Viewer</title>
<link rel="icon" href="../favicon.png">
<style>
	th, td {border:1px solid grey; text-align:center;}
	#dis {background-color:#eaeaea;}
	body {text-align:center;font-family:"Arial";}
</style>
</head>

<body>
<h2>TR37 Past Shift Viewer</h2>
<p>Used to view the shift signups from previous years.</p>

<form action="<?php echo $_SERVER['PHP_SELF']; ?>" method="post">
	<input type="number" id="year" name="year" min="2000" required></td>
	<input type="submit" value="Submit">
</form>

<form>
<table cellspacing="0" cellpadding="5" align="center">
<?php
if(isset($_POST["year"])) {
//reads existing signups from file
if(file_exists($_POST["year"]. "/")) {
	$dataAA = json_decode(file_get_contents($_POST["year"].'/dataShiftsAA.json'));
	$dataAB = json_decode(file_get_contents($_POST["year"].'/dataShiftsAB.json'));
	$dataBA = json_decode(file_get_contents($_POST["year"].'/dataShiftsBA.json'));
	$dataBB = json_decode(file_get_contents($_POST["year"].'/dataShiftsBB.json'));
	$dataCA = json_decode(file_get_contents($_POST["year"].'/dataShiftsCA.json'));
	$dataCB = json_decode(file_get_contents($_POST["year"].'/dataShiftsCB.json'));
} else {
	die ("There was a problem retrieving the shifts for your specified year");
}

echo '
<tr> <!--times-->
	<th></th>
	<th>9am-1pm</th>
	<th>1pm/3pm-5pm</th>
	<th>5pm-9pm</th>
</tr>';

//read values from reset page
$dates = file($_POST["year"].'/resetDates.txt');

//setup of date counter
$begin = new DateTime($dates[0]);
$end = new DateTime($dates[1]);
$interval = DateInterval::createFromDateString('1 day');
$period = new DatePeriod($begin, $interval, $end);
$wk=0;

//setup of shift form boxes
foreach ($period as $dt) {
	echo '
	<tr>
	<td>' . $dt->format("l, m/d/Y\n").'</td>
	<td><input type="text" name="AA[]" value="' . $dataAA[$wk] . '" id="dis" readonly><br>
	<input type="text" name="AB[]" value="' . $dataAB[$wk] . '" id="dis" readonly><br></td>
	<td><input type="text" name="BA[]" value="' . $dataBA[$wk] . '" id="dis" readonly><br>
	<input type="text" name="BB[]" value="' . $dataBB[$wk] . '" id="dis" readonly><br></td>
	<td><input type="text" name="CA[]" value="' . $dataCA[$wk] . '" id="dis" readonly><br>
	<input type="text" name="CB[]" value="' . $dataCB[$wk] . '" id="dis" readonly><br></td>
	</tr>';
	$wk++;
}
}
?>
</table>
</form>
<br />
</body>
</html>


