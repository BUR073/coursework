<!DOCTYPE html>
<html>
	<head>
		<meta charset="utf-8">
		<title>Admin</title>
		<link href="../style/style.css" rel="stylesheet" type="text/css">
        <link href="../styles/skeleton.css" rel="stylesheet" type="text/css">
		<link href="../styles/style.css" rel="stylesheet" type="text/css">
		<link href="../styles/normalize.css" rel="stylesheet" type="text/css"> 
		
		<link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.7.1/css/all.css">
	</head>
	<body class="loggedin">
		<nav class="navtop">
			<div>
				<h1>Slots</h1>
				<a href="../logon/logout.php"><i class="fas fa-sign-out-alt"></i>Logout</a>
				
			</div>
		</nav>
		<div class="content">
			<h2>Admin</h2>
			<p>Welcome, <?=$_SESSION['name']?>!</p>

			<script>
				<?php
				session_start();
				// If the user is not logged in redirect to the login page...
				if (!isset($_SESSION['loggedin'])) {
					header('Location: ../logon/index.html');
					exit;
				}	

				$DATABASE_HOST = 'localhost';
				$DATABASE_USER = 'root';
				$DATABASE_PASS = '';
				$DATABASE_NAME = 'phplogin';
				$con = mysqli_connect($DATABASE_HOST, $DATABASE_USER, $DATABASE_PASS, $DATABASE_NAME);
				// Try and connect using the info above.

				if ( mysqli_connect_errno() ) {
					// If there is an error with the connection, stop the script and display the error.
					exit('Failed to connect to MySQL: ' . mysqli_connect_error());

    
				}
				$date = date("Y/m/d"); 

				$result = mysqli_query($con,"SELECT `SlotId`, `TimeStart`, `TimeFinish`, `Date`, `NumberUsers`, `Type` FROM `Slot` WHERE `Date` >= $date");

				while($row = mysqli_fetch_array($result))
          				{
          				echo "SlotId: <tr><td>" . $row['SlotId'] . "</td><td> " . $row['TimeStart'] . "</td></tr><br>"; //these are the fields that you have stored in your database table employee
          				}
 				echo "</table class='table";

				mysqli_close($con);
				?>
			</script>


			
		</div>
	</body>
</html>

