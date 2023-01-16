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

function updateNote($DATABASE_HOST, $DATABASE_USER, $DATABASE_PASS, $DATABASE_NAME, $con, $id, $oldNote, $newNote){
	echo "<br>", "Function: updateNote()", "<br>"; 
	$stmt = $con->prepare('UPDATE `Order` SET `Notes` = ? WHERE `OrderId` = ?');

    $stmt->bind_param('ss', $newNote, $id ); 
  
    $stmt->execute();

    $stmt->close();

};




function checkMembership($DATABASE_HOST, $DATABASE_USER, $DATABASE_PASS, $DATABASE_NAME, $con, $checkType, $newType){

    $userId = $_SESSION['id'];

    // Get user Id from session variable

    $MemberShipId = ''; 
    $MemberStartDate = ''; 
    $MemberEndDate = ''; 
    $Gym = ''; 
    $Cardio = ''; 

    // Define required varibale for sql below

    // The SQL statement below queries the table `MemberShipCore` to find the users
    // MemberShipId which is then stored in the variable $MemberShipId
    
    $stmt = $con->prepare('SELECT `MemberShipId` FROM `MemberShipCore` Where `UserId` = ?');
    $stmt->bind_param('s', $userId);
    $stmt->execute(); 
    $stmt->store_result(); 
    $stmt->bind_result($MemberShipId); 
    $stmt->fetch(); 



    $stmt = $con->prepare('SELECT `StartDate`, `EndDate`,`Gym`, `Cardio` FROM `MembershipCore` INNER JOIN
	 `MemberShipType` ON `MemberShipCore`.`MemberShipId` = `MemberShipType`.`MemberShipId` WHERE
	  `MemberShipCore`.`MemberShipId` = ? ORDER BY StartDate');
    $stmt->bind_param('s', $MemberShipId);
    $stmt->execute();
    $stmt->store_result();
    // What if there is multiple sets of memberships? --> Order by start date --> Will give lates membership
    $stmt->bind_result($MemberStartDate, $MemberEndDate, $Gym, $Cardio);

    $stmt->fetch();

	if ($checkType == 'date'){
		return $MemberEndDate;  
	} if ($checkType == 'type'){
		if ($newType == 'Weights Gym' && $Gym == 1){
			echo "Membership: Valid", "<br>"; 
			return 1; 
		} elseif ($newType == 'Cardio Gym' && $Cardio == 1){
			echo "Membership: Valid", "<br>"; 
			return 1; 
		} else {
			echo "Membership: Not Valid", "<br>"; 
			return 0; 
		}
	};

};

function updateNumberUsers($DATABASE_HOST, $DATABASE_USER, $DATABASE_PASS, $DATABASE_NAME, 
$con, $slotId, $oldSlotId){
	// Find number of users for new slot
	$stmt = $con->prepare('SELECT `NumberUsers` FROM `Slot` WHERE `SlotId` = ?');
	$stmt->bind_param('s', $slotId);
		
	$stmt->execute();

	$newNumberUsers = '';

	$stmt->bind_result($newNumberUsers);

	$stmt->fetch();

	$newNumberUsers++; 

	$stmt->close();

	echo "SELECT NumberUsers from slot where slotId = ", $slotId, "<br>"; 

	// Update new number of users
	$stmt = $con->prepare('UPDATE `Slot` SET `NumberUsers` = ? WHERE `SlotId` = ?');
	$stmt->bind_param('ss', $newNumberUsers, $slotId);
		
	$stmt->execute();

	// Find old slot number of users

	$stmt = $con->prepare('SELECT `NumberUsers` FROM `Slot` WHERE `SlotId` = ?');
	$stmt->bind_param('s', $oldSlotId);
	echo "SlotId Old: ", $oldSlotId, "<br>"; 
		
	$stmt->execute();

	$oldNumberUsers = '';

	$stmt->bind_result($oldNumberUsers);

	$stmt->fetch();
	echo "OldNumberUsers: ", $oldNumberUsers, "<br>", "NumberUsers: ", $newNumberUsers; 
	$oldNumberUsers--;
	$stmt->close();

	// Update old number of users

	$stmt = $con->prepare('UPDATE `Slot` SET `NumberUsers` = ? WHERE `SlotId` = ?');
	$stmt->bind_param('ss', $oldNumberUsers, $oldSlotId);
		
	$stmt->execute();
	$stmt->close(); 

};


function updateOrder($DATABASE_HOST, $DATABASE_USER, $DATABASE_PASS, $DATABASE_NAME, 
$con, $newType, $id, $userId, $newStartTime, $newEndTime, $newDate, $slotId, $numberUsers, $oldSlotId){
	echo "<br>", "Function: updateOrder()", "<br>"; 

	echo "UPDATE Order SET `SlotId` = '", $slotId, "' WHERE `UserId` = '", $userId, "' AND `OrderId` = '", $id, "';", "<br>";

	$stmt = $con->prepare('UPDATE `Order` SET `SlotId` = ? WHERE `UserId` = ? AND `OrderId` = ?');
    	$stmt->bind_param('sss', $slotId, $userId, $id);
            
    	$stmt->execute();

		updateNumberUsers($DATABASE_HOST, $DATABASE_USER, $DATABASE_PASS, $DATABASE_NAME, 
		$con, $slotId, $id); 
};

function findSlot($DATABASE_HOST, $DATABASE_USER, $DATABASE_PASS, $DATABASE_NAME, 
$con, $newType, $oldType, $id, $userId, $newStartTime, $newEndTime, $newDate, $oldSlotId){
	echo "<br>", "Function: findSlotType()", "<br>"; 
	$checkType = 'type'; 

	echo "New Type: ", $newType, "<br>", "Old Type: ", $oldType, "<br>";
	$membership = checkMembership($DATABASE_HOST, $DATABASE_USER, $DATABASE_PASS, 
	$DATABASE_NAME, $con, $checkType, $newType); 

	if ($membership == 1){
		echo "Continue", "<br>";
		// 0 - Cardio
    	// 1 - weights

		echo "Date: ", $newDate, "<br>"; 
    	$stmt = $con->prepare('SELECT `SlotId`, `NumberUsers` FROM `Slot` 
		WHERE `TimeStart` = ? AND `TimeFinish` = ? AND `Date` = ? AND `Type` = ?');
    	$stmt->bind_param('ssss', $newStartTime, $newEndTime, $newDate, $newType);
            
    	$stmt->execute();

    	$slotId = ''; 
    	$numberUsers = ''; 
    

    	$stmt->bind_result($slotId, $numberUsers);

    	$stmt->fetch();

    	$stmt->close();

		echo "SlotId: ", $slotId, "<br>", "Number of users: ", $numberUsers, "<br>"; 

		if ($numberUsers > 20){
			echo "New slot is fully booked", "<br>"; 
		} elseif ($numberUsers < 20){
			echo "Not fully booked, proceed", "<br>"; 
			updateOrder($DATABASE_HOST, $DATABASE_USER, $DATABASE_PASS, $DATABASE_NAME, 
			$con, $newType, $id, $userId, $newStartTime, $newEndTime, $newDate, $slotId, $numberUsers, $oldSlotId); 
		}


	} elseif ($membership = 0){
		echo "Don't continue", "<br>";
	}




 

}; 

function compare($DATABASE_HOST, $DATABASE_USER, $DATABASE_PASS, $DATABASE_NAME, $con, $id,
 $oldNote, $oldSlotId, $oldStartTime, $oldEndTime, $oldDate, $oldType, $newType, $newStartTime, 
 $newEndTime, $newDate, $newNote){
	echo "<br>", "Function: compare()", "<br>"; 
	$userId = $_SESSION['id'];


	if ($oldNote != $newNote){
		echo "Loop: Note", "<br>";
		updateNote($DATABASE_HOST, $DATABASE_USER, $DATABASE_PASS, $DATABASE_NAME, $con, $id, $oldNote, $newNote); 
	} if ($oldStartTime != $newStartTime && $oldEndTime != $oldStartTime || $newDate != $oldDate || $newType != $oldType){
		echo "Loop: Time, Date, Type", "<br>";
		findSlot($DATABASE_HOST, $DATABASE_USER, $DATABASE_PASS, 
		$DATABASE_NAME, $con, $newType, $oldType, $id, 
		$userId, $newStartTime, $newEndTime, $newDate, $oldType); 
	} else {
		echo "No different"; 
	}
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

	compare($DATABASE_HOST, $DATABASE_USER, $DATABASE_PASS, $DATABASE_NAME, $con, $id, 
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

	findOldVar($DATABASE_HOST, $DATABASE_USER, $DATABASE_PASS, 
	$DATABASE_NAME, $con, $id, $newType, $newStartTime, 
	$newEndTime, $newDate, $newNote);
	
}; 

postVar($DATABASE_HOST, $DATABASE_USER, $DATABASE_PASS, $DATABASE_NAME, $con); 




?> 