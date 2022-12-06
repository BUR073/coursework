
<html>
	<head>
		<meta charset="utf-8">
		<title>Gym</title>
		<link href="../styles/style.css" rel="stylesheet" type="text/css">
		<link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.7.1/css/all.css">
	</head>
	<body class="loggedin">
		<nav class="navtop">
			<div>
				<h1>Gym Home</h1>
				<a href=""><i class="fas fa-book"></i>Booking</a>
				<a href="../contactUs/contactUs.html"><i class="fas fa-address-book"></i>Contact Us</a>
				<a href="../home/profile.php"><i class="fas fa-user-circle"></i>Profile</a>
				<a href="../logon/logout.php"><i class="fas fa-sign-out-alt"></i>Logout</a>
				
				
			</div>
		</nav>
		<div class="content">
			<h2>Gym Membership</h2>
            <p>
                Your current Membership: 
            </p>
</html>

<?php

ini_set('display_errors', 1);

ini_set('display_startup_errors', 1);

error_reporting(E_ALL);


$id = $_SESSION['id']; 

$DATABASE_HOST = 'localhost';

$DATABASE_USER = 'root';

$DATABASE_PASS = '';

$DATABASE_NAME = 'phplogin';

$con = mysqli_connect($DATABASE_HOST, $DATABASE_USER, $DATABASE_PASS, $DATABASE_NAME);

if (mysqli_connect_errno()) {
	exit('Failed to connect to MySQL: ' . mysqli_connect_error());
}

$stmt = $con->prepare('SELECT membershipType, membershipLength, membershipStartDate, membershipEndDate FROM membership WHERE accountId = ?');
// In this case we can use the account ID to get the account info.
$stmt->bind_param('i', $_SESSION['id']);
$stmt->execute();


$stmt->bind_result($membershipType, $membershipLength, $membershipStartDate, $membershipEndDate);

$stmt->fetch();

$stmt->close();




?> 

			
                