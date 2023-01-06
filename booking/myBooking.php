<!DOCTYPE html>
<html>
	<head>
		<meta charset="utf-8">
		<title>Profile Page</title>
		<link href="../style/style.css" rel="stylesheet" type="text/css">
        <link href="../styles/skeleton.css" rel="stylesheet" type="text/css">
		<link href="../styles/style.css" rel="stylesheet" type="text/css">
		<link href="../styles/normalize.css" rel="stylesheet" type="text/css"> 
		<script src="jquery-3.6.1.min.js"></script>
		<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/css/bootstrap.min.css">
  		<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.1/jquery.min.js"></script>
  		<script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/js/bootstrap.min.js"></script>

		
		<link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.7.1/css/all.css">
	</head>

<?php

// We need to use sessions, so you should always start sessions using the below code.
session_start();
// If the user is not logged in redirect to the login page...
if (!isset($_SESSION['loggedin'])) {
	header('Location: ../logon/index.html');
	exit;
}

ini_set('display_errors', 1);

ini_set('display_startup_errors', 1);

error_reporting(E_ALL);


function formatTime($time){
	$time = substr($time, 0, -8); 
	return $time; 
}; 

function convertType($type){
	if ($type == '0'){
		return 'Cardio Gym';
	} elseif ($type == '1'){
		return 'Weights Gym'; 
	}; 
}; 

$con=mysqli_connect("localhost","root","","phplogin");
// Check connection
if (mysqli_connect_errno())
{
echo "Failed to connect to MySQL: " . mysqli_connect_error();
}
// $date = date("Y/m/d");
$id = $_SESSION['id']; 
$date = strtotime(date("Y/m/d"));
$result = mysqli_query($con,"SELECT `OrderId`, `Notes`, `Slot`.`SlotId`, `TimeStart`, `TimeFinish`, `Date`, `Type` 
FROM `Order` INNER JOIN `Slot` ON `Order`.`OrderId` = `Slot`.`SlotId` WHERE `UserId` = $id AND `Date` > $date;");

echo "<link href='../styles/style.css' rel='stylesheet' type='text/css'>";
echo "<table id='adminTable'>
<tr>
<th>Booking Reference Number</th>
<th>Type</th>
<th>Start time</th>
<th>End time</th>
<th>Date</th>
<th>Notes</th>
</tr>";

while($row = mysqli_fetch_array($result))
{

echo "<tr>";
echo "<td>" . $row['OrderId'] . "</td>";
echo "<td>" . convertType($row['Type']) . "</td>";
echo "<td>" . formatTime($row['TimeStart']) . "</td>";
echo "<td>" . formatTime($row['TimeFinish']) . "</td>";
echo "<td>" . $row['Date'] . "</td>";
echo "<td>" . $row['Notes'] . "</td>";
}
echo "<table>";

mysqli_close($con);




?> 