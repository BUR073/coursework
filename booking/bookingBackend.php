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

$DATABASE_HOST = 'localhost';
$DATABASE_USER = 'root';
$DATABASE_PASS = '';
$DATABASE_NAME = 'phplogin';
$con = mysqli_connect($DATABASE_HOST, $DATABASE_USER, $DATABASE_PASS, $DATABASE_NAME);
// Try and connect using the info above.

if ( mysqli_connect_errno() ) {
	// If there is an error with the connection, stop the script and display the error.
	exit('Failed to connect to MySQL: ' . mysqli_connect_error());

    
}

function newOrderId($DATABASE_HOST, $DATABASE_USER, $DATABASE_PASS, $DATABASE_NAME, $con){
    echo "<br>";
    echo "Function: newOrderId", "<br>";
    echo "<br>";
    $stmt = $con->prepare('SELECT MAX(OrderId) FROM `Order`');
  
    $stmt->execute();

    $newId = ''; 

    $stmt->bind_result($newId);

    $stmt->fetch();

    $stmt->close();

    $newId++; 

    return $newId; 
};

function newNumberUsers($DATABASE_HOST, $DATABASE_USER, $DATABASE_PASS, $DATABASE_NAME, $con, $slotId){
    echo "<br>";
    echo "Function: newNumberUsers", "<br>";
    echo "<br>";

    $stmt = $con->prepare('SELECT MAX(NumberUsers) FROM `Slot` WHERE `SlotId` = ?');

    $stmt->bind_param('s',$slotId); 
  
    $stmt->execute();

    $newId = ''; 

    $stmt->bind_result($newId);

    $stmt->fetch();

    $stmt->close();

    $newId++; 

    return $newId; 
};

function book($DATABASE_HOST, $DATABASE_USER, $DATABASE_PASS, $DATABASE_NAME, $con, $slotId, $userId){
    echo "<br>";
    echo "Function: book", "<br>";
    echo "<br>";

    $orderId = newOrderId($DATABASE_HOST, $DATABASE_USER, $DATABASE_PASS, $DATABASE_NAME, $con); 

    echo "New OrderId: ", $orderId, "<br>"; 
    $notes = ''; 

    $stmt = $con->prepare('INSERT INTO `Order`(`OrderId`, `UserId`, `Notes`, `SlotId`) VALUES (?, ?, ?, ?)');
    $stmt->bind_param('ssss', $orderId, $userId, $notes, $slotId); 
    $stmt->execute(); 

    echo "Inserted into Order", "<br>"; 

    $numberUsers = newNumberUsers($DATABASE_HOST, $DATABASE_USER, $DATABASE_PASS, $DATABASE_NAME, $con, $slotId); 
    echo "New Number of users: ", $numberUsers, "<br>"; 

    $stmt = $con->prepare('UPDATE `Slot` SET `NumberUsers`= ?  WHERE `SlotId` = ?');
    $stmt->bind_param('ss', $numberUsers, $slotId); 
    $stmt->execute(); 
    $stmt->close(); 

    echo "Updated slot", "<br>"; 
}; 

function checkBooking($DATABASE_HOST, $DATABASE_USER, $DATABASE_PASS, $DATABASE_NAME, $con, $slotId, $userId){
    echo "<br>";
    echo "Function: checkBooking", "<br>";
    echo "<br>";

    echo "SlotId: ", $slotId, "<br>"; 
    echo "userId: ", $userId, "<br>"; 
    
    $stmt = $con->prepare('SELECT `OrderId` FROM `Order` WHERE `UserId` = ? AND `SlotId` = ?');
    $stmt->bind_param('ss', $userId, $slotId ); 
    $stmt->execute(); 
    $orderId = ''; 
    $stmt->bind_result($orderId); 
    $stmt->fetch(); 
    $stmt->close(); 

    echo "OrderId: ", $orderId, "<br>"; 

    if ($orderId == ''){
        echo "User is not already booked into this slot", "<br>";
        book($DATABASE_HOST, $DATABASE_USER, $DATABASE_PASS, $DATABASE_NAME, $con, $slotId, $userId); 
    } else {
        echo "User is already booked into this slot", "<br>"; 
    }; 
}; 

