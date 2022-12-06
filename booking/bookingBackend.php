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

// function CheckSlotDate($DATABASE_HOST, $DATABASE_USER, $DATABASE_PASS, $DATABASE_NAME, $slotId){
//     $date = ''; 
//     $con = mysqli_connect($DATABASE_HOST, $DATABASE_USER, $DATABASE_PASS, $DATABASE_NAME);
//     $stmt = $con->prepare('SELECT `Date` FROM `Slot` WHERE `SlotId` = ?');
//     $stmt->bind_param('s', $slotId);
//     $stmt->execute(); 
//     $stmt->store_result(); 
//     $stmt->bind_result($date); 
//     $stmt->fetch();
//     return $date; 
// }

function goHome(){
    echo "<script>alert('Sorry, you may only book one session per day');";
    header("Location:booking.html");
    // Alert does not show as page changes too qucik
    
}

function findSlot($DATABASE_HOST, $DATABASE_USER, $DATABASE_PASS, $DATABASE_NAME, $timeStart, $slotDate){
    $con = mysqli_connect($DATABASE_HOST, $DATABASE_USER, $DATABASE_PASS, $DATABASE_NAME);
    $stmt = $con->prepare('SELECT `SlotId` FROM `Slot` WHERE `TimeStart` = ? AND `Date` = ? ');
    $stmt->bind_param('ss', $timeStart, $slotDate);
    $stmt->execute(); 
    $stmt->store_result(); 
    $stmt->bind_result($slotId); 
    $stmt->fetch(); 

    return $slotId; 
    
}
function checkUserBookings($DATABASE_HOST, $DATABASE_USER, $DATABASE_PASS, $DATABASE_NAME, $userId, $Date){
    $slotId = ''; 

    $timeStart = $_POST['timeStart'];

    $slotDate = $_POST['date']; 

    $slotRequested = findSlot($DATABASE_HOST, $DATABASE_USER, $DATABASE_PASS, $DATABASE_NAME, $timeStart, $slotDate);

    $bookingDate = $_POST['date'];

    $con = mysqli_connect($DATABASE_HOST, $DATABASE_USER, $DATABASE_PASS, $DATABASE_NAME);

    $stmt = $con->prepare('SELECT `SlotId` FROM `Order` WHERE `UserId` = ?');

    $stmt->bind_param('s', $userId);

    $stmt->execute(); 

    $stmt->bind_result($slotId); 

    while($stmt->fetch()) {

        // $slotDate = CheckSlotDate($DATABASE_HOST, $DATABASE_USER, $DATABASE_PASS, $DATABASE_NAME, $slotId);

        echo "<br>";
        echo "<br>";
        echo "SlotId: ", $slotId, "<br>";
        echo "Slot Requested: ", $slotRequested, "<br>"; 
        // echo "Date: ", $slotDate;  

        if ($slotId == $slotRequested){

            echo "<br>", "Error: Cannot book two sessions on one day";

            // goHome(); 

        }
    }
 

}

function MemberShipValid($date, $MemberEndDate, $BookingType, $Gym, $Cardio){

    if ($date<$MemberEndDate){

        $dateValid = '1'; 

        echo "dateValid: ", $dateValid, "<br>"; 

        if ($BookingType = 'cardio' && $cardio = '1'){

            return 1;

        } else { 

            if ($BookingType = 'weights' && $Gym = '1'){

                return 1; 
            }
        }

    }else{ 

        $dateValid = '0';

        echo "dateValid: ", $dateValid, "<br>"; 

        return 0; 
    }
}


function UserDetail($DATABASE_HOST, $DATABASE_USER, $DATABASE_PASS, $DATABASE_NAME, $con){
    $userId = $_SESSION['id'];
    // Get user Id from session variable

    $MemberShipId = ''; 
    $MemberStartDate = ''; 
    $MemberEndDate = ''; 
    $Gym = ''; 
    $Cardio = ''; 

    // Define required varibale for sql below
    
    $stmt = $con->prepare('SELECT `MemberShipId` FROM `MemberShipCore` Where `UserId` = ?');
    $stmt->bind_param('s', $userId);
    $stmt->execute(); 
    $stmt->store_result(); 
    $stmt->bind_result($MemberShipId); 
    $stmt->fetch(); 

    echo $MemberShipId, "<br>"; 

    $stmt = $con->prepare('SELECT `StartDate`, `EndDate`,`Gym`, `Cardio` FROM `MembershipCore` INNER JOIN `MemberShipType` ON `MemberShipCore`.`MemberShipId` = `MemberShipType`.`MemberShipId` WHERE `MemberShipCore`.`MemberShipId` = ? ORDER BY StartDate;');

    // SELECT column_name(s) FROM table1 FULL OUTER JOIN table2 ON table1.column_name = table2.column_name WHERE condition;
    // In this case we can use the account ID to get the account info.
    $stmt->bind_param('s', $MemberShipId);
    $stmt->execute();
    $stmt->store_result();
    // What if there is multiple sets of memberships? --> Order by start date --> Will give lates membership
    $stmt->bind_result($MemberStartDate, $MemberEndDate, $Gym, $Cardio);

    $stmt->fetch();

    echo "MemberStartDate: ", $MemberStartDate, "<br>"; 
    echo "MemberEndDate", $MemberEndDate, "<br>"; 
    echo "Gym", $Gym, "<br>"; 
    echo 'Cardio', $Cardio, "<br>"; 

    $date = date("Y/m/d"); 
    $GymType = $_POST['gymType']; 

    $MemberShipValid = MemberShipValid($date, $MemberEndDate, $GymType, $Gym, $Cardio); 

    if ($MemberShipValid = 1) {
        echo "<br>";
        echo "Membership is valid... Proceed to booking";
        $date = $_POST['date']; 
        checkUserBookings($DATABASE_HOST, $DATABASE_USER, $DATABASE_PASS, $DATABASE_NAME, $userId, $date); 
    } else {
        echo "<br>";
        echo "Membership is invalid... return to homepage";
    }

}

UserDetail($DATABASE_HOST, $DATABASE_USER, $DATABASE_PASS, $DATABASE_NAME, $con); 