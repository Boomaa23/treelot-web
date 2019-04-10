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
$year = $_POST["year"];
//reads existing signups from file
if(file_exists($year. "/")) {
	$handle = fopen($year."/data.json", "r+");
	$data = array(array());
	$linecount = 0;
	while(!feof($handle)){
		$data[$linecount] = json_decode(fgets($handle));
		$linecount++;
	}
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
	<td><input type="text" name="AA[]" value="' . $data[0][$wk] . '" id="dis" readonly><br>
	<input type="text" name="AB[]" value="' . $data[1][$wk] . '" id="dis" readonly><br></td>
	<td><input type="text" name="BA[]" value="' . $data[2][$wk] . '" id="dis" readonly><br>
	<input type="text" name="BB[]" value="' . $data[3][$wk] . '" id="dis" readonly><br></td>
	<td><input type="text" name="CA[]" value="' . $data[4][$wk] . '" id="dis" readonly><br>
	<input type="text" name="CB[]" value="' . $data[5][$wk] . '" id="dis" readonly><br></td>
	</tr>';
	$wk++;
}
}
?>
</table>
</form>
<br />
<?php
function trim_text($input, $length) {
  if (strlen($input) <= $length)
      return $input;

  $last_space = strrpos(substr($input, 0, $length), ' ');
  return substr($input, 0, $last_space) . '...';
}

$files = glob('data/*.txt', GLOB_BRACE);
foreach($files as $file) {
	$fileArray = file("$file");
	echo $file;
	$dir_file = str_replace('archive/'.$year.'comment/', "", $file);
	$dir_file = str_replace(".txt", "", $dir_file);
	echo '<a id="nostyle" href="../comment/view.php?file=' . $dir_file . '&src=archive&year=' . $year.'"><div id="title">' . trim_text($fileArray[0],70);
	echo '</div><div id="date">' . $fileArray[1];
	echo '</div><br><div id="content">' . trim_text($fileArray[2],500);
	echo "</a><hr /></div>";
}
?>
</body>
</html>


