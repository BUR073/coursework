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

		</div>
	</body>
</html>

<?php
$con=mysqli_connect("localhost","root","","phplogin");
// Check connection
if (mysqli_connect_errno())
{
echo "Failed to connect to MySQL: " . mysqli_connect_error();
}
$date = date("Y/m/d");
$result = mysqli_query($con,"SELECT `SlotId`, `TimeStart`, `TimeFinish`, `Date`, `NumberUsers`, `Type` FROM `Slot` WHERE `Date` >= $date");
echo "<link href='../styles/style.css' rel='stylesheet' type='text/css'>";
echo "<table id='adminTable'>
<tr style='border: 1px solid;'>
<th style='border: 1px solid;'>SlotId</th>
<th style='border: 1px solid;'>Time Start</th>
<th style='border: 1px solid;'>Time Finish</th>
<th style='border: 1px solid;'>Date</th>
<th style='border: 1px solid;'>Number of Users</th>
<th style='border: 1px solid;'>Type</th>
</tr>";

while($row = mysqli_fetch_array($result))
{

echo "<tr style='border: 1px solid;'>";
echo "<td style='border: 1px solid;'>" . $row['SlotId'] . "</td>";
echo "<td style='border: 1px solid;'>" . $row['TimeStart'] . "</td>";
echo "<td style='border: 1px solid;'>" . $row['TimeFinish'] . "</td>";
echo "<td style='border: 1px solid;'>" . $row['Date'] . "</td>";
echo "<td style='border: 1px solid;'>" . $row['NumberUsers'] . "</td>";
echo "<td style='border: 1px solid;'>" . $row['Type'] . "</td>";
echo "</tr>";
}
echo "</table>";

mysqli_close($con);

?>