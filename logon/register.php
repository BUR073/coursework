<?php

ini_set('display_errors', 1);

ini_set('display_startup_errors', 1);

error_reporting(E_ALL);

$DATABASE_HOST = 'localhost';

$DATABASE_USER = 'root';

$DATABASE_PASS = '';

$DATABASE_NAME = 'phplogin2';

$con = mysqli_connect($DATABASE_HOST, $DATABASE_USER, $DATABASE_PASS, $DATABASE_NAME);

if (mysqli_connect_errno()) {
	exit('Failed to connect to MySQL: ' . mysqli_connect_error());
}


if (!isset($_POST['username'], $_POST['password'], $_POST['email'])) {
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

if ($stmt = $con->prepare('SELECT id, password FROM accounts WHERE username = ?')) {
	
	$stmt->bind_param('s', $_POST['username']);

	$stmt->execute();

	$stmt->store_result();

	if ($stmt->num_rows > 0) {

		echo 'Username exists, please choose another!';

	} else {

			if($stmt = $con->prepare("SELECT MAX(id) as new_id FROM accounts;")) {
				
			}


            if ($stmt = $con->prepare('INSERT INTO accounts (username, password) VALUES (?, ?)')) {

				$password = $_POST['password'];

				$membershipType = 'None';

				$admin = '0';

				$stmt->bind_param('ss', $_POST['username'], $password);

				$stmt->execute();

				echo "<script>alert('Thanks for registering, please login');document.location='index.html'</script>";
	           
} else {
	
	echo "<script>alert('Could not prepare statement, please try again');document.location='register.html'</script>";
}
	}
	$stmt->close();

} else {
	
	echo "<script>alert('Could not prepare statement, please try again');document.location='register.html'</script>";
}
$con->close();
?>