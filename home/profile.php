<?php

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
		<title>Profile Page</title>
		<link href="../style/style.css" rel="stylesheet" type="text/css">
        <link href="../styles/skeleton.css" rel="stylesheet" type="text/css">
		<link href="../styles/style.css" rel="stylesheet" type="text/css">
		<link href="../styles/normalize.css" rel="stylesheet" type="text/css"> 
		<script src="jquery-3.6.1.min.js"></script>
		<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/css/bootstrap.min.css">
  		<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.1/jquery.min.js"></script>
  		<script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/js/bootstrap.min.js"></script>

		
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


<div class='container'>

<!-- Modal -->
<div class='modal fade' id='usernameModal' role='dialog'>

<div class='modal-dialog'>
    
<!-- Modal content-->

<div class='modal-content'>

<div class='modal-header'>

<button type='button' class='close' data-dismiss='modal'>&times;</button>

<h4 class='modal-title'>Change Username</h4>

</div>

<div class='modal-body'>

<form action='modalHandle.php' method='post'> 
<label for='originalUsername'>Original:</label>
<input type='text' id='originalUsername' name='orignalUsername'><br><br>

<label for='newUsername'>New:</label>
<input type='text' id='newUsername' name='newUsername'><br><br>

<input type='submit' value='Submit'>


</form>

<input type='button' value='Close' data-dismiss='modal'> 


</div>
</div>
</div>
</div>
</div>





<!-- Modal for changing users password -->


<div class='container'>

<!-- Modal -->
<div class='modal fade' id='passwordModal' role='dialog'>

<div class='modal-dialog'>
    
<!-- Modal content-->

<div class='modal-content'>

<div class='modal-header'>

<button type='button' class='close' data-dismiss='modal'>&times;</button>

<h4 class='modal-title'>Change Password</h4>

</div>

<div class='modal-body'>

<form action=''> 
<label for='originalPassword'>Original:</label>
<input type='text' id='originalPassword' name='orignalPassword'><br><br>

<label for='newPassword'>New:</label>
<input type='text' id='newPassword' name='newsPassword'><br><br>

<input type='submit' value='Submit'>


</form>

<input type='button' value='Close' data-dismiss='modal'>


</div>


</div>
      
</div>
</div>
  
</div>




 
<!-- Modal for changing users last name -->

<div class='container'>

<!-- Modal -->
<div class='modal fade' id='lastNameModal' role='dialog'>

<div class='modal-dialog'>
    
<!-- Modal content-->

<div class='modal-content'>

<div class='modal-header'>

<button type='button' class='close' data-dismiss='modal'>&times;</button>

<h4 class='modal-title'>Change Last Name</h4>

</div>

<div class='modal-body'>

<form action=''> 
<label for='original'>Original:</label>
<input type='text' id='originallastName' name='orignallastName'><br><br>

<label for='new'>New:</label>
<input type='text' id='newlastName' name='newlastName'><br><br>

<input type='submit' value='Submit'>


</form>

<input type='button' value='Close' data-dismiss='modal'>


</div>


</div>
      
</div>
</div>
  
</div>





<!-- Modal for changing users first name -->


<div class='container'>

<!-- Modal -->
<div class='modal fade' id='firstNameModal' role='dialog'>

<div class='modal-dialog'>
    
<!-- Modal content-->

<div class='modal-content'>

<div class='modal-header'>

<button type='button' class='close' data-dismiss='modal'>&times;</button>

<h4 class='modal-title'>Change First Name</h4>

</div>

<div class='modal-body'>

<form action=''> 
<label for='originalfirstName'>Original:</label>
<input type='text' id='originalfirstName' name='orignal'><br><br>

<label for='newfirstName'>New:</label>
<input type='text' id='newfirstName' name='newsfirstName'><br><br>

<input type='submit' value='Submit'>


</form>

<input type='button' value='Close' data-dismiss='modal'>


</div>


</div>
      
</div>
</div>
  
</div>






<!-- Modal for changing users email -->

<div class='container'>

<!-- Modal -->
<div class='modal fade' id='emailModal' role='dialog'>

<div class='modal-dialog'>
    
<!-- Modal content-->

<div class='modal-content'>

<div class='modal-header'>

<button type='button' class='close' data-dismiss='modal'>&times;</button>

<h4 class='modal-title'>Change Email</h4>

</div>

<div class='modal-body'>

<form action=''> 
<label for='originalEmail'>Original:</label>
<input type='text' id='originalEmail' name='orignalEmail'><br><br>

<label for='newEmail'>New:</label>
<input type='text' id='newEmail' name='newEmail'><br><br>

<input type='submit' value='Submit'>


</form>

<input type='button' value='Close' data-dismiss='modal'>


</div>


</div>
      
</div>
</div>
  
</div>







<!-- Modal for changing users phonenumber -->


<div class='container'>

<!-- Modal -->
<div class='modal fade' id='phoneModal' role='dialog'>

<div class='modal-dialog'>
    
<!-- Modal content-->

<div class='modal-content'>

<div class='modal-header'>

<button type='button' class='close' data-dismiss='modal'>&times;</button>

<h4 class='modal-title'>Change Phone Number</h4>

</div>

<div class='modal-body'>

<form action=''> 
<label for='originalPhone'>Original:</label>
<input type='text' id='originalPhone' name='orignalPhone'><br><br>

<label for='newPhone'>New:</label>
<input type='text' id='newPhone' name='newPhone'><br><br>

<input type='submit' value='Submit'>


</form>

<input type='button' value='Close' data-dismiss='modal'>


</div>


</div>
      
</div>
</div>
  
</div>






</html>


<?php
ini_set('display_errors', 1);

ini_set('display_startup_errors', 1);

error_reporting(E_ALL);
// We need to use sessions, so you should always start sessions using the below code.


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
echo "<td><input type='button' data-toggle='modal' name='username' value='Change Username' data-target='#usernameModal' </td>";
echo "<td><input type='button' data-toggle='modal' name='password' value='Change Password' data-target='#passwordModal'</td>";
echo "<td><input type='button' data-toggle='modal' name='lastName' value='Change Last Name' data-target='#lastNameModal'</td>";
echo "<td><input type='button' data-toggle='modal' name='frstName' value='Change First Name' data-target='#firstNameModal'</td>";
echo "<td><input type='button' data-toggle='modal' name='email' value='Change Email' data-target='#emailModal'</td>";
echo "<td><input type='button' data-toggle='modal' name='phone' value='Change Phone' data-target='#phoneModal'</td>";
echo "</tr>";
}
echo "<table>";

mysqli_close($con);



?>

