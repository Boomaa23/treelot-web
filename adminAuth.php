<?php 
include "auth.php";
if (authMain() == "admin") {
	authCheck();
} else {
	die("You do not have the adequate credentials to view this page.");
}
?>

<title>TR37 Tree Lot | Admin Browser</title>
<link rel="icon" href="favicon.png">
<style>
	body {text-align:center;font-family:"Arial";}
</style>

<body style="text-align:center;">
<a><b>Admin Authorized Pages</b></a><br />
<a href="adminAuth.php?reset">Reset & backup shifts</a><br />
<a href="adminAuth.php?view">View past years' shifts</a><br />
<a href="adminAuth.php?signup">Sign up for new shifts</a><br />
<a href="adminAuth.php?comment">View or add shift comments</a><br />
<a href="adminAuth.php?delete">Manage shift deletions</a><br />
<a href="adminAuth.php?preferences">Set website preferences</a><br />
<hr />
	
<?php
if (isset($_GET["reset"])) {
	echo '<iframe width="100%" height="84%"src="reset.php" style="border:0;"></iframe>';
} else if (isset($_GET["view"])) {
	echo '<iframe width="100%" height="84%"src="archive/index.php" style="border:0;"></iframe>';
} else if (isset($_GET["signup"])) {
	echo '<iframe width="100%" height="84%"src="index.php?admin" style="border:0;"></iframe>';
} else if (isset($_GET["comment"])) {
	echo '<iframe width="100%" height="84%"src="comment/index.php?admin" style="border:0;"></iframe>';
} else if (isset($_GET["delete"])) {
	echo '<iframe width="100%" height="84%"src="delete/index.php?admin" style="border:0;"></iframe>';
} else if (isset($_GET["preferences"])) {
	echo '<iframe width="100%" height="84%"src="preferences.php" style="border:0;"></iframe>';
}
?>
</body>