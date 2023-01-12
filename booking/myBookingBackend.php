<?php
session_start();
// If the user is not logged in redirect to the login page...
if (!isset($_SESSION['loggedin'])) {
	header('Location: ../logon/index.html');
	exit;
}

ini_set('display_errors', 1);

ini_set('display_startup_errors', 1);

error_reporting(E_ALL);

$DATABASE_HOST = 'localhost';
$DATABASE_USER = 'root';
$DATABASE_PASS = '';
$DATABASE_NAME = 'phplogin';
$con = mysqli_connect($DATABASE_HOST, $DATABASE_USER, $DATABASE_PASS, $DATABASE_NAME);

function formatTime($time){
	$time = substr($time, 0, -8); 
	return $time; 
}; 

function compare($DATABASE_HOST, $DATABASE_USER, $DATABASE_PASS, $DATABASE_NAME, $con,
 $oldNote, $oldSlotId, $oldStartTime, $oldEndTime, $oldDate, $oldType, $newType, $newStartTime, 
 $newEndTime, $newDate, $newNote){
	echo "Function: compare()", "<br>"; 
 }; 

function findOldVar($DATABASE_HOST, $DATABASE_USER, $DATABASE_PASS, $DATABASE_NAME, $con, $id, $newType, $newStartTime, 
$newEndTime, $newDate, $newNote){


	$stmt = $con->prepare('SELECT `Notes`, `Slot`.`SlotId`, `TimeStart`, `TimeFinish`, `Date`, `Type` 
	FROM `Order` INNER JOIN `Slot` ON `Order`.`OrderId` = `Slot`.`SlotId` WHERE `OrderId` = ?');

	$stmt->bind_param('s', $id); 

	$stmt->execute();

	$oldNote = ''; 
	$oldStartTime = ''; 
	$oldType = '';
	$oldEndTime = ''; 
	$oldDate = ''; 
	$oldSlotId = ''; 

	$stmt->bind_result($oldNote, $oldSlotId, $oldStartTime, $oldEndTime, $oldDate, $oldType);

	$stmt->fetch();

	$stmt->close();

	$oldStartTime = formatTime($oldStartTime); 
	$oldEndTime = formatTime($oldEndTime); 

	echo "Function: findOldVar()", "<br>", "<br>"; 
	echo "Old Type: ", $oldType, "<br>";
	echo "Old Start Time: ", $oldStartTime, "<br>";
	echo "Old End Time: ", $oldEndTime, "<br>";
	echo "Old Date: ", $oldDate, "<br>";
	echo "Old Notes: ", $oldNote, "<br>";
	echo "Old Slot Id: ", $oldSlotId, "<br>"; 

	compare($DATABASE_HOST, $DATABASE_USER, $DATABASE_PASS, $DATABASE_NAME, $con,
 $oldNote, $oldSlotId, $oldStartTime, $oldEndTime, $oldDate, $oldType, $newType, $newStartTime, 
 $newEndTime, $newDate, $newNote); 

}; 




function postVar($DATABASE_HOST, $DATABASE_USER, $DATABASE_PASS, $DATABASE_NAME, $con){
	echo "Function: postVar()", "<br>", "<br>";
	$id = $_POST['bookingIdHidden']; 
	$newType = $_POST['gymType']; 
	$newStartTime = $_POST['timeStart']; 
	$newEndTime = $_POST['timeEnd'];
	$newDate = $_POST['date']; 
	$newNote = $_POST['notes'];

	echo "Booking Id: ", $id, "<br>";
	echo "Type: ", $newType, "<br>";
	echo "Start Time: ", $newStartTime, "<br>";
	echo "End Time: ", $newEndTime, "<br>";
	echo "Date: ", $newDate, "<br>";
	echo "Notes: ", $newNote, "<br>";
	echo "<br>";

	findOldVar($DATABASE_HOST, $DATABASE_USER, $DATABASE_PASS, $DATABASE_NAME, $con, $id, $newType, $newStartTime, 
	$newEndTime, $newDate, $newNote);
	
}; 

postVar($DATABASE_HOST, $DATABASE_USER, $DATABASE_PASS, $DATABASE_NAME, $con); 




?> 