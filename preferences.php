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
<form action="<?php echo $_SERVER['PHP_SELF']; ?>?success" method="post">
<h2>TR37 Website Preferences</h2>
<p>Site-wide preferences. Warning: don't mess with this unless you know what you're doing.</p><br />
<?php 
  $dtsin = json_decode(file_get_contents("resetDates.json")); 
  $prefs = json_decode(file_get_contents('preferences.json'), true);
?>

<a><b>Use deletion requests</b><i>(default: no)</i>: </a>
	<input type="radio" id="request_delete" name="request_delete" value="true" <?php echo $prefs["requests"] === "true" ? 'checked="checked"' : ""; ?> required>Yes</input>
	<input type="radio" id="request_delete" name="request_delete" value="false" <?php echo $prefs["requests"] === "false" ? 'checked="checked"' : ""; ?>>No</input><br />
  <a>(shift deletions must be approved by an admin)</a><br /><br />

<a><b>Enable IP-based shift revocations </b><i>(default: yes)</i>: </a>
  <input type="radio" id="revocation_enable" name="revocation_enable" value="true" <?php echo $prefs["revocations"] === "true" ? 'checked="checked"' : ""; ?> required>Yes</input>
  <input type="radio" id="revocation_enable" name="revocation_enable" value="false" <?php echo $prefs["revocations"] === "false" ? 'checked="checked"' : ""; ?>>No</input><br />
  <a>(shifts will be greyed out after entry to all but the inital IP that entered it)</a>

<br /><br /><input type="submit" value="Submit">
</form>
</body>
</html>

<?php
if(isset($_GET["success"])) {
  file_put_contents("preferences.json", json_encode(array("requests" => $_POST["request_delete"], "revocations" => $_POST["revocation_enable"])));
	echo 'Site-wide preferences changed successfully';
}
?>