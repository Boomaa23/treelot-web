<?php 
include "../auth.php";
if ((authMain() != "admin") && (authMain() != "user")) {
	die("You do not have the adequate credentials to view this page.");
}	
if(!isset($_GET["year"])) {
	die("Invalid or no year provided");
}

$year = $_GET["year"];
//reads existing signups from file
if(file_exists($year . "/") || file_exists("../data.json")) {
	if(isset($_GET["src"]) && $_GET["src"] === "root") {
		$handle = fopen("../data.json", "r+");
	} else {
		$handle = fopen($year . "/data.json", "r+");
	}
	$data = array(array());
	$linecount = 0;
	while(!feof($handle)){
		$data[$linecount] = json_decode(fgets($handle));
		$linecount++;
	}
} else {
	die ("There was a problem retrieving the shifts for your specified year");
}

//read values from reset page
$dateSrc = isset($_GET["src"]) ? "../resetDates.json" : $year . "/resetDates.json";
$dates = json_decode(file_get_contents($dateSrc));

//setup of date counter
$begin = new DateTime($dates[2] . "-" . $dates[0] . "-" . $dates[1]);
$end = new DateTime($dates[5] . "-" . $dates[3] . "-" . ($dates[4] + 1));
$interval = DateInterval::createFromDateString('1 day');
$period = new DatePeriod($begin, $interval, $end);
$wk = 0;

$filename = $year . '_treelot-shift-report_' . date("c") . '.csv';
$content = ", 9-1 #1, 9-1 #2, 9-1 #3, 1/3-5 #1, 1/3-5 #2, 1/3-5 #3, 5-9 #1, 5-9 #2, 5-9 #3\n";
$j = 0;
foreach($period as $dt) {
  $content .= ($dt->format("m/d")) . ',';
	for($i = 0;$i <= 8;$i++) {
		$content .= $data[$i][$j];
		if($i !== 8) {
			$content .= ',';
		}
	}
	$j++;
	$content .= "\n";
}

header("Content-Type: text/plain");
header('Content-Disposition: attachment; filename="' . $filename . '"');
header("Content-Length: " . strlen($content));
$fp = fopen('php://output', 'w');
fwrite($fp, $content);
fclose($fp);
?>