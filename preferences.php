<?php
include "auth.php";
if (authMain() != "admin") {
	die("You do not have the adequate credentials to view this page.");
}
?>

<html>
<head>
	<title>TR37 Tree Lot | Website Preferences</title>
	<link rel="icon" href="favicon.png">
	<style>
		th, td {border:1px solid grey; text-align:center;}
		body {text-align:center;font-family:"Arial";}
	</style>
</head>

<body>
<form action="<?php echo $_SERVER['PHP_SELF']; ?>?change" method="post">
<h2>Website Preferences</h2>
<p>Site-wide preferences. Warning: don't mess with this unless you know what you're doing.</p><br />
<?php
  $dtsin = json_decode(file_get_contents("resetDates.json"));
  $prefs = json_decode(file_get_contents('preferences.json'), true);
?>

<a><b>Expand shifts to three slots </b><i>(default: yes)</i>: </a>
	<input type="radio" id="expand_shifts" name="expand_shifts" value="true" <?php echo $prefs["expand"] === "true" ? 'checked="checked"' : ""; ?> required>Yes</input>
	<input type="radio" id="expand_shifts" name="expand_shifts" value="false" <?php echo $prefs["expand"] === "false" ? 'checked="checked"' : ""; ?>>No</input><br />
  <a>(each time slot will have three shift slots instead of two)</a><br /><br />

<a><b>Enable deletion requests </b><i>(default: yes)</i>: </a>
	<input type="radio" id="request_delete" name="request_delete" value="true" <?php echo $prefs["requests"] === "true" ? 'checked="checked"' : ""; ?> required>Yes</input>
	<input type="radio" id="request_delete" name="request_delete" value="false" <?php echo $prefs["requests"] === "false" ? 'checked="checked"' : ""; ?>>No</input><br />
  <a>(shift deletions must be approved by an admin)</a><br /><br />

<a><b>Enable lot setup shifts </b><i>(default: no)</i>: </a>
  <input type="radio" id="setup_shifts" name="setup_shifts" value="true" <?php echo $prefs["setup"] === "true" ? 'checked="checked"' : ""; ?> required>Yes</input>
  <input type="radio" id="setup_shifts" name="setup_shifts" value="false" <?php echo $prefs["setup"] === "false" ? 'checked="checked"' : ""; ?>>No</input><br />
  <a>(users will not be able to sign up for shifts during tree recieving)</a><br /><br />

<a><b>Put site into Maintenance Mode </b><i>(default: no)</i>: </a>
  <input type="radio" id="maintenance" name="maintenance" value="true" <?php echo $prefs["maintenance"] === "true" ? 'checked="checked"' : ""; ?> required>Yes</input>
  <input type="radio" id="maintenance" name="maintenance" value="false" <?php echo $prefs["maintenance"] === "false" ? 'checked="checked"' : ""; ?>>No</input><br />
  <a>(signups are disabled to use or access)</a><br /><br />

<input type="submit" value="Submit">
</form>
</body>
</html>

<?php
if(isset($_GET["change"])) {
  file_put_contents("preferences.json", json_encode(array(
		"expand" => $_POST["expand_shifts"],
		"requests" => $_POST["request_delete"],
		"setup" => $_POST["setup_shifts"],
		"maintenance" => $_POST["maintenance"]), JSON_PRETTY_PRINT));
	echo 'Site-wide preferences changed successfully';
}
?>
