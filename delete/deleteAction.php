<?php
if(isset($_POST["AA"]) && ($_GET["ts"] == file_get_contents("../timestamp.txt"))) {
	
	$dataAA = json_decode(file_get_contents("../data/dataShiftsAA.json"));
	$dataAB = json_decode(file_get_contents("../data/dataShiftsAB.json"));
	$dataBA = json_decode(file_get_contents("../data/dataShiftsBA.json"));
	$dataBB = json_decode(file_get_contents("../data/dataShiftsBB.json"));
	$dataCA = json_decode(file_get_contents("../data/dataShiftsCA.json"));
	$dataCB = json_decode(file_get_contents("../data/dataShiftsCB.json"));
	
	$AA_temp = $_POST["AA"];
	echo print_r($_POST["AA"]);
	for($i = 0;$i <= sizeof($AA_temp);$i++) {
		if($AA_temp[$i] == "remove") {
			$AA_temp[$i] == "*";
		} else if(!empty($dataAA[$i])){
			$AA_temp[$i] == $dataAA[$i];
		} else {
			$AA_temp[$i] == "*";
		}
	}
	
	//clears shift data file
	ftruncate(fopen("../data/dataShiftsAA.json", "r+"), 0);
	ftruncate(fopen("../data/dataShiftsAB.json", "r+"), 0);
	ftruncate(fopen("../data/dataShiftsBA.json", "r+"), 0);
	ftruncate(fopen("../data/dataShiftsBB.json", "r+"), 0);
	ftruncate(fopen("../data/dataShiftsCA.json", "r+"), 0);
	ftruncate(fopen("../data/dataShiftsCB.json", "r+"), 0);
	ftruncate(fopen("../timestamp.txt", "r+"), 0);

	//writes inputs from html to file
    file_put_contents("../data/dataShiftsAA.json", json_encode($AA_temp), FILE_APPEND);
	file_put_contents("../data/dataShiftsAB.json", json_encode($_POST["AB"]), FILE_APPEND);
	file_put_contents("../data/dataShiftsBA.json", json_encode($_POST["BA"]), FILE_APPEND);
	file_put_contents("../data/dataShiftsBB.json", json_encode($_POST["BB"]), FILE_APPEND);
	file_put_contents("../data/dataShiftsCA.json", json_encode($_POST["CA"]), FILE_APPEND);
	file_put_contents("../data/dataShiftsCB.json", json_encode($_POST["CB"]), FILE_APPEND);
	
	file_put_contents("../timestamp.txt", time() , FILE_APPEND | LOCK_EX);
} else {
	if(isset($_GET["admin"])) {
		die('There was an error submitting your request. Please try again. <a href="index.php?admin">Back to main page</a>');
	} else  {
		die('There was an error submitting your request. Please try again. <a href="index.php">Back to main page</a>');
	}
}

if(isset($_GET["admin"])) {
	header("refresh:2;url=index.php?admin");
} else {
	header("refresh:2;url=index.php");
}
?>