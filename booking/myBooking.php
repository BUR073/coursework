

<!DOCTYPE html>
<html>
	<head>
		<meta charset="utf-8">
		<title>My Bookings Page</title>
		<link href="../style/style.css" rel="stylesheet" type="text/css">
        <link href="../styles/skeleton.css" rel="stylesheet" type="text/css">
		<link href="../styles/style.css" rel="stylesheet" type="text/css">
		<link href="../styles/normalize.css" rel="stylesheet" type="text/css"> 
        <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.1/jquery.min.js"></script>

       
  
		<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/css/bootstrap.min.css" rel="stylesheet">
  		<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/js/bootstrap.bundle.min.js"></script>


		
		<link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.7.1/css/all.css">


        <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-select@1.13.14/dist/css/bootstrap-select.min.css">
        <script type="text/javascript" src="js/bootstrap-timepicker.min.js"></script>
        <link type="text/css" href="css/bootstrap-timepicker.min.css" />





        

</head>



<!-- The Modal -->
<div class="hidden modal fade" id="myModal">
    <div class="modal-dialog">
        <div class="modal-content">
            <!-- Modal Header -->
            <div class="modal-header">
                <h4 class="modal-title"></h4>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <!--Modal body -->
            <div class="modal-body">

                <form action="myBookingBackend.php" method='post'>
                
                <input type="hidden" name="bookingIdHidden" id="bookingIdHidden" value="">

                <label for="date">Date</label>
                <input type="date" name="date" id="date" placeholder="">

                <label for="timeStart">Start Time</label>
                <input type="time" name="timeStart" id="timeStart" placeholder="">

                <label for="timeEnd">End Time</label>
                <input type="time" name="timeEnd" id="timeEnd" placeholder="">


                <label for="gymType">Type:</label>
				<select name="gymType" id="gymType">
   				<option value="cardio">Cardiovascular Gym</option>
  				<option value="weights">Weights Room</option>
				</select>


                <label for="notes">Notes</label>

                <input type="text" id="notes" name="notes" rows="3" cols="30" value="">


                


                <button type="submit" id="submit" class="btn btn-primasry">Submit</button>

                </form>
            </div>
            <!-- Modal footer -->
            <div class="modal-footer">
                <button type="button" class="btn btn-danger" data-bs-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>

<script> 

function takeId(newButtonId, startTime, endTime, date, note){
	
    $('#myModal').modal('show');

    try {
        $('#bookingIdHidden').attr('value', newButtonId);
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

$newButtonId = $row['OrderId'];
$startTime = $row['TimeStart']; 
$endTime = $row['TimeFinish']; 
$date = $row['Date']; 
$note = $row['Notes'];

echo "<tr>";
echo "<td>" . $newButtonId . "</td>";
echo "<td>" . convertType($row['Type']) . "</td>";
echo "<td>" . formatTime($startTime) . "</td>";
echo "<td>" . formatTime($endTime) . "</td>";
echo "<td>" . $date . "</td>";
echo "<td>" . $note . "</td>";
echo "<td>" . "<input type='button' name='edit' value='Edit' onclick='takeId($newButtonId, $startTime, $endTime, $date, $note)' id='1'>" . "</td>"; 
// echo "<td>" . "<input type='button' name='edit' value='Edit' onclick=takeId($newButtonId, $startTime, $endTime, $date, $note) id='1'>" . "</td>"; 

echo "<td>" . "<input type='button' name='cancel' value='Cancel' onclick=takeId($newButtonId) id='2'>" . "</td>"; 
}
echo "<table>";

mysqli_close($con);
