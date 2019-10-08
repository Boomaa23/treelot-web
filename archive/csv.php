<?php 
$year = $_GET["year"];
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

//read values from reset page
$dates = json_decode(file_get_contents($year . "/resetDates.json"));

//setup of date counter
$begin = new DateTime($dates[2] . "-" . $dates[0] . "-" . $dates[1]);
$end = new DateTime($dates[5] . "-" . $dates[3] . "-" . $dates[4]);
$interval = DateInterval::createFromDateString('1 day');
$period = new DatePeriod($begin, $interval, $end);
$wk = 0;

$filename = $year . '_treelot-shift-report_' . date("c") . '.csv';
$content = "Date,9-1 #1,9-1 #2,1/3-5 #1, 1/3-5 #2, 5-9 #1, 5-9 #2\n";
$j = 0;
foreach($period as $dt) {
  $content .= ($dt->format("m/d")) . ',';
	for($i = 0;$i <= 5;$i++) {
		$content .= $data[$i][$j];
		if($i !== 5) {
			$content .= ',';
		}
	}
	$content .= "\n";
}

header("Content-Type: text/plain");
header('Content-Disposition: attachment; filename="' . $filename . '"');
header("Content-Length: " . strlen($content));
$fp = fopen('php://output', 'w');
fwrite($fp, $content);
fclose($fp);
?>