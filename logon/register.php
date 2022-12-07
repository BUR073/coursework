<?php

ini_set('display_errors', 1);

ini_set('display_startup_errors', 1);

error_reporting(E_ALL);

$


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

function checkEmail($DATABASE_HOST, $DATABASE_USER, $DATABASE_PASS, $DATABASE_NAME, $con, $email){
	if ($stmt = $con->prepare('SELECT `email` FROM `user` WHERE `email` = ?')){
		$stmt->execute();

    	$stmt->bind_param('s', $email); 

    	$stmt->close();
	} else{
		echo "Email is not already in use... proceed"; 
		return 0; 
	}
  
    
}

function checkPhone($DATABASE_HOST, $DATABASE_USER, $DATABASE_PASS, $DATABASE_NAME, $con, $phone){
	if ($stmt = $con->prepare('SELECT `Phone` FROM `user` WHERE `Phone` = ?')){
		$stmt->execute();

    	$stmt->bind_param('s', $phone); 

    	$stmt->close();
	} else{
		echo "Phone is not already in use... proceed"; 
		return 0; 
	}
  
    
}

function checkUsername($DATABASE_HOST, $DATABASE_USER, $DATABASE_PASS, $DATABASE_NAME, $con, $username){
	if ($stmt = $con->prepare('SELECT `Username` FROM `user` WHERE `Username` = ?')){
		$stmt->execute();

    	$stmt->bind_param('s', $username); 

    	$stmt->close();
	} else{
		echo "Username is not already in use... proceed"; 
		return 0; 
	}
  
    
}

function insertVar($DATABASE_HOST, $DATABASE_USER, $DATABASE_PASS, $DATABASE_NAME, $con, $userId, $firstName, $lastName, $phone, $email, $username, $password){
	$stmt = $con->prepare('INSERT INTO `User`(`UserId`, `Username`, `Password`, `LastName`, `FirstName`, `Email`, `Phone`) VALUES (?, ?, ?, ?, ?, ?, ?)');
  
    $stmt->execute();

    $stmt->bind_param('sssssss', $userId, $username, $password, $lastName, $firstName, $email, $phone); 

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
	$userId = newId($DATABASE_HOST, $DATABASE_USER, $DATABASE_PASS, $DATABASE_NAME, $con); 

	$checkUsername = checkUsername($DATABASE_HOST, $DATABASE_USER, $DATABASE_PASS, $DATABASE_NAME, $con, $username);
	$checkPhone = checkPhone($DATABASE_HOST, $DATABASE_USER, $DATABASE_PASS, $DATABASE_NAME, $con, $phone);
	$checkEmail = checkEmail($DATABASE_HOST, $DATABASE_USER, $DATABASE_PASS, $DATABASE_NAME, $con, $email);

	if ($checkUsername == 0){
		if ($checkPhone == 0 ){
			if ($checkEmail ==0){
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