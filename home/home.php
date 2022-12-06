<?php

session_start();
// If the user is not logged in redirect to the login page...
if (!isset($_SESSION['loggedin'])) {
	header('Location: ../logon/index.html');
	exit;
}
?>

<!DOCTYPE html>
<html>
	<head>
		<meta charset="utf-8">
		<title>Gym</title>
		<link href="../styles/skeleton.css" rel="stylesheet" type="text/css">
		<link href="../styles/style.css" rel="stylesheet" type="text/css">
		<link href="../styles/normalize.css" rel="stylesheet" type="text/css"> 
		
		<link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.7.1/css/all.css">
	</head>
	<body class="loggedin">
		<nav class="navtop">
			<div>
				<h1>Gym Home</h1>
				<a href="../membership/membership.html"><i class="fas fa-table"></i>Manage Memberships</a>
				<a href="../booking/booking.html"><i class="fas fa-book"></i>Booking</a>
				<a href="../home/profile.php"><i class="fas fa-user-circle"></i>Profile</a>
				<a href="../logon/logout.php"><i class="fas fa-sign-out-alt"></i>Logout</a>
				
				
			</div>
		</nav>
		<div class="content">
			<h2>Gym Home Page</h2>
			<p>Welcome back, <?=$_SESSION['name']?>!</p>
			<p>For bookings, please go to the bookings tab</p>


			
		</div>

		<div class="content">
			<h2>Notices</h2>

			<h1>Notice 1</h1>

			<p>
			Lorem ipsum dolor sit amet, consectetur adipiscing elit. Ut eu luctus lectus, sed convallis tellus. Quisque sodales erat in pharetra iaculis. Nullam iaculis leo quis sapien cursus, sed varius urna mattis. Fusce aliquet condimentum augue non blandit. Donec aliquam mollis pellentesque. Fusce facilisis mattis nibh. Vivamus semper tortor eu convallis dictum. Cras sollicitudin ipsum nec tempor aliquet. Vestibulum sit amet lorem in eros sagittis laoreet.
			</p>

			<h1>Notice 2</h1>

			<p>
				Lorem ipsum dolor sit amet, consectetur adipiscing elit. Ut eu luctus lectus, sed convallis tellus. Quisque sodales erat in pharetra iaculis. Nullam iaculis leo quis sapien cursus, sed varius urna mattis. Fusce aliquet condimentum augue non blandit. Donec aliquam mollis pellentesque. Fusce facilisis mattis nibh. Vivamus semper tortor eu convallis dictum. Cras sollicitudin ipsum nec tempor aliquet. Vestibulum sit amet lorem in eros sagittis laoreet.
			</p>
		</div>
	</body>
</html>