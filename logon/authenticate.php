<?php
session_start();

ini_set('display_errors', 1);

ini_set('display_startup_errors', 1);

error_reporting(E_ALL);

$DATABASE_HOST = 'localhost';
$DATABASE_USER = 'root';
$DATABASE_PASS = '';
$DATABASE_NAME = 'phplogin';
// Try and connect using the info above.
$con = mysqli_connect($DATABASE_HOST, $DATABASE_USER, $DATABASE_PASS, $DATABASE_NAME);
if ( mysqli_connect_errno() ) {
	// If there is an error with the connection, stop the script and display the error.
	exit('Failed to connect to MySQL: ' . mysqli_connect_error());

    
}

// Checks if details have been entered fully in html form. I either fields
// are empty then user will be warned and returned to login
if ( !isset($_POST['username'], $_POST['password']) ) {
	// Could not get the data that should have been sent.
	
    echo "<script>alert('Please fill both the username and password fields');document.location='index.html'</script>";
}

// Find users details from database. UserID, password and admin are retrieved 
// These details are them binded to the varaibles $id, $password and $admin
if ($stmt = $con->prepare('SELECT UserId, password, admin FROM User WHERE username = ?')) {
	// Bind parameters (s = string, i = int, b = blob, etc), in our case the username is a string so we use "s"
	$stmt->bind_param('s', $_POST['username']);
	$stmt->execute();
	// Store the result so we can check if the account exists in the database.
	$stmt->store_result();

    if ($stmt->num_rows > 0) {
        $stmt->bind_result($id, $password, $admin);
        $stmt->fetch();
        // Account exists, now we verify the password.

        if ($_POST['password'] === $password) {
            // Checks that the password matches the password entered
            session_regenerate_id();
            // Create new session id
            $_SESSION['loggedin'] = TRUE;
            $_SESSION['name'] = $_POST['username'];
            $_SESSION['id'] = $id;

            // Store variables in session
            // This is usefule later for booking, negates the need for entering all of 
            // the users details into the html form as most can be found from the session
            // Also stops users who are not logged in from accessing pages behind the
            // login. 

            // header('Location: ../home/home.php');

            // Takes user to home page
            
            // Check if user has admin rights
            // If they do send them to the admin homepage
            // Otherwise send them to the standard homepage

            if ($admin == 0) {
                header('Location: ../home/home.php');
            } else {
                header('Location: ../admin/adminHome.php');
            }

            


        } else {
            // If password is not correct
            // Warn user and send them back to login
            echo "<script>alert('Incorrect username and/or password');document.location='index.html'</script>";
        }
    } else {
        // Same for if username is correct
        // Same message and action for user
        echo "<script>alert('Incorrect username and/or password');document.location='index.html'</script>";
    }

    


	$stmt->close();
}
?>