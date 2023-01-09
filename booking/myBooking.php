

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
		<!-- <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.1/jquery.min.js"></script>  -->
        <!-- <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script> -->
        <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.4.0/jquery.min.js"></script>
  	
  
		<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/css/bootstrap.min.css" rel="stylesheet">
  		<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/js/bootstrap.bundle.min.js"></script>

		<!-- <script rel="stylesheet" src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css"></script> 
		<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script> -->

		
		<link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.7.1/css/all.css">
</head>



<!-- The Modal -->
<div class="modal fade" id="myModal">
    <div class="modal-dialog">
        <div class="modal-content">
            <!-- Modal Header -->
            <div class="modal-header">
                <h4 class="modal-title">Modal Heading</h4>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <!-- Modal body -->
            <div class="modal-body" id="bookingIdText">

            </div>
            <form action="myBookingBackend.php" method='post'>
                <div>
                    <input type="hidden" name="bookingIdHidden" id="bookingIdHidden" value="">
                </div>
                <button type="submit" id="submit" class="btn btn-primary">Submit</button>
            </form>
            <!-- Modal footer -->
            <div class="modal-footer">
                <button type="button" class="btn btn-danger" data-bs-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>

<script> 

function takeId(newButtonId){
	
    $('#myModal').modal('show');
    document.getElementById("bookingIdText").innerHTML = newButtonId; 
    alert(newButtonId);
    try {

        alert($('#bookingIdHidden').val()); 
     
        $('#bookingIdHidden').attr('value', newButtonId)
    } catch(err) {
        alert(err); 
    }

};


</script> 

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
echo "<td>" . "<input type='button' name='edit' value='Edit' onclick=takeId($newButtonId) id='1' </button>" . "</td>"; 
echo "<td>" . "<input type='button' name='cancel' value='Cancel' id='2' data-toggle='modal' data-target='#modal'></button>" . "</td>"; 
}
echo "<table>";

mysqli_close($con);



?> 