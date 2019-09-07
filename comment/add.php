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
	<!--<a><b>Your Scout's Name:</a></b><br />
	<input type="text" style="width:300px;" name="name" required>
	<br /><br />-->
	
	<a><b>Select a date:</b></a>
	<table><tr>
	<?php
	$dates = json_decode(file_get_contents("../resetDates.json"));
	$begin = new DateTime($dates[2] . "-" . $dates[0] . "-" . $dates[1]);
	$end = new DateTime($dates[5] . "-" . $dates[3] . "-" . $dates[4]);
	$interval = DateInterval::createFromDateString('1 day');
	$period = new DatePeriod($begin, $interval, $end);
	$wk = 1;
	$matchwk = -1;
	
	foreach ($period as $dt) {
		$loc = $_SERVER['PHP_SELF'] . '?dateSelected=' . $dt->format("m-d-Y");
		$preselect = isset($_GET['dateSelected']) && $dt->format("m-d-Y") === $_GET['dateSelected'] ? 'checked="checked"' : '';
		echo '<td><input type="radio" name="date" onclick="javascript:window.location.href=\'' . $loc . '\'" value=' . $dt->format("m-d-Y") . ' required ' . $preselect . '></td><td>' . $dt->format("l, m/d/Y") . '</td>';
		if($wk%3 == 0) {
			echo '</tr><tr>';
		}
		if(isset($_GET['dateSelected']) && $_GET['dateSelected'] === $dt->format("m-d-Y")) {
			$matchwk = $wk;
		}
		$wk++;
	}
	echo '</table>';
	
	if(isset($_GET['dateSelected'])) {
		//reads shift data from file
		$handle = fopen("../data.json", "r+");
		$data = array(array());
		$linecount = 0;
		while(!feof($handle)){
			$data[$linecount] = json_decode(fgets($handle));
			$linecount++;
		}
		
		echo '<br /><a><b>Select a shift</b></a><br />';
		$shifts_preset = false;
		for ($stime = 0;$stime < sizeof($data);$stime++) {
			if(!empty($data[$stime][$matchwk - 1])) {
				switch($stime) {
					case 0: case 1: echo '<input type="radio" name="time" value="9am-1pm" required><a>' . $data[$stime][$matchwk - 1] . ' | 9am-1pm (Weekends Only)</a><br />'; break;
					case 2: case 3: echo '<input type="radio" name="time" value="1pm/3pm-5pm" required><a>' . $data[$stime][$matchwk - 1] . ' | 1pm/3pm-5pm (Weekends/Weekdays)</a><br />'; break;
					case 4: case 5: echo '<input type="radio" name="time" value="5pm-9pm" required><a>' . $data[$stime][$matchwk - 1] . ' | 5pm-9pm</a><br />'; break;
					default: break;
				}
				echo '<input type="text" name="name" style="display:none;" value="' . $data[$stime][$matchwk - 1] . '">';
				$shifts_preset = true;
			}
		}
		
		if(!$shifts_preset) { 
			echo 'No shifts found, please select another date';
		} else {
			echo '</table><br />
			
			<a><b>Title your comment</a></b><br />
			<input type="text" style="width:300px;" name="title" required>
			<br /><br />
			
			<a><b>Write your comment</b></a><br />
			<textarea name="comment" cols="70" rows="15" required></textarea>
			<br /><br /><input type="submit">';
		}
	}
	?>
</form>
</body>
</html>


