<!DOCTYPE html>
<html>
	<head>
		<meta charset="utf-8">
		<title>Profile Page</title>
		<link href="../style/style.css" rel="stylesheet" type="text/css">
        <link href="../styles/skeleton.css" rel="stylesheet" type="text/css">
		<link href="../styles/style.css" rel="stylesheet" type="text/css">
		<link href="../styles/normalize.css" rel="stylesheet" type="text/css"> 
		<script src="jquery-3.6.1.min.js"></script>

		
		<link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.7.1/css/all.css">
	</head>
	<body class="loggedin">
		<nav class="navtop">
			<div>
				<h1>Website Title</h1>
				<a href="home.php"><i class="fas fa-home"></i>Home</a>
				<a href="../logon/logout.php"><i class="fas fa-sign-out-alt"></i>Logout</a>
			</div>
		</nav>
		<div class="content">
			<h2>Profile Page</h2>
			<p>Your account details are below:</p>

		</div>
	</body>
</html>


<?php
ini_set('display_errors', 1);

ini_set('display_startup_errors', 1);

error_reporting(E_ALL);
// We need to use sessions, so you should always start sessions using the below code.
session_start();
// If the user is not logged in redirect to the login page...
if (!isset($_SESSION['loggedin'])) {
	header('Location: index.html');
	exit;
}

$con=mysqli_connect("localhost","root","","phplogin");
// Check connection
if (mysqli_connect_errno())
{
echo "Failed to connect to MySQL: " . mysqli_connect_error();
}
// $date = date("Y/m/d");
$id = $_SESSION['id']; 
$result = mysqli_query($con,"SELECT `Username`, `Password`, `LastName`, `FirstName`, `Email`, `Phone` FROM `User` WHERE Userid = $id");
echo "<link href='../styles/style.css' rel='stylesheet' type='text/css'>";



$username = '';
$password = '';
$lastName = '';
$firstName = ''; 
$email = '';
$phone = '';

echo "<link href='../styles/style.css' rel='stylesheet' type='text/css'>";
echo "<table id='adminTable'>
<tr>
<th>Username</th>
<th>Password</th>
<th>Last Name</th>
<th>First Name</th>
<th>Email</th>
<th>Phone</th>
</tr>";


while($row = mysqli_fetch_array($result))
{

echo "<tr>";
echo "<td>" . $row['Username'] . "</td>";
echo "<td>" . $row['Password'] . "</td>";
echo "<td>" . $row['LastName'] . "</td>";
echo "<td>" . $row['FirstName'] . "</td>";
echo "<td>" . $row['Email'] . "</td>";
echo "<td>" . $row['Phone'] . "</td>";

echo "</tr>";
echo "<tr>";
echo "<td><input type='submit' name='username' value='Change Username'</td>";
echo "<td><input type='submit' name='password' value='Change Password'</td>";
echo "<td><input type='submit' name='lastName' value='Change Last Name'</td>";
echo "<td><input type='submit' name='frstName' value='Change First Name'</td>";
echo "<td><input type='submit' name='email' value='Change Email'</td>";
echo "<td><input type='submit' name='phone' value='Change Phone'</td>";
echo "</tr>";
}
echo "</table>";

mysqli_close($con);
?>

