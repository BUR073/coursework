<?php

ini_set('display_errors', 1);

ini_set('display_startup_errors', 1);

error_reporting(E_ALL);

session_start();
// If the user is not logged in redirect to the login page...
if (!isset($_SESSION['loggedin'])) {
	header('Location: ../logon/index.html');
	exit;
}

if(isset($_POST['action'])){
    echo $_POST['action']; ;
} else {
    echo "error"; 
}; 

$DATABASE_HOST = 'localhost';
$DATABASE_USER = 'root';
$DATABASE_PASS = '';
$DATABASE_NAME = 'phplogin';
$con = mysqli_connect($DATABASE_HOST, $DATABASE_USER, $DATABASE_PASS, $DATABASE_NAME);

function findMembership($DATABASE_HOST, $DATABASE_USER, $DATABASE_PASS, $DATABASE_NAME, $con){

    $userId = $_SESSION['id'];

    // Get user Id from session variable

    $MemberShipId = ''; 
    $memberStartDate = ''; 
    $memberEndDate = ''; 
    $gym = ''; 
    $cardio = ''; 

    // Define required varibale for sql below

    // The SQL statement below queries the table `MemberShipCore` to find the users
    // MemberShipId which is then stored in the variable $MemberShipId
    
    $stmt = $con->prepare('SELECT `MemberShipId` FROM `MemberShipCore` Where `UserId` = ?');
    $stmt->bind_param('s', $userId);
    $stmt->execute(); 
    $stmt->store_result(); 
    $stmt->bind_result($MemberShipId); 
    $stmt->fetch(); 

    $result = ''; 

    $stmt = $con->prepare('SELECT `StartDate`, `EndDate`,`Gym`, `Cardio` FROM `MembershipCore` INNER JOIN `MemberShipType` ON `MemberShipCore`.`MemberShipId` = `MemberShipType`.`MemberShipId` WHERE `MemberShipCore`.`MemberShipId` = ? ORDER BY StartDate;');
    $stmt->bind_param('s', $MemberShipId);
    $stmt->execute();
    $stmt->store_result();
    // What if there is multiple sets of memberships? --> Order by start date --> Will give lates membership
    $stmt->bind_result($memberStartDate, $memberEndDate, $gym, $cardio);

    $stmt->fetch();


    $type = '';
    if ($gym == 1 && $cardio == 1){
        $type = 'combined';
    } elseif ($gym == 1 && $cardio == 0){
        $type = 'gym';
    } elseif ($gym == 0 && $cardio == 1){
        $type = 'cardio'; 
    } elseif ($gym == 0 && $cardio == 0){
        $type = 'none'; 
    }; 

    echo "<br>", "Type:", $type; 

}; 

findMembership($DATABASE_HOST, $DATABASE_USER, $DATABASE_PASS, $DATABASE_NAME, $con);


?> 
