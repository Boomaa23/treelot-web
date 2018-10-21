<?php 
include "auth.php";
if (authMain() != "admin") {
	die("You do not have the adequate credentials to view this page.");
}
?>

<html>
<head>
<title>TR37 Tree Lot | Date Reset</title>
<link rel="icon" href="favicon.png">
<style>
	th, td {border:1px solid grey; text-align:center;}
	body {text-align:center;font-family:"Arial";}
</style>
</head>

<body>
<form action="<?php echo $_SERVER['PHP_SELF']; ?>" method="post">
<h2>TR37 Shift Reset & Backup</h2>
<p>Used annually to reset everything after each year's Tree Lot is over.<br /> Automatically backs up old shifts for viewing.</p>

<table align="center">
<tr>
	<td></td>
	<td>Year</td>
	<td>Month</td>
	<td>Day</td>
</tr>
<tr>
	<td>Starting Dates</td>
	<td><input type="number" id="startYear" name="startYear" size="20" min="2000" value="<?php $dtsin = file("resetDates.txt"); echo (int)$dtsin[4]; ?>" required></td>
	<td><input type="number" id="startMonth" name="startMonth" size="20" min="1" max="12" value="<?php $dtsin = file("resetDates.txt"); echo (int)$dtsin[5]; ?>" required></td>
	<td><input type="number" id="startDay" name="startDay" size="20" min="1" max="31" value="<?php $dtsin = file("resetDates.txt"); echo (int)$dtsin[6]; ?>" required></td>
</tr>
<tr>
	<td>Ending Dates</td>
	<td><input type="number" id="endYear" name="endYear" min="2000" value="<?php $dtsin = file("resetDates.txt"); echo (int)$dtsin[7]; ?>" required></td>
	<td><input type="number" id="endMonth" name="endMonth" min="1" max="12" value="<?php $dtsin = file("resetDates.txt"); echo (int)$dtsin[8]; ?>" required></td>
	<td><input type="number" id="endDay" name="endDay" min="1" max="31" value="<?php $dtsin = file("resetDates.txt"); echo (int)$dtsin[9]; ?>" required></td>
</tr>
</table><br />
	Offset (# of days the starting day is from a Saturday): 
	<input type="number" id="off" name="off" min="0" max="6" value="<?php $dtsin = file("resetDates.txt"); echo (int)$dtsin[2]; ?>"><br /><br />
	<input type="submit" value="Submit" onclick="return confirm('Are you sure you want reset everything?')">
</form>
</body>
</html>

<?php
if(isset($_POST["startYear"]) && isset($_POST["off"])) {
	//init for inputs & archive
	$start = $_POST["startYear"] . "-" . $_POST["startMonth"] . "-" . $_POST["startDay"];
	$end = PHP_EOL . $_POST["endYear"] . "-" . $_POST["endMonth"] . "-" . ($_POST["endDay"] + 1);
	$off = $_POST["off"];
	$dtsin[] = file("resetDates.txt"); 
	if(!file_exists('archive/'.$_POST["startYear"].'/')) {
		mkdir('archive/'.$_POST["startYear"]);
	}
	
	//moves schedules to archive
	copy('data/dataShiftsAA.json', 'archive/'.(int)$dtsin[4].'/dataShiftsAA.json');
	copy('data/dataShiftsAB.json', 'archive/'.(int)$dtsin[4].'/dataShiftsAB.json');
	copy('data/dataShiftsBA.json', 'archive/'.(int)$dtsin[4].'/dataShiftsBA.json');
	copy('data/dataShiftsBB.json', 'archive/'.(int)$dtsin[4].'/dataShiftsBB.json');
	copy('data/dataShiftsCA.json', 'archive/'.(int)$dtsin[4].'/dataShiftsCA.json');
	copy('data/dataShiftsCB.json', 'archive/'.(int)$dtsin[4].'/dataShiftsCB.json');
	copy('resetDates.txt', 'archive/'.(int)$dtsin[4].'/resetDates.txt');
	
	//clears dates file
	ftruncate(fopen("resetDates.txt", "r+"), 0);
	
	//writes main portion to reset file
	file_put_contents("resetDates.txt", $start, FILE_APPEND);
	file_put_contents("resetDates.txt", $end . PHP_EOL, FILE_APPEND);
	file_put_contents("resetDates.txt", $off, FILE_APPEND);
	
	//writes check values to reset file
	file_put_contents("resetDates.txt", PHP_EOL . PHP_EOL . $_POST["startYear"] . PHP_EOL, FILE_APPEND);
	file_put_contents("resetDates.txt", $_POST["startMonth"] . PHP_EOL, FILE_APPEND);
	file_put_contents("resetDates.txt", $_POST["startDay"] . PHP_EOL, FILE_APPEND);
	file_put_contents("resetDates.txt", $_POST["endYear"] . PHP_EOL, FILE_APPEND);
	file_put_contents("resetDates.txt", $_POST["endMonth"] . PHP_EOL, FILE_APPEND);
	file_put_contents("resetDates.txt", $_POST["endDay"] . PHP_EOL, FILE_APPEND);
	
	//removes old shift files
	ftruncate(fopen("data/dataShiftsAA.json", "r+"), 0);
	ftruncate(fopen("data/dataShiftsAB.json", "r+"), 0);
	ftruncate(fopen("data/dataShiftsBA.json", "r+"), 0);
	ftruncate(fopen("data/dataShiftsBB.json", "r+"), 0);
	ftruncate(fopen("data/dataShiftsCA.json", "r+"), 0);
	ftruncate(fopen("data/dataShiftsCB.json", "r+"), 0);
	
	//clears & resets page/timestamp
	
	ftruncate(fopen("timestamp.txt", "r+"), 0);
	header("refresh:0");
}
?>