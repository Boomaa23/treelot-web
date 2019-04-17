<?php 
if (!isset($_GET["admin"])) {
	include "auth.php";
	if ((authMain() != "admin") && (authMain() != "user")) {
		die("You do not have the adequate credentials to view this page.");
	}	
	if (authMain() == "admin") {
		header("refresh:0;url=adminAuth.php?signup");
	}
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
	<h1>Troop 37 Tree Lot Signup</h1>
	<div id="par">
	<p>This is the website to sign up for tree lot shifts for Troop 37. Normal weekend hours are 9am-9pm in three shifts of four hours (9am-1pm, 1pm-5pm, 5pm-9pm). On the weekdays, the tree lot is only open from 3pm to 9pm, so the afternoon shift is reduced to 3pm-5pm and there is no morning shift. There is space for two scouts (and their parents) to sign up for each shift. Each scout must sign up for at least 16 hours worth of shifts.</p>
	<p><b> Do not delete filled in shifts from other scouts.</b> Please contact the website administrator by email at <a href="mailto:ncograin@gmail.com">ncograin@gmail.com</a> if you have any issues with signups. Thank you!</p>
	<!--<a href="delete/index.php">Request a shift deletion</a>-->
	<button><a href="comment/index.php" id="nostyle"><b>View or add shift comments</b></a></button>
	<button><a href="delete/index.php" id="nostyle"><b>Request a shift deletion</b></a></button>
	<p>Comments for today: <?php echo(date("n/d/Y",time())); ?></p>
	<?php 
		$files = glob('comment/data/*.txt', GLOB_BRACE);
		foreach($files as $file) {
			$fileArray = file($file);
			$dir_file = str_replace("data/", "", $file);
			$dir_file = str_replace(".txt", "", $dir_file);
			if(trim($fileArray[1]) == date("n/d/Y",time())) {
				echo '<b>' . $fileArray[3] . ' - ' . $fileArray[0] . ': </b>' . $fileArray[2] . '<br>';
			}
		}
	?>
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
	<form action="action.php?ts=<?php if(file_exists("timestamp.txt")) { echo file_get_contents("timestamp.txt") . PHP_EOL; } if(isset($_GET["admin"])) {echo "&admin";}?>" method="post">
	<?php
	//logs ip and time of access
	/*
	$ip = $_SERVER['REMOTE_ADDR'];
	$dateTime = date('m/d/Y G:i:s');
	$date = $dateTime . " - " . $ip ;
	file_put_contents("iplog.txt", $date . PHP_EOL, FILE_APPEND);
	*/
	
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
	
	//read values from reset page
	$dates = json_decode(file_get_contents("resetDates.json"));
	
	//setup of date counter
	$begin = new DateTime($dates[2] . "-" . $dates[0] . "-" . $dates[1]);
	$end = new DateTime($dates[5] . "-" . $dates[3] . "-" . $dates[4]);
	$interval = DateInterval::createFromDateString('1 day');
	$period = new DatePeriod($begin, $interval, $end);
	$wk = 0;

	//setup of shift form boxes
	foreach ($period as $dt) {
		//prevents modification of already filled slots
		$read = array(array());
		for($i = 0;$i <= 5;$i++) {
			$read[$i][$wk] = !empty($data[$i][$wk]) ? 'id="dis" readonly' : "";
		}

		//checks to disable weekday shifts
		$wkCk = (int)$dates[6];
		if ($wk%7 == $wkCk || $wk%7 == $wkCk+1) {
			echo '
			<tr><td>' . $dt->format("l, m/d/Y\n") . '</td>
			<td><input type="text" name="AA[]" value="' . $data[0][$wk] . '" ' . $read[0][$wk] . '><br>
			<input type="text" name="AB[]" value="' . $data[1][$wk] . '" ' . $read[1][$wk] . '><br></td>';
		} else {
			echo '
			<tr><td>' . $dt->format("l, m/d/Y\n") . '</td>
			<td><input id="dis" type="text" name="AA[]" value="' . $data[0][$wk] . '" readonly><br>
			<input id="dis" type="text" name="AB[]" value="' . $data[1][$wk] . '" readonly><br></td>';
		}
		
		//finishes input box setup
		echo '
		<td><input type="text" name="BA[]" value="' . $data[2][$wk] . '" ' . $read[2][$wk] . '><br>
		<input type="text" name="BB[]" value="' . $data[3][$wk] . '" ' . $read[3][$wk] . '><br></td>
		<td><input type="text" name="CA[]" value="' . $data[4][$wk] . '" ' . $read[4][$wk] . '><br>
		<input type="text" name="CB[]" value="' . $data[5][$wk] . '" ' . $read[5][$wk] . '><br></td>
		</tr>';
		$wk++;
	}
	?>
	</table>
	<br /><input type="submit">
	</form>
</body>
</html>