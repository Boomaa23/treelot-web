<?php
include "../auth.php";
if (authMain() != "admin") {
	die("You do not have the adequate credentials to view this page.");
}	
?>

<html>
<head>
<title>TR37 Tree Lot | Admin Shift Deletion List</title>
<link rel="icon" href="../favicon.png">
<style>
	th, td {border:1px solid grey; text-align:center;}
	#dis {background-color:#eaeaea;}
	body {text-align:center;font-family:"Arial";}
</style>
</head>

<body>
<h2>Shift Deletion Requests</h2>
<p>Administrators can approve shift deletion requests here.</p>
<table cellspacing="0" cellpadding="5" align="center">
<?php 
  //reads existing signups from file
  $handle = fopen("removequeue.json", "r+");
  $data = array(array());
  $linecount = 0;
  while(!feof($handle)){
    $data[$linecount] = json_decode(fgets($handle), true);
    $linecount++;
  }
  
  $rwnum = 0;
	echo '<th>#</th>
	<th>Name</th>
	<th>Loc</th>
	<th>Approve?</th>';
  foreach($data as $key => $row) {
    if($rwnum >= sizeof($data) - 1) {
      break;
    }
    echo '<tr>';
    echo '<form action="deleteAction.php?loc=' . htmlspecialchars($row["shiftlocation"]) . '&line=' . $key . '" method="post">';
    echo '<td>' . $key . '</td>';
    echo '<td><input type="text" name="confirm" style="display:none;" value="' . $row["name"] . '">' . $row["name"] . '</input></td>';
    echo '<td>' . $row["shiftlocation"] . '</td>';
    echo '<td><input type="submit" value="Approve"></input></td>';
    echo '</form></tr>';
    $rwnum++;
  }
?>
</table>