<!DOCTYPE html>
<html>
	<head>
		<meta charset="utf-8">
		<title>My Bookings Page</title>
		<link href="../style/style.css" rel="stylesheet" type="text/css">
        <link href="../styles/skeleton.css" rel="stylesheet" type="text/css">
		<link href="../styles/style.css" rel="stylesheet" type="text/css">
		<link href="../styles/normalize.css" rel="stylesheet" type="text/css"> 
		<script src="jquery-3.6.1.min.js"></script>
		<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.1/jquery.min.js"></script> 
		<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/css/bootstrap.min.css">
  		<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.1/jquery.min.js"></script>
  		<script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/js/bootstrap.min.js"></script>

		
		<link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.7.1/css/all.css">
	</head>

	<body class="loggedin">
		<nav class="navtop">
			<div>
				<h1>Booking</h1>
                <!-- Heading --> 

				<a href="../membership/membership.html"><i class="fas fa-table"></i>Manage Memberships</a>
                <!-- Manage memberships button --> 

				<a href="../home/home.php"><i class="fas fa-home"></i>Home</a>
                <!-- Home button -->  

				<a href="../home/profile.php"><i class="fas fa-user-circle"></i>Profile</a>
                <!-- Profile button --> 

				<a href="myBooking.php"><i class="fas fa-sign-out-alt"></i>My Bookings</a>
				<!-- My bookings button -->
				<a href="../logon/logout.php"><i class="fas fa-sign-out-alt"></i>Logout</a>
                <!-- Logout button --> 

				
				
				
			</div>
		</nav>
		<div class="content">
            <!-- Define new division--> 

			<h2>My Bookings</h2>
			<br>


			
		</div>

		<div class='container'>

<script> 

function takeId(newButtonId){
	// alert(newButtonId)
	$('#modal').modal('show');
	// document.getElementById("bookingId").innerHTML = newButtonId;
	// document.getElementById("boookingId").value = newButtonId;

	$(document).ready(function () { 
    $('input[name="bookingId"]').val(newButtonId);
});
};

</script> 

<!-- Modal -->
<div class='modal fade' id='modal' role='dialog'>

<div class='modal-dialog'>
    
<!-- Modal content-->

<div class='modal-content'>

<div class='modal-header'>

<button type='button' class='close' data-dismiss='modal'>&times;</button>

<h4 class='modal-title'>Title</h4>

<a id="bookingId">BookingId</a>

<form action='myBookingBackend.php' method='post'>

<label for="type">Type</label><br>
<select name="type" id="type">
  <option value="cardio">Cardio Gym</option>
  <option value="weight">Weights Gym</option>
</select>

<label for="timeStart">Start Time</label><br>
<select name="timeStart" id="timeStart">
  <option value="0600">0600</option>
  <option value="0700">0700</option>

  <option value="0800">0800</option>
  <option value="0900">0900</option>

  <option value="1000">1000</option>
  <option value="1100">1100</option>

  <option value="1200">1200</option>
  <option value="1300">1300</option>

  <option value="1400">1400</option>
  <option value="1500">1500</option>

  <option value="1600">1600</option>
  <option value="1700">1700</option>

</select>



<input type="hidden" name="bookingId" id="bookingId" value="">
<input type="submit" value="Submit">


</form>
</div>

<div class='modal-body'>

<input type='button' value='Close' data-dismiss='modal'> 


</div>
</div>
</div>
</div>
</div>



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
<th></th>
<th></th>
</tr>";

while($row = mysqli_fetch_array($result))
{
$newButtonId = ''; 
$newButtonId = $row['OrderId'];
echo "<tr>";
echo "<td>" . $row['OrderId'] . "</td>";
echo "<td>" . convertType($row['Type']) . "</td>";
echo "<td>" . formatTime($row['TimeStart']) . "</td>";
echo "<td>" . formatTime($row['TimeFinish']) . "</td>";
echo "<td>" . $row['Date'] . "</td>";
echo "<td>" . $row['Notes'] . "</td>";
echo "<td>" . "<input type='button' name='cancel' value='Edit' onclick=takeId($newButtonId) id='1'</button>" . "</td>"; 
echo "<td>" . "<input type='button' name='cancel' value='Cancel' id='2' data-toggle='modal' data-target='#modal'></button>" . "</td>"; 
}
echo "<table>";

mysqli_close($con);



?> 