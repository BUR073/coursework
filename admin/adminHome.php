<!DOCTYPE html>
<html>
	<head>
		<meta charset="utf-8">

		<link href="../style/style.css" rel="stylesheet" type="text/css">
        <link href="../styles/skeleton.css" rel="stylesheet" type="text/css">
		<link href="../styles/style.css" rel="stylesheet" type="text/css">
		<link href="../styles/normalize.css" rel="stylesheet" type="text/css"> 
		<script src="jquery-3.6.1.min.js"></script>

		
		<link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.7.1/css/all.css">
	</head>
	<body class="loggedin">
		<nav class="header">
			<div class="navbar">

				<h1>Admin</h1>

				<a href="../logon/logout.php"><i class="fas fa-sign-out-alt"></i>Logout</a>

				
			</div>
		</nav>
		<div class="content">
			<h2>Slots</h2>

		</div>
	</body>
</html>

<?php
function formatTime($time){
	$time = substr($time, 0, -8);
	return $time; 
}

function type($type){
	if ($type == 0){
		$Newtype = 'Cardio'; 
		return $Newtype; 
	} else {
		if ($type == 1){
			$Newtype = 'Weight'; 
			return $Newtype; 
		}

	}
}

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
<tr>
<th>SlotId</th>
<th>Time Start</th>
<th>Time Finish</th>
<th>Date</th>
<th>Number of Users</th>
<th>Type</th>
</tr>";

while($row = mysqli_fetch_array($result))
{

echo "<tr>";
echo "<td>" . $row['SlotId'] . "</td>";
echo "<td>" . formatTime($row['TimeStart']) . "</td>";
echo "<td>" . formatTime($row['TimeFinish']) . "</td>";
echo "<td>" . $row['Date'] . "</td>";
echo "<td>" . $row['NumberUsers'] . "</td>";
echo "<td>" . type($row['Type']) . "</td>";
echo "</tr>";
}
echo "</table>";

mysqli_close($con);



?>