function findSlot($DATABASE_HOST, $DATABASE_USER, $DATABASE_PASS, $DATABASE_NAME, $con, $type, $userId){
    echo "<br>";
    echo "Function: findSlot", "<br>";
    echo "<br>";

    $dateRequested = $_POST['date']; 
    $timeStart = $_POST['timeStart']; 
    $timeEnd = $_POST['timeEnd']; 

    echo "Date requested: ", $dateRequested, "<br>";
    echo "Time start: ", $timeStart, "<br>"; 
    echo "Time end: ", $timeEnd, "<br>"; 


    $stmt = $con->prepare('SELECT `SlotId`, `NumberUsers` FROM `Slot` WHERE `TimeStart` = ? AND `TimeFinish` = ? AND `Date` = ? ');
    $stmt->bind_param('sss', $timeStart, $timeEnd, $dateRequested);
            
    $stmt->execute();

    $slotId = ''; 
    $numberUsers = ''; 

    $stmt->bind_result($slotId, $numberUsers);

    $stmt->fetch();

    $stmt->close();

    if ($slotId != ''){
        echo "Slot Found", "<br>"; 
        echo "Slot Id: ", $slotId, "<br>";
        echo "Number of users: ", $numberUsers, "<br>";   
        checkBooking($DATABASE_HOST, $DATABASE_USER, $DATABASE_PASS, $DATABASE_NAME, $con, $slotId, $userId);
    } else {
        echo "Slot not found", "<br>"; 
    }
}; 

function checkMembership($DATABASE_HOST, $DATABASE_USER, $DATABASE_PASS, $DATABASE_NAME, $con, $type){
    echo "Function: checkMembership", "<br>";
    echo "<br>";
    $userId = $_SESSION['id'];
    echo "UserId: ", $userId, "<br>"; 
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

    echo $MemberShipId, "<br>"; 

    $stmt = $con->prepare('SELECT `StartDate`, `EndDate`,`Gym`, `Cardio` FROM `MembershipCore` INNER JOIN `MemberShipType` ON `MemberShipCore`.`MemberShipId` = `MemberShipType`.`MemberShipId` WHERE `MemberShipCore`.`MemberShipId` = ? ORDER BY StartDate;');
    $stmt->bind_param('s', $MemberShipId);
    $stmt->execute();
    $stmt->store_result();
    // What if there is multiple sets of memberships? --> Order by start date --> Will give lates membership
    $stmt->bind_result($MemberStartDate, $MemberEndDate, $Gym, $Cardio);

    $stmt->fetch();

    echo "MemberStartDate: ", $MemberStartDate, "<br>"; 
    echo "MemberEndDate", $MemberEndDate, "<br>"; 
    echo "Gym: ", $Gym, "<br>"; 
    echo 'Cardio: ', $Cardio, "<br>"; 
    echo 'Type: ', $type, "<br>"; 

    $currentDate = date("Y/m/d");
    echo "Today's date: ", $currentDate, "<br>"; 

    if ($type == 'cardio' && $Cardio == '1'){
        echo "Membership is valid for type requested", "<br>"; 
        if (strtotime($currentDate) < strtotime($MemberEndDate)){
            echo "Date is valid", "<br>";
            findSlot($DATABASE_HOST, $DATABASE_USER, $DATABASE_PASS, $DATABASE_NAME, $con, $type, $userId);
           
        } elseif (strtotime($currentDate) > strtotime($MemberEndDate)){
            // echo "<script>alert('Your membership is not valid to access the cardio gym');document.location='booking.html'</script>";
            echo "You membership is not valid to access the cardio gym", "<br>";
        }
        

    } elseif ($type == 'weights' && $Gym == '1'){
        echo "Membership is valid for type requested", "<br>"; 
        if (strtotime($currentDate) < strtotime($MemberEndDate)){
            echo "Date is valid", "<br>";
            findSlot($DATABASE_HOST, $DATABASE_USER, $DATABASE_PASS, $DATABASE_NAME, $con, $type, $userId);
            
        } elseif (strtotime($currentDate) > strtotime($MemberEndDate)){
            // echo "<script>alert('Your membership is not valid to access the weights gym');document.location='booking.html'</script>";
            echo "You membership is not valid to access the weights gym", "<br>";
        }
 
    } else {
        // echo "<script>alert('Your membership is not valid');document.location='booking.html'</script>";
        echo "You membership is not valid", "<br>";

    }




}; 

$type = $_POST['gymType']; 
checkMembership($DATABASE_HOST, $DATABASE_USER, $DATABASE_PASS, $DATABASE_NAME, $con, $type)

?> 