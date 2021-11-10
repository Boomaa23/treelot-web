<?php
session_start();
if (!isset($_GET["admin"])) {
	include "auth.php";
	if ((authMain() != "admin") && (authMain() != "user")) {
		die("You do not have the adequate credentials to view this page.");
	}
	if (authMain() == "admin") {
		header("refresh:0;url=adminAuth.php?signup");
	}
}

if(!isset($_SESSION['filled'])) {
	$_SESSION['filled'] = array("");
}

if(json_decode(file_get_contents('preferences.json'), true)["maintenance"] === "true") {
	die("This site is currently down for maintenance and should be back up shortly.");
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
	#nostyle {text-decoration:none;color:black;}
</style>
</head>
<body>
	<?php include("footer.html"); ?>
	<h1>Troop 37 Tree Lot Signup</h1>
	<div id="par">
	<p>This is the website to sign up for tree lot shifts for Troop 37. Normal weekend hours are 9am-9pm in three shifts of four hours (9am-1pm, 1pm-5pm, 5pm-9pm). On the weekdays, the tree lot is only open from 3pm to 9pm, split into two shifts of three hours each (3pm-6pm, 6pm-9pm) with no morning shift. There is space for two to three scouts scouts (and their parents) to sign up for each shift.</p>
	<p><b> Do not delete filled in shifts from other scouts.</b> Please contact the website administrator by email at <a href="mailto:treelot_web@sbtroop37.mytroop.us">treelot_web@sbtroop37.mytroop.us</a> if you have any issues with signups. Shift deletions can be accomodated by talking to the troop webmaster, scoutmaster, or tree lot manager. Thank you!</p>
  <?php
    $prefs = json_decode(file_get_contents('preferences.json'), true);
    $deleteText = $prefs["requests"] === "true" ? 'Request a shift deletion' : 'Revoke a shift signup';
    if($prefs["comments"] === "true") {
      echo '<button><a href="comment/index.php" id="nostyle"><b>View or add shift comments</b></a></button>';
    }
		echo ' <button><a href="delete/index.php" id="nostyle"><b>' . $deleteText . '</b></a></button>';
		echo ' <button><a href="archive/csv.php?src=root&year=' . json_decode(file_get_contents("resetDates.json"))[2] . '" id="nostyle"><b>Download shifts as CSV</b></a></button>';
		$dateStart = (new DateTime("now", new DateTimeZone("America/Los_Angeles")));
		$dateEnd = (new DateTime("now", new DateTimeZone("America/Los_Angeles")))->add(new DateInterval("P7D"));
		$interval = DateInterval::createFromDateString('1 day');
		$period = new DatePeriod($dateStart, $interval, $dateEnd);

    if($prefs["comments"] === "true") {
  		echo '<p style="margin-bottom:0;">Comments for the next seven days: ' . $dateStart->format('m/d/Y') . ' - ' . $dateEnd->format('m/d/Y') . '</p>';
  		$handle = fopen("comment/allcomments.json", "r+");
  		$file = array(array());
  		for($l = 0;!feof($handle);$l++) {
  			$file[$l] = json_decode(fgets($handle));
  		}

  		$disp = 0;
  		for($i = 0;$i < sizeof($file) && !($disp >= 6);$i++) {
  			if($disp % 3 == 0 && !is_null($file[$i])) {
  				echo '<br />';
  			}

  			foreach($period as $dt) {
  				if(!is_null($file[$i]) && $file[$i][1] == $dt->format('m-d-Y')) {
  					$disp++;
  					echo '<a href="comment/view.php?line=' . $i . '&src=main">' . $file[$i][4] . ' - ' . $file[$i][0] . ' (' . $file[$i][2] . ')</a> &nbsp&nbsp';
  				}
  			}
  		}
  		if($disp > 6) {
  			echo '<br /><a href="comment/index.php">[SEE MORE]</a>';
  		}
    }
	?>
	</div>
	<br />
	<table cellspacing="0" cellpadding="5" align="center">
	<!--enter box fields-->
	<form action="action.php?ts=<?php if(file_exists("timestamp.txt")) { echo file_get_contents("timestamp.txt") . PHP_EOL; } if(isset($_GET["admin"])) {echo "&admin";}?>" method="post">
	<?php

	//makes a data file if none exists
	if(!file_exists("data.json")) {
		copy('basedata.json', 'data.json');
	}

	//reads existing signups from file
	$handle = fopen("data.json", "r+");
	$data = array(array());
	$linecount = 0;
	while(!feof($handle)){
		$data[$linecount] = json_decode(fgets($handle));
		$linecount++;
	}

	//reads all other data from files
	$prefs = json_decode(file_get_contents('preferences.json'), true);
	$dates = json_decode(file_get_contents("resetDates.json"));

	echo '
	<tr> <!--times-->
		<th></th>
		<th>' . $dates[7] . '</th>
		<th>' . $dates[8] . '</th>
		<th>' . $dates[9] . '</th>
	</tr>';

	// hide row C via CSS if pref is set
	if ($prefs["expand"] === "never") {
		echo '<link type="text/css" rel="stylesheet" href="hide-row-c.css">';
	} else if ($prefs["expand"] === "weekends") {
		echo '<link type="text/css" rel="stylesheet" href="hrc-weekdays.css">';
	}

	//setup of date counter
	$begin = new DateTime($dates[2] . "-" . $dates[0] . "-" . $dates[1]);
	$end = new DateTime($dates[5] . "-" . $dates[3] . "-" . ($dates[4] + 1));
	$interval = DateInterval::createFromDateString('1 day');
	$period = new DatePeriod($begin, $interval, $end);
	$day = 0;

	//setup of shift form boxes
	foreach ($period as $dt) {
		//prevents modification of already filled slots
		$read = array(array());
		for($i = 0;$i < 9;$i++) {
			 $read[$i][$day] = !empty($data[$i][$day]) && !array_search($i . '-' . $day, $_SESSION["filled"]) ? 'id="dis" readonly' : "";
		}

		//checks to disable weekday shifts
		$dayCk = (7 - (int)$dates[6]) % 7;
		$isWeekday = "";
		if (($day%7 !== $dayCk && ($day-1)%7 !== $dayCk) || ($day === 0 && $prefs["setup"] === "false")) {
			if ($day%7 !== $dayCk && ($day-1)%7 !== $dayCk) {
				$isWeekday = "WEEKDAY";
			}
			echo '
			<tr><td>' . $dt->format("l, m/d/Y\n") . '</td>
			<td><input id="dis" type="text" class="COL_A ROW_A ' . $isWeekday . '" name="AA[]" value="' . $data[0][$day] . '" readonly><br>
			<input id="dis" type="text" class="COL_A ROW_B ' . $isWeekday . '" name="AB[]" value="' . $data[1][$day] . '" readonly><br>
			<input id="dis" type="text" class="COL_A ROW_C ' . $isWeekday . '" name="AC[]" value="' . $data[2][$day] . '" readonly></td>';
		} else {
			echo '
			<tr><td>' . $dt->format("l, m/d/Y\n") . '</td>
			<td><input type="text" class="COL_A ROW_A" name="AA[]" value="' . $data[0][$day] . '" ' . $read[0][$day] . '><br>
			<input type="text" class="COL_A ROW_B" name="AB[]" value="' . $data[1][$day] . '" ' . $read[1][$day] . '><br>
			<input type="text" class="COL_A ROW_C" name="AC[]" value="' . $data[2][$day] . '" ' . $read[2][$day] . '></td>';
		}

		//finishes input box setup
		echo '
		<td><input type="text" class="COL_B ROW_A ' . $isWeekday . '" name="BA[]" value="' . $data[3][$day] . '" ' . $read[3][$day] . '><br>
		<input type="text" class="COL_B ROW_B ' . $isWeekday . '" name="BB[]" value="' . $data[4][$day] . '" ' . $read[4][$day] . '><br>
		<input type="text" class="COL_B ROW_C ' . $isWeekday . '" name="BC[]" value="' . $data[5][$day] . '" ' . $read[5][$day] . '></td>
		<td><input type="text" class="COL_C ROW_A ' . $isWeekday . '" name="CA[]" value="' . $data[6][$day] . '" ' . $read[6][$day] . '><br>
		<input type="text" class="COL_C ROW_B ' . $isWeekday . '" name="CB[]" value="' . $data[7][$day] . '" ' . $read[7][$day] . '><br>
		<input type="text" class="COL_C ROW_C ' . $isWeekday . '" name="CC[]" value="' . $data[8][$day] . '" ' . $read[8][$day] . '></td>
		</tr>';
		$day++;
	}
	?>
	</table>
	<br /><input type="submit"><br /><br />
	</form>
</body>
</html>
