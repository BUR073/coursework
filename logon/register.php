<?php

ini_set('display_errors', 1);

ini_set('display_startup_errors', 1);

error_reporting(E_ALL);



// This checks that all fields where filled in on the html form

if (!isset($_POST['username'], $_POST['password'], $_POST['email'], $_POST['firstName'], $_POST['lastName'], $_POST['phone'])) {
	exit('Please complete the registration form!');
}

if (empty($_POST['username']) || empty($_POST['password']) || empty($_POST['email'])) {
	exit('Please complete the registration form');
}

// Checks that email is a valid email

if (!filter_var($_POST['email'], FILTER_VALIDATE_EMAIL)) {
	exit('Email is not valid!');
}

// Checks that the username and password are both valid

if (preg_match('/^[a-zA-Z0-9]+$/', $_POST['username']) == 0) {
    exit('Username is not valid!');
}

if (strlen($_POST['password']) > 20 || strlen($_POST['password']) < 5) {
	exit('Password must be between 5 and 20 characters long!');
}

// Function newId takes all required database connection variables
// It then finds the highest UserId and adds 1
// This new UserId is then returned

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

// returnToRegister is a short function that does not take any paramaters
// It displays a message tellign the user that on of the their inputs
// is already in use by another user. Specifically which input is not
// told to the user for privacy and security reasons 

function returnToRegister(){
	echo "returnToBooking", "<br>"; 
	echo "<script>alert('Sorry, the details you have entered are already in use. Please try another one');document.location='register.html'</script>";
}

// checkEmail is one of three similar functions. It takes all the 
// required database connection variables as well as the 
// email inputted by the user in the html form
// It then checks wether that email is already being user by another
// user. If it is it returns a 1 otherwise it calls the returntToBooking function
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
		returnToRegister(); 
		return 0; 
	}

    
}
// checkPhone is the 2nd out of the 3 similar functions
// It has the same purpose as checkEmail apart from it 
// checks wether the phone number inputted by the user
// is already in use

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
		returnToRegister($message); 
		return 0; 
	}


  
    
}

// checkUsername is the last of the 3 similar functions
// It has the same basic function as the previous two 
// but checks wether the username is already in use instead
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
		returnToRegister($message); 
		return 0; 
	}

  
    
}
// This short function returns the user
// to the login page after they have 
// created their account
function returnToLogin(){
	echo "<script>alert('Acount creation successful');document.location='index.html'</script>";
}

// insertVar takes all the database connection variables plus all
// the details inputted by the user and the new userId
// These details are then inserted into the database to complete
// the account creation 
function insertVar($DATABASE_HOST, $DATABASE_USER, $DATABASE_PASS, $DATABASE_NAME, $con, $userId, $firstName, $lastName, $phone, $email, $username, $password){
	$stmt = $con->prepare('INSERT INTO `User`(`UserId`, `Username`, `Password`, `LastName`, `FirstName`, `Email`, `Phone`, `Admin`) VALUES (?, ?, ?, ?, ?, ?, ?, ?)');
    
	$admin = 0; 

	$stmt->bind_param('ssssssss', $userId, $username, $password, $lastName, $firstName, $email, $phone, $admin); 

    $stmt->execute();

    

    $stmt->close();

	echo "Account created"; 

}


// collectVar collects all the required details for the account 
// creation and stores them in variables. It also does the final
// checks to make sure that the username, email and phone number
// are not already in use by another user by calling the check 
// functions. Then the insertVar functions is called to insert
// all the details and create the account for the user
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
// Below are the database connection variables

$DATABASE_HOST = 'localhost';

$DATABASE_USER = 'root';

$DATABASE_PASS = '';

$DATABASE_NAME = 'phplogin';

$con = mysqli_connect($DATABASE_HOST, $DATABASE_USER, $DATABASE_PASS, $DATABASE_NAME);

if (mysqli_connect_errno()) {
	exit('Failed to connect to MySQL: ' . mysqli_connect_error());
}

// This starts of the program
collectVar($DATABASE_HOST, $DATABASE_USER, $DATABASE_PASS, $DATABASE_NAME, $con); 

?>