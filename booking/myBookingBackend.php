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
$id = ''; 
if(isset($_POST["bookingId"])){
    $id = $_POST['bookingId']; 
    echo $id;
} else{
    echo "Nothing"; 
}


?> 