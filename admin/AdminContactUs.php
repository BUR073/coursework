<style>
    tr {
        outline-style: solid;
        outline-width: 5px;
    }
</style>

<?php
// We need to use sessions, so you should always start sessions using the below code.
session_start();
// If the user is not logged in redirect to the login page...
if (!isset($_SESSION['loggedin'])) {
	header('Location: index.html');
	exit;
}
?>

<html>
	<head>
		<meta charset="utf-8">
		<title>Contact Us Messages</title>
		<link href="../styles/style.css" rel="stylesheet" type="text/css">
		<link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.7.1/css/all.css">
	</head>
	<body class="loggedin">
		<nav class="navtop">
			<div>
				<h1>Admin</h1>
				<a href="adminHome.php"><i class="fas fa-home"></i>Home</a>
				<a href="../logon/logout.php"><i class="fas fa-sign-out-alt"></i>Logout</a>
			</div>
		</nav>
		<div class="content">
	
		</div>
	</body>
</html>

<?php
session_start();

ini_set('display_errors', 1);

ini_set('display_startup_errors', 1);

error_reporting(E_ALL);


$DATABASE_HOST = 'localhost';
$DATABASE_USER = 'root';
$DATABASE_PASS = '';
$DATABASE_NAME = 'phplogin2';
// Try and connect using the info above.
$con = mysqli_connect($DATABASE_HOST, $DATABASE_USER, $DATABASE_PASS, $DATABASE_NAME);
if ( mysqli_connect_errno() ) {
	// If there is an error with the connection, stop the script and display the error.
	exit('Failed to connect to MySQL: ' . mysqli_connect_error());

  
}
$none = 0;
$sql = "SELECT Message, Name, Subject, email, resolved FROM contact WHERE resolved = 1 ";

  
$result = $con->query($sql);

echo "<table border='1'>
<tr>
<th>Name</th>
<th>Subject</th>
<th>Message</th>
<th>Email</th>
<th>Resolved</th>
</tr>";

if ($result->num_rows > 0) {
  // output data of each row
  
  while($row = $result->fetch_assoc()) {
      
    echo "<br>";
	print("<TR>");
    echo "Resolved:  " . $row["resolved"]. "     - Name:     " . $row["Name"]. "     - Subject:   " . $row["Subject"]. "       - Message:    " . $row['Message']. "       - Email:     " . $row['email']. "<br>";
	print("<TR>");

    echo "<tr>";
    echo "<td>" . $row["Name"] . "</td>";
    echo "<td>" . $row["Subject"] . "</td>";
    echo "<td>" . $row["Message"] . "</td>";
    echo "<td>" . $row["email"] . "</td>";
    echo "<td>" . $row["resolved"] . "</td>";
    echo "</tr>";
  
    echo "</table>";
    
  }
} else {
  echo "0 results";
}
$con->close();


?> 