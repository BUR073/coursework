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
   

    $orderId = newOrderId($DATABASE_HOST, $DATABASE_USER, $DATABASE_PASS, $DATABASE_NAME, $con); 


    $notes = ''; 

    $stmt = $con->prepare('INSERT INTO `Order`(`OrderId`, `UserId`, `Notes`, `SlotId`) VALUES (?, ?, ?, ?)');
    $stmt->bind_param('ssss', $orderId, $userId, $notes, $slotId); 
    $stmt->execute(); 



    $numberUsers = newNumberUsers($DATABASE_HOST, $DATABASE_USER, $DATABASE_PASS, $DATABASE_NAME, $con, $slotId); 


    $stmt = $con->prepare('UPDATE `Slot` SET `NumberUsers`= ?  WHERE `SlotId` = ?');
    $stmt->bind_param('ss', $numberUsers, $slotId); 
    $stmt->execute(); 
    $stmt->close(); 


    echo "<script>alert('Gym session booked');document.location='booking.html'</script>";
}; 

function checkBooking($DATABASE_HOST, $DATABASE_USER, $DATABASE_PASS, $DATABASE_NAME, $con, $slotId, $userId){
    
    $stmt = $con->prepare('SELECT `OrderId` FROM `Order` WHERE `UserId` = ? AND `SlotId` = ?');
    $stmt->bind_param('ss', $userId, $slotId ); 
    $stmt->execute(); 
    $orderId = ''; 
    $stmt->bind_result($orderId); 
    $stmt->fetch(); 
    $stmt->close(); 


    if ($orderId == ''){
        book($DATABASE_HOST, $DATABASE_USER, $DATABASE_PASS, $DATABASE_NAME, $con, $slotId, $userId); 
    } else {
        echo "<script>alert('You cannot book the same slot more than once');document.location='booking.html'</script>";
    }; 
}; 
function convertTypeBool($type){
    if ($type == 'weights'){
        return 1; 
    } elseif ($type == 'cardio'){
        return 0; 
    }; 
};

function findSlot($DATABASE_HOST, $DATABASE_USER, $DATABASE_PASS, $DATABASE_NAME, $con, $type, $userId){
    // 0 - Cardio
    // 1 - weights


    $dateRequested = $_POST['date']; 
    $timeStart = $_POST['timeStart']; 
    $timeEnd = $_POST['timeEnd']; 
    $type = convertTypeBool($type); 



    $stmt = $con->prepare('SELECT `SlotId`, `NumberUsers` FROM `Slot` WHERE `TimeStart` = ? AND `TimeFinish` = ? AND `Date` = ? AND `Type` = ?');
    $stmt->bind_param('ssss', $timeStart, $timeEnd, $dateRequested, $type);
            
    $stmt->execute();

    $slotId = ''; 
    $numberUsers = ''; 
    

    $stmt->bind_result($slotId, $numberUsers);

    $stmt->fetch();

    $stmt->close();


    if ($slotId != ''){
        
        if ($numberUsers < 20){
            checkBooking($DATABASE_HOST, $DATABASE_USER, $DATABASE_PASS, $DATABASE_NAME, $con, $slotId, $userId);
        } else {
            echo "<script>alert('Sorry, this session is fully booked');document.location='booking.html'</script>"; 
        }
           
    } else {
        echo "<script>alert('Session not found, please refer to the timetable');document.location='booking.html'</script>";
    }
}; 

function checkMembership($DATABASE_HOST, $DATABASE_USER, $DATABASE_PASS, $DATABASE_NAME, $con, $type){

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



    $stmt = $con->prepare('SELECT `StartDate`, `EndDate`,`Gym`, `Cardio` FROM `MembershipCore` INNER JOIN `MemberShipType` ON `MemberShipCore`.`MemberShipId` = `MemberShipType`.`MemberShipId` WHERE `MemberShipCore`.`MemberShipId` = ? ORDER BY StartDate;');
    $stmt->bind_param('s', $MemberShipId);
    $stmt->execute();
    $stmt->store_result();
    // What if there is multiple sets of memberships? --> Order by start date --> Will give lates membership
    $stmt->bind_result($MemberStartDate, $MemberEndDate, $Gym, $Cardio);

    $stmt->fetch();


    $currentDate = date("Y/m/d");


    if ($type == 'cardio' && $Cardio == '1'){
   
        if (strtotime($currentDate) < strtotime($MemberEndDate)){

            findSlot($DATABASE_HOST, $DATABASE_USER, $DATABASE_PASS, $DATABASE_NAME, $con, $type, $userId);
           
        } elseif (strtotime($currentDate) > strtotime($MemberEndDate)){
            echo "<script>alert('Your membership is not valid to book a session in the cardio gym');document.location='booking.html'</script>";
        }
        

    } elseif ($type == 'weights' && $Gym == '1'){

        if (strtotime($currentDate) < strtotime($MemberEndDate)){
  
            findSlot($DATABASE_HOST, $DATABASE_USER, $DATABASE_PASS, $DATABASE_NAME, $con, $type, $userId);
            
        } elseif (strtotime($currentDate) > strtotime($MemberEndDate)){

            echo "<script>alert('Your membership is not valid to access the weights gym');document.location='booking.html'</script>";
        }
 
    } else {
       
        echo "<script>alert('Your membership is not valid');document.location='booking.html'</script>";

    }




}; 

$type = $_POST['gymType']; 
checkMembership($DATABASE_HOST, $DATABASE_USER, $DATABASE_PASS, $DATABASE_NAME, $con, $type)

?> 