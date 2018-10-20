<?php
if(isset($_POST["AA"]) && ($_GET["ts"] == file_get_contents("timestamp.txt"))) {
	//clears shift data file
	ftruncate(fopen("data/dataShiftsAA.json", "r+"), 0);
	ftruncate(fopen("data/dataShiftsAB.json", "r+"), 0);
	ftruncate(fopen("data/dataShiftsBA.json", "r+"), 0);
	ftruncate(fopen("data/dataShiftsBB.json", "r+"), 0);
	ftruncate(fopen("data/dataShiftsCA.json", "r+"), 0);
	ftruncate(fopen("data/dataShiftsCB.json", "r+"), 0);
	ftruncate(fopen("timestamp.txt", "r+"), 0);
	
	//writes inputs from html to file
    file_put_contents("data/dataShiftsAA.json", json_encode($_POST["AA"]), FILE_APPEND);
	file_put_contents("data/dataShiftsAB.json", json_encode($_POST["AB"]), FILE_APPEND);
	file_put_contents("data/dataShiftsBA.json", json_encode($_POST["BA"]), FILE_APPEND);
	file_put_contents("data/dataShiftsBB.json", json_encode($_POST["BB"]), FILE_APPEND);
	file_put_contents("data/dataShiftsCA.json", json_encode($_POST["CA"]), FILE_APPEND);
	file_put_contents("data/dataShiftsCB.json", json_encode($_POST["CB"]), FILE_APPEND);
	
	file_put_contents("timestamp.txt", time() , FILE_APPEND | LOCK_EX);
}
else {
   die('There was an error submitting your request. Please try again. <a href="main.php">Back to main page</a>');
}
header( "refresh:0;url=main.php" );
?>