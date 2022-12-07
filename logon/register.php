<?php

ini_set('display_errors', 1);

ini_set('display_startup_errors', 1);

error_reporting(E_ALL);




if (!isset($_POST['username'], $_POST['password'], $_POST['email'], $_POST['firstName'], $_POST['lastName'], $_POST['phone'])) {
	exit('Please complete the registration form!');
}

if (empty($_POST['username']) || empty($_POST['password']) || empty($_POST['email'])) {
	exit('Please complete the registration form');
}

if (!filter_var($_POST['email'], FILTER_VALIDATE_EMAIL)) {
	exit('Email is not valid!');
}

if (preg_match('/^[a-zA-Z0-9]+$/', $_POST['username']) == 0) {
    exit('Username is not valid!');
}

if (strlen($_POST['password']) > 20 || strlen($_POST['password']) < 5) {
	exit('Password must be between 5 and 20 characters long!');
}

function newId($DATABASE_HOST, $DATABASE_USER, $DATABASE_PASS, $DATABASE_NAME, $con) {

    $stmt = $con->prepare('SELECT MAX(UserId) FROM `User`');
  
    $stmt->execute();

    $newId = ''; 

    $stmt->bind_result($newId);

    $stmt->fetch();

    $stmt->close();

    $newId++; 

    return $newId; 

}

function returnToBooking($message){
	echo "returnToBooking", "<br>"; 
	echo "<script>alert('Sorry, the details you have entered are already in use. Please try another one');document.location='register.html'</script>";
}

function checkEmail($DATABASE_HOST, $DATABASE_USER, $DATABASE_PASS, $DATABASE_NAME, $con, $email){
	$stmt = $con->prepare('SELECT `email` FROM `user` WHERE `email` = ?');
		
	$stmt->bind_param('s', $email);
		
	$stmt->execute();

	$checkEmailParam = ''; 

	$stmt->bind_result($checkEmailParam);

    $stmt->fetch();

    $stmt->close();

	if ($checkEmailParam == ''){
		echo "Email is not already in use... proceed", "<br>";
		return 1; 
	} else {
		echo "Email is already in use.. do not proceed", "<br>"; 
		$message = ''; 
		$message = 'Email'; 
		returnToBooking($message); 
		return 0; 
	}

    
}

function checkPhone($DATABASE_HOST, $DATABASE_USER, $DATABASE_PASS, $DATABASE_NAME, $con, $phone){
	$stmt = $con->prepare('SELECT `Phone` FROM `user` WHERE `Phone` = ?');
	$stmt->bind_param('s', $phone); 
		
	$stmt->execute();

	$checkPhoneParam = ''; 

	$stmt->bind_result($checkPhoneParam);

    $stmt->fetch();

    $stmt->close();

	if ($checkPhoneParam == ''){
		echo "Phone Number is not already in use... proceed", "<br>";
		return 1; 
	} else {
		echo "Phone number is already in use.. do not proceed", "<br>"; 
		$message = ''; 
		$message = 'Phone Number'; 
		returnToBooking($message); 
		return 0; 
	}


  
    
}

function checkUsername($DATABASE_HOST, $DATABASE_USER, $DATABASE_PASS, $DATABASE_NAME, $con, $username){
	$stmt = $con->prepare('SELECT `Username` FROM `user` WHERE `Username` = ?');
		
		
	$stmt->bind_param('s', $username); 
	$stmt->execute();
	$checkUsernameParam = ''; 
	
	$stmt->bind_result($checkUsernameParam);

    $stmt->fetch();

    $stmt->close();

	if ($checkUsernameParam == ''){
		echo "Username is not already in use... proceed", "<br>";
		return 1; 
	} else {
		echo "Username is already in use.. do not proceed", "<br>"; 
		$message = ''; 
		$message = 'Phone Number'; 
		returnToBooking($message); 
		return 0; 
	}

  
    
}

function insertVar($DATABASE_HOST, $DATABASE_USER, $DATABASE_PASS, $DATABASE_NAME, $con, $userId, $firstName, $lastName, $phone, $email, $username, $password){
	$stmt = $con->prepare('INSERT INTO `User`(`UserId`, `Username`, `Password`, `LastName`, `FirstName`, `Email`, `Phone`, `Admin`) VALUES (?, ?, ?, ?, ?, ?, ?, ?)');
    
	$admin = 0; 

	$stmt->bind_param('ssssssss', $userId, $username, $password, $lastName, $firstName, $email, $phone, $admin); 

    $stmt->execute();

    

    $stmt->close();

	echo "Account created"; 

}



function collectVar($DATABASE_HOST, $DATABASE_USER, $DATABASE_PASS, $DATABASE_NAME, $con){
	$username = $_POST['username']; 
	$password = $_POST['password']; 
	$email = $_POST['email']; 
	$firstName = $_POST['firstName']; 
	$lastName = $_POST['lastName']; 
	$phone = $_POST['phone']; 

	echo "Username: ", $username, "<br>"; 
	echo "Password: ", $password, "<br>"; 
	echo "Email: ", $email, "<br>"; 
	echo "First Name: ", $firstName, "<br>"; 
	echo "Last Name: ", $lastName, "<br>"; 
	echo "Phone: ", $phone, "<br>"; 

	$userId = newId($DATABASE_HOST, $DATABASE_USER, $DATABASE_PASS, $DATABASE_NAME, $con); 

	$checkUsername = checkUsername($DATABASE_HOST, $DATABASE_USER, $DATABASE_PASS, $DATABASE_NAME, $con, $username);
	$checkPhone = checkPhone($DATABASE_HOST, $DATABASE_USER, $DATABASE_PASS, $DATABASE_NAME, $con, $phone);
	$checkEmail = checkEmail($DATABASE_HOST, $DATABASE_USER, $DATABASE_PASS, $DATABASE_NAME, $con, $email);

	echo "checkUsername: ", $checkUsername, "<br>"; 
	echo "checkPhone: ", $checkPhone, "<br>"; 
	echo "checkEmail: ", $checkEmail, "<br>"; 

	if ($checkUsername == 1){
		if ($checkPhone == 1 ){
			if ($checkEmail == 1){
				echo "Username, Phone and Email all unique... proceed";
				insertVar($DATABASE_HOST, $DATABASE_USER, $DATABASE_PASS, $DATABASE_NAME, $con, $userId, $firstName, $lastName, $phone, $email, $username, $password); 
			}
			
		} 
		
	} 
}

$DATABASE_HOST = 'localhost';

$DATABASE_USER = 'root';

$DATABASE_PASS = '';

$DATABASE_NAME = 'phplogin';

$con = mysqli_connect($DATABASE_HOST, $DATABASE_USER, $DATABASE_PASS, $DATABASE_NAME);

if (mysqli_connect_errno()) {
	exit('Failed to connect to MySQL: ' . mysqli_connect_error());
}

collectVar($DATABASE_HOST, $DATABASE_USER, $DATABASE_PASS, $DATABASE_NAME, $con); 

?>