<?php
include "../auth.php";
if (authMain() != "admin") {
	die("You do not have the adequate credentials to view this page.");
}
?>

<html>
<head>
	<title>TR37 Tree Lot | Archive Viewer</title>
	<link rel="stylesheet" type="text/css" href="../comment/commentstyle.css">
	<link rel="icon" href="../favicon.png">
	<style>
		th, td {border:1px solid grey; text-align:center;}
		input {margin-bottom:7px;}
		#dis {background-color:#eaeaea;}
		body {text-align:center;font-family:"Arial";}
		.nostyle {text-decoration:none;color:black;}
	</style>
</head>

<body>
	<h2>Archived Shift Viewer</h2>
	<p>Used to view the shift signups from previous years.</p>

	<form action="<?php echo $_SERVER['PHP_SELF']; ?>" method="get">
		Year: <input type="number" id="year" name="year" min="2000" value="<?php if(isset($_GET["year"])) { echo $_GET["year"]; } ?>" required>
		<input type="submit" value="Submit"><br />
		Archived Year(s): <?php echo(implode(", ", glob('*', GLOB_ONLYDIR))); ?>
	</form>

	<?php
	if(isset($_GET["year"])) {
		$year = $_GET["year"];
		echo '<button><a class="nostyle" href="csv.php?year=' . $year . '">Download as CSV</a></button>';
		echo '<h2><i>' . $year . ' Shifts</i></h2><table cellspacing="0" cellpadding="5" align="center">';

		//reads existing signups from file
		if(file_exists($year . "/")) {
			$handle = fopen($year . "/data.json", "r+");
			$data = array(array());
			$linecount = 0;
			while(!feof($handle)){
				$data[$linecount] = json_decode(fgets($handle));
				$linecount++;
			}
		} else {
			die ("There was a problem retrieving the shifts for your specified year");
		}

		//read data values from json
		$dates = json_decode(file_get_contents($year . "/resetDates.json"));
		$prefs = json_decode(file_get_contents($year . '/preferences.json'), true);

		echo '
		<tr> <!--times-->
			<th></th>
			<th>' . $dates[7] . '</th>
			<th>' . $dates[8] . '</th>
			<th>' . $dates[9] . '</th>
		</tr>';

		// hide row C via CSS if pref is set true
		if ($prefs["expand"] !== "true") {
			echo '<link type="text/css" rel="stylesheet" href="../hide-row-c.css">';
		}

		//setup of date counter
		$begin = new DateTime($dates[2] . "-" . $dates[0] . "-" . $dates[1]);
		$end = new DateTime($dates[5] . "-" . $dates[3] . "-" . $dates[4]);
		$interval = DateInterval::createFromDateString('1 day');
		$period = new DatePeriod($begin, $interval, $end);
		$wk = 0;

		//setup of shift form boxes
		foreach ($period as $dt) {
			echo '
			<tr>
				<td>' . $dt->format("l, m/d/Y\n").'</td>
				<td><input type="text" class="COL_A ROW_A" name="AA[]" value="' . $data[0][$wk] . '" id="dis" readonly><br>
				<input type="text" class="COL_A ROW_B" name="AB[]" value="' . $data[1][$wk] . '" id="dis" readonly><br>
				<input type="text" class="COL_A ROW_C" name="AC[]" value="' . $data[2][$wk] . '" id="dis" readonly></td>
				<td><input type="text" class="COL_B ROW_A" name="BA[]" value="' . $data[3][$wk] . '" id="dis" readonly><br>
				<input type="text" class="COL_B ROW_B" name="BB[]" value="' . $data[4][$wk] . '" id="dis" readonly><br>
				<input type="text" class="COL_B ROW_C" name="BC[]" value="' . $data[5][$wk] . '" id="dis" readonly></td>
				<td><input type="text" class="COL_C ROW_A" name="CA[]" value="' . $data[6][$wk] . '" id="dis" readonly><br>
				<input type="text" class="COL_C ROW_B" name="CB[]" value="' . $data[7][$wk] . '" id="dis" readonly><br>
				<input type="text" class="COL_C ROW_C" name="CC[]" value="' . $data[8][$wk] . '" id="dis" readonly></td>
			</tr>';
			$wk++;
		}

		echo '
		</table>
		<h2><i>' . $year . ' Comments</i></h2>';

		$handle = fopen($year . "/allcomments.json", "r+");
		$data = array(array());
		$linecount = 0;
		while(!feof($handle)){
			$data[$linecount] = json_decode(fgets($handle));
			$linecount++;
		}

		for($i = 0;$i < $linecount;$i++) {
			if($data[$i] != "") {
				echo '<a id="nostyle" href="../comment/view.php?line=' . $i . '&year=' . $year . '&src=archive"><div id="title">' . trim_text($data[$i][4] . ' - ' . $data[$i][0],70);
				echo '</div><div id="date">' . $data[$i][1] . ' | ' . $data[$i][2];
				echo '</div><br><div id="content">' . trim_text($data[$i][3],500);
				echo "</a><hr /></div>";
			}
		}
	}
	function trim_text($input, $length) {
	  if (strlen($input) <= $length)
	      return $input;

	  $last_space = strrpos(substr($input, 0, $length), ' ');
	  return substr($input, 0, $last_space) . '...';
	}
	?>
</body>
</html>
