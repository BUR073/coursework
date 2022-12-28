<?php 
// TODO: Username validation
// TODO: Password validation 
// TODO: Make sure values are different
// TODO: Email validation
//if (!filter_var($_POST['email'], FILTER_VALIDATE_EMAIL)) {
	// exit('Email is not valid!');

ini_set('display_errors', 1);

ini_set('display_startup_errors', 1);

error_reporting(E_ALL);

session_start();
// If the user is not logged in redirect to the login page...
if (!isset($_SESSION['loggedin'])) {
	header('Location: index.html');
	exit;
}

$DATABASE_HOST = 'localhost';
$DATABASE_USER = 'root';
$DATABASE_PASS = '';
$DATABASE_NAME = 'phplogin';
// Try and connect using the info above.
$con = mysqli_connect($DATABASE_HOST, $DATABASE_USER, $DATABASE_PASS, $DATABASE_NAME);
if ( mysqli_connect_errno()) {
	// If there is an error with the connection, stop the script and display the error.
	exit('Failed to connect to MySQL: ' . mysqli_connect_error());
}; 


echo "Modal Handle", "<br>"; 
$type = ''; 
$original = ''; 
$new = ''; 

$type = $_POST['type']; 
$original = $_POST['original'];
$new = $_POST['new']; 

echo "Type: ", $type, "<br>";
echo "Original: ", $original, "<br>";
echo "New: ", $new, "<br>";

compareDetails($DATABASE_HOST, $DATABASE_USER, $DATABASE_PASS, $DATABASE_NAME, $con, $type, $new, $original); 

function updateDetails($DATABASE_HOST, $DATABASE_USER, $DATABASE_PASS, $DATABASE_NAME, $con, $type, $new, $original){
    echo "Function: Update Details", "<br>"; 
}; 

function validateUsername($DATABASE_HOST, $DATABASE_USER, $DATABASE_PASS, $DATABASE_NAME, $con, $type, $new, $original){
    echo "Function: Validate Username", "<br>"; 
    if (preg_match('/^[a-zA-Z0-9]+$/', $new) == 0) {
        echo('Username is not valid!');
    } else {
        updateDetails($DATABASE_HOST, $DATABASE_USER, $DATABASE_PASS, $DATABASE_NAME, $con, $type, $new, $original); 
    }; 
}; 

function validatePassword($DATABASE_HOST, $DATABASE_USER, $DATABASE_PASS, $DATABASE_NAME, $con, $type, $new, $original){
    echo "Function: Validate Password", "<br>"; 
    if (strlen($new) > 20 || strlen($new) < 5) {
        exit('Password must be between 5 and 20 characters long!');
    } else {
        updateDetails($DATABASE_HOST, $DATABASE_USER, $DATABASE_PASS, $DATABASE_NAME, $con, $type, $new, $original); 
    }; 
};



function validateEmail($DATABASE_HOST, $DATABASE_USER, $DATABASE_PASS, $DATABASE_NAME, $con, $type, $new, $original){
    echo "Function: Validate Email", "<br>"; 
    if (!filter_var($new, FILTER_VALIDATE_EMAIL)){
        exit('Email is not valid');
    } else {
        updateDetails($DATABASE_HOST, $DATABASE_USER, $DATABASE_PASS, $DATABASE_NAME, $con, $type, $new, $original);
    }
} 
  


function checkType($DATABASE_HOST, $DATABASE_USER, $DATABASE_PASS, $DATABASE_NAME, $con, $type, $new, $original){
    echo "Function: Check Type", "<br>"; 
    if ($type == 'Username'){
        validateUsername($DATABASE_HOST, $DATABASE_USER, $DATABASE_PASS, $DATABASE_NAME, $con, $type, $new, $original);
    } elseif ($type == 'Password') {
        validatePassword($DATABASE_HOST, $DATABASE_USER, $DATABASE_PASS, $DATABASE_NAME, $con, $type, $new, $original);
    } elseif ($type == 'Email') {
        validateEmail($DATABASE_HOST, $DATABASE_USER, $DATABASE_PASS, $DATABASE_NAME, $con, $type, $new, $original); 
    } else {
        updateDetails($DATABASE_HOST, $DATABASE_USER, $DATABASE_PASS, $DATABASE_NAME, $con, $type, $new, $original); 
    }
}; 

function checkOriginal($DATABASE_HOST, $DATABASE_USER, $DATABASE_PASS, $DATABASE_NAME, $con, $type, $new, $original){
    echo "Function: Check Original", "<br>"; 
    $id = $_SESSION['id']; 
    echo "UserID: ", $id,  "<br>"; 

    $stmt = $con->prepare('SELECT ? FROM `User` WHERE ? = ? AND `UserId` = ?');

    $stmt->bind_param('sssi', $type, $type, $original, $id);
  
    $stmt->execute();

    $originalDb = ''; 

    $stmt->bind_result($originalDb);

    $stmt->fetch();

    $stmt->close();

    echo "Original detail from db: ", $originalDb, "<br>"; 

    // checkType($DATABASE_HOST, $DATABASE_USER, $DATABASE_PASS, $DATABASE_NAME, $con, $type, $new, $original); 
}; 

function compareDetails($DATABASE_HOST, $DATABASE_USER, $DATABASE_PASS, $DATABASE_NAME, $con, $type, $new, $original){
    echo "Function: Compare Details", "<br>"; 
    if ($new == $original){
        echo "Details cannot be the same, do not proceed", "<br>"; 
        return 0; 
    } else {
        echo "Details are different, proceed", "<br>"; 
        checkOriginal($DATABASE_HOST, $DATABASE_USER, $DATABASE_PASS, $DATABASE_NAME, $con, $type, $new, $original); 
        return 1; 
    }; 
}; 

?> 