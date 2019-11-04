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
<form action="<?php echo $_SERVER['PHP_SELF']; ?>?success" method="post">
<h2>Shift Reset & Backup</h2>
<p>Used annually to reset everything after each year's Tree Lot is over.<br /> Automatically backs up old shifts for viewing.</p>
<?php $dtsin = json_decode(file_get_contents("resetDates.json")); ?>

<table align="center">
<tr>
	<td></td>
	<td>Month</td>
	<td>Day</td>
	<td>Year</td>
</tr>
<tr>
	<td>Starting Date</td>
	<td><input type="number" id="startMonth" name="startMonth" size="20" min="1" max="12" value="<?php echo (int)$dtsin[0]; ?>" required></td>
	<td><input type="number" id="startDay" name="startDay" size="20" min="1" max="31" value="<?php echo (int)$dtsin[1]; ?>" required></td>
	<td><input type="number" id="startYear" name="startYear" size="20" min="2000" value="<?php echo (int)$dtsin[2]; ?>" required></td>
</tr>
<tr>
	<td>Ending Date</td>
	<td><input type="number" id="endMonth" name="endMonth" size="20" min="1" max="12" value="<?php echo (int)$dtsin[3]; ?>" required></td>
	<td><input type="number" id="endDay" name="endDay" size="20" min="1" max="31" value="<?php echo (int)$dtsin[4]; ?>" required></td>
	<td><input type="number" id="endYear" name="endYear" size="20" min="2000" value="<?php echo (int)$dtsin[5]; ?>" required></td>
</tr>
</table><br />
	<a>Offset (# of days the starting day is from a Saturday): </a>
	<input type="number" id="off" name="off" min="0" max="6" value="<?php echo (int)$dtsin[6]; ?>"><br />
	<a>Expand current shifts - no reset <i>(default: unchecked): </i></a>
	<input type="checkbox" id="expand" name="expand"><br />
	<a>Backup only - no reset <i>(default: unchecked): </i></a>
	<input type="checkbox" id="backup" name="backup"><br /><br />
	<input type="submit" value="Submit" onclick="return confirm('Are you sure you want reset all the data from this year?')">
</form>
</body>
</html>

<?php
//init for inputs & archive
$dtsin[] = json_decode(file_get_contents("resetDates.json")); 
$year = (int)$dtsin[2];

if(isset($_GET["success"])) {
	echo 'Success! Archived copy viewable <a href="archive/index.php?year=' . $year . '">here</a>';
}

if(isset($_POST["startYear"]) && isset($_POST["off"])) {
	//creates new archive folder
	if(!file_exists('archive/' . $year . '/')) {
		mkdir('archive/' . $year);
	}
	
	//moves schedules to archive
	copy('data.json', 'archive/' . $year . '/data.json');
	copy('resetDates.json', 'archive/' . $year . '/resetDates.json');
	copy('comment/allcomments.json', 'archive/' . $year . '/allcomments.json');
	copy('delete/removelog.json', 'archive/' . $year . '/removelog.json');
	
	if(isset($_POST["backup"])) {
		header("refresh:0");
		exit();
	}
	
	//puts dates into data file
	$resetData = array($_POST["startMonth"], $_POST["startDay"], $_POST["startYear"], $_POST["endMonth"], $_POST["endDay"], $_POST["endYear"], $_POST["off"]);
	ftruncate(fopen("resetDates.json", "r+"), 0);
	file_put_contents("resetDates.json", json_encode($resetData), FILE_APPEND);
	
	//calculate number of days in shift range
	$start = strtotime($resetData[2] . "-" . $resetData[0] . "-" . $resetData[1]);
	$end = strtotime($resetData[5] . "-" . $resetData[3] . "-" . $resetData[4]);
	$days = round(($end - $start) / (60 * 60 * 24));
	
	//clears old files
	$expanded = "";
	if(!isset($_POST["expand"])) {
		ftruncate(fopen("data.json", "r+"), 0);
		ftruncate(fopen("comment/allcomments.json", "r+"), 0);
		ftruncate(fopen("delete/removelog.json", "r+"), 0);
		//ftruncate(fopen("shiftipmap.json", "r+"), 0);
	} else {
		//reads existing signups from file
		$handle = fopen("data.json", "r+");
		$data = array(array());
		$linecount = 0;
		while(!feof($handle)){
			$data[$linecount] = json_decode(fgets($handle));
			$linecount++;
		}
		
		//merges new array of blanks (sized to fit) with old data
		for($i = 0;$i < sizeof($data);$i++) {
			$expanded .= json_encode(array_merge($data[$i], array_fill(0, ($days - sizeof($data[0])), "")));
			if($i !== sizeof($data) - 1) {
				$expanded .= PHP_EOL;
			}
		}
	}
	
	$placeholder = array_fill(0, abs($days), "");
	// DEPRECATED by session-based shift rewrites/deletion
	/*if(!isset($_POST["expand"])) {
		//put placeholders in shiftipmap
		$placeholder_arr = array_fill(0, 6, $placeholder);
		file_put_contents('shiftipmap.json', json_encode($placeholder_arr, JSON_PRETTY_PRINT));
	} else {
		//expand ip storage to match shift length
		$o_ips = json_decode(file_get_contents('shiftipmap.json'));
		$oldsize = sizeof($o_ips[0]);
		for($i = 0;$i < sizeof($o_ips);$i++) {
			$o_ips[$i] = array_merge($o_ips[$i], array_fill(0, ($days - $oldsize + 1), ""));
		}
		file_put_contents('shiftipmap.json', json_encode($o_ips, JSON_PRETTY_PRINT));
	}*/
	
	//dynamically create basedata based on # of days
	file_put_contents('basedata.json', str_repeat(json_encode($placeholder) . PHP_EOL, 5) . json_encode($placeholder));
	
	//copies in blank shift files or expanded shift data
	if(!isset($_POST["expand"])) {
		copy('basedata.json', 'data.json');
	} else {
		file_put_contents('data.json', $expanded);
	}
	
	//clears & resets page/timestamp
	ftruncate(fopen("timestamp.txt", "r+"), 0);
	header("refresh:0");
}
?>