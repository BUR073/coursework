<?php
// We need to use sessions, so you should always start sessions using the below code.
session_start();
// If the user is not logged in redirect to the login page...
if (!isset($_SESSION['loggedin'])) {
	header('Location: index.html');
	exit;
}
?>

<!DOCTYPE html>
<html>
	<head>
		<meta charset="utf-8">
		<title>Admin</title>
		<link href="../styles/style.css" rel="stylesheet" type="text/css">
		<link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.7.1/css/all.css">
	</head>
	<body class="loggedin">
		<nav class="navtop">
			<div>
				<h1>Gym Home</h1>
				<a href=""><i class="fas fa-book"></i>Booking</a>
				<a href="adminContactUs.php"><i class="fas fa-address-book"></i>Contact Us</a>
				<a href="../home/profile.php"><i class="fas fa-user-circle"></i>Profile</a>
				<a href="../logon/logout.php"><i class="fas fa-sign-out-alt"></i>Logout</a>
				
			</div>
		</nav>
		<div class="content">
			<h2>Admin</h2>
			<p>Welcome, <?=$_SESSION['name']?>!</p>


			
		</div>
	</body>
</html>

