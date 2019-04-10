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
$handle = fopen("../data.json", "r+");
$data = array(array());
$linecount = 0;
while(!feof($handle)){
	$data[$linecount] = json_decode(fgets($handle));
	$linecount++;
}

//read values from reset page
$dates = file("../resetDates.txt");

//setup of date counter
$begin = new DateTime($dates[0]);
$end = new DateTime($dates[1]);
$interval = DateInterval::createFromDateString('1 day');
$period = new DatePeriod($begin, $interval, $end);
$wk=0;

//setup of shift form boxes
foreach ($period as $dt) {
	//prevents deletion of empty slots
	$read = array(array());
	for($i = 0;$i <= 5;$i++) {
		$read[$i][$wk] = empty($data[$i][$wk]) ? "disabled" : "";
	}
	
	//checks to disable weekend shifts
	$wkCk = (int)$dates[2];
	if ($wk%7 == $wkCk || $wk%7 == $wkCk+1) {
		echo '
		<tr><td>' . $dt->format("l, m/d/Y\n") . '</td>
		<td><input type="checkbox" name="AA[]"  value="' . $data[0][$wk] . '" ' . $read[0][$wk] . '>' . $data[0][$wk] . '<br>
		<input type="checkbox" name="AB[]" value="' . $data[1][$wk] . '" ' . $read[1][$wk] . '>' . $data[1][$wk] . '<br></td>';
	} else {
		echo '
		<tr><td>' . $dt->format("l, m/d/Y\n") . '</td>
		<td id="dis"></td>';
	}
	
	//finishes input box setup
	echo '
	<td><input type="checkbox" name="BA[]" value="' . $data[2][$wk] . '" ' . $read[2][$wk] . '>' . $data[2][$wk] . '<br>
	<input type="checkbox" name="BB[]" value="' . $data[3][$wk] . '" ' . $read[3][$wk] . '>' . $data[3][$wk] . '<br></td>
	<td><input type="checkbox" name="CA[]" value="' . $data[4][$wk] . '" ' . $read[4][$wk] . '>' . $data[4][$wk] . '<br>
	<input type="checkbox" name="CB[]" value="' . $data[5][$wk] . '" ' . $read[5][$wk] . '>' . $data[5][$wk] . '<br></td>
	</tr>';
	$wk++;
	
}
?>
</table>
<br /><input type="submit">
</form>
</body>
</html>