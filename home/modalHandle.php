<?php 
// TOOD: Bug fixes surrounding alert statmenets
// So far, notably the alert for if the values are the same

ini_set('display_errors', 1);

ini_set('display_startup_errors', 1);

error_reporting(E_ALL);

session_start();
// If the user is not logged in redirect to the login page...
if (!isset($_SESSION['loggedin'])) {
	header('Location: index.html');
	exit;
}

// Database connection details 
$DATABASE_HOST = 'localhost';
$DATABASE_USER = 'root'; 
$DATABASE_PASS = '';
$DATABASE_NAME = 'phplogin';
// Try and connect using the info above.
$con = mysqli_connect($DATABASE_HOST, $DATABASE_USER, $DATABASE_PASS, $DATABASE_NAME);
if ( mysqli_connect_errno()) {
	// If there is an error with the connection, stop the script and display the error.
	exit('Failed to connect to MySQL: ' . mysqli_connect_error());
}; 




// Initilaize vars
$type = ''; 
$original = ''; 
$new = ''; 

// Retrieve vars from modal form
$type = $_POST['type']; 
$original = $_POST['original'];
$new = $_POST['new']; 

// Retrieve user id from session variable
$UserId = $_SESSION['id']; 

// Call checkType function 
checkType($DATABASE_HOST, $DATABASE_USER, $DATABASE_PASS, $DATABASE_NAME, $con, $type, $new, $original, $UserId);

// Function updatePhone - takes all db details, form details and userId 
function updatePhone($DATABASE_HOST, $DATABASE_USER, $DATABASE_PASS, $DATABASE_NAME, $con, $type, $new, $original, $UserId){

    // Prepare SQL statement that will update the column 'Phone' for the user and replace it with the contents
    // of the var $new 

    $stmt = $con->prepare("UPDATE `User` SET `Phone`= ? WHERE `Phone` = ? AND `UserId` = ?");
	
    // Bind paramters $new, $original and $userId into SQL statement 

    $stmt->bind_param('sss', $new, $original, $UserId);
    
    // Execute statement 
    $stmt->execute();

    // Close db connection

    $stmt->close(); 

    // Direct user to profile.php
    header("Location:profile.php");
}; 

// Function updatePassword - same as updatePhone but updates the users password
function updatePassword($DATABASE_HOST, $DATABASE_USER, $DATABASE_PASS, $DATABASE_NAME, $con, $type, $new, $original, $UserId){


    $stmt = $con->prepare("UPDATE `User` SET `Password`= ? WHERE `Password` = ? AND `UserId` = ?");
		
    $stmt->bind_param('sss', $new, $original, $UserId);
        
    $stmt->execute();

    $stmt->close(); 

    header("Location:profile.php");

}; 

// Function updateLastName - same as updatePhone but updates the users last name
function updateLastName($DATABASE_HOST, $DATABASE_USER, $DATABASE_PASS, $DATABASE_NAME, $con, $type, $new, $original, $UserId){
  

    $stmt = $con->prepare("UPDATE `User` SET `LastName`= ? WHERE `LastName` = ? AND `UserId` = ?");
		
    $stmt->bind_param('sss', $new, $original, $UserId);
        
    $stmt->execute();

    $stmt->close(); 

    header("Location:profile.php");
}; 

// Function updateFirstName - same as updatePhone but updates the users first name
function updateFirstName($DATABASE_HOST, $DATABASE_USER, $DATABASE_PASS, $DATABASE_NAME, $con, $type, $new, $original, $UserId){
 

    $stmt = $con->prepare("UPDATE `User` SET `FirstName`= ? WHERE `FirstName` = ? AND `UserId` = ?");
		
    $stmt->bind_param('sss', $new, $original, $UserId);
        
    $stmt->execute();

    $stmt->close(); 

    header("Location:profile.php");

}; 

// Function updateEmail - same as updatePhone but updates the users email
function updateEmail($DATABASE_HOST, $DATABASE_USER, $DATABASE_PASS, $DATABASE_NAME, $con, $type, $new, $original, $UserId){


    $stmt = $con->prepare("UPDATE `User` SET `Email`= ? WHERE `Email` = ? AND `UserId` = ?");
		
    $stmt->bind_param('sss', $new, $original, $UserId);
        
    $stmt->execute();

    $stmt->close(); 

    header("Location:profile.php");

}; 

// Function updateUsername - same as updatePhone but updates the users username
function updateUsername($DATABASE_HOST, $DATABASE_USER, $DATABASE_PASS, $DATABASE_NAME, $con, $type, $new, $original, $UserId){


    $stmt = $con->prepare("UPDATE `User` SET `Username`= ? WHERE `Username` = ? AND `UserId` = ?");
		
    $stmt->bind_param('sss', $new, $original, $UserId);
        
    $stmt->execute();

    $stmt->close();

    header("Location:profile.php");

}; 

// Function checkPhone user - Takes all db details, form details and userId 
function checkPhoneUse($DATABASE_HOST, $DATABASE_USER, $DATABASE_PASS, $DATABASE_NAME, $con, $type, $new, $original, $UserId){

    // Prepare SQL statment that selects the details within the column Phone where Phone is equal to the phone number stored in 
    // $new, provided by the user
    $stmt = $con->prepare('SELECT `Phone` FROM `user` WHERE `Phone` = ?');
	
    // Bind the paramater $new into the sql statement 
    $stmt->bind_param('s', $new);
    
    // Execute sql statement 
    $stmt->execute();

    // Init variable 
    $checkPhoneUse = ''; 

    // Bind the result of the SQL statement to var $checkPhoneUser
    $stmt->bind_result($checkPhoneUse);

    // Fetch result
    $stmt->fetch();

    // Close db connection
    $stmt->close();

    // If $checkPhoneUse is equal to nothing call the next function - updatePhone
    // The purpose of this is to check that the phone number is not already in use
    // by another user
    if ($checkPhoneUse == ''){

        updatePhone($DATABASE_HOST, $DATABASE_USER, $DATABASE_PASS, $DATABASE_NAME, $con, $type, $new, $original, $UserId);

    } else {
        // Else - User will be alerted that the phone number is not valid and returned to profile.php
        // Message is non-descriptive and does not say the number cannot be used because another user
        // is already using to preserver privacy for other users
        echo "<script>alert('Phone number is not valid');document.location='profile.php'</script>";
        
    }

};



// Function comparePhone - takes all db variables, form details and userId
function comparePhone($DATABASE_HOST, $DATABASE_USER, $DATABASE_PASS, $DATABASE_NAME, $con, $type, $new, $original, $UserId){
   
    // Function compareDetails is called and value returned is stored
    // in var $different. This compares the vars $new and $original to 
    // make sure they are different 
    $different = compareDetails($new, $original); 

    // If $new and $original are not different 
    if ($different == '0'){
        // Return the user to profile.php and tell the user that the details must be different 
        echo "<script>alert('Details must be different');document.location='profile.php'</script>";
    } else { 
        // If the details are different 

        // Prepare and SQL statement that selects Phone from the table User where Phone is equal too
        // the phone number stored in the var $original and userId is equal too $UserId
        $stmt = $con->prepare('SELECT `Phone` FROM `user` WHERE `Phone` = ? AND `UserId` = ?');
		
        // Bind the paramaters $original and $UserId to the SQL statement 
        $stmt->bind_param('si', $original, $UserId);
        
        // Execute the SQL statement
        $stmt->execute();
        
        // Init car $checkPhone 
        $checkPhone = ''; 
        
        // Bind results of the SQL statement to var $checkPhone
        $stmt->bind_result($checkPhone);
        
        // Fetch result
        $stmt->fetch();
        
        // Close DB connection 
        $stmt->close();

        // If $checkPhone does not contains a value
        if ($checkPhone == ''){
            // Return user to profile.php and tell them that the orignal phone number does not match
            echo "<script>alert('Original phone number does not match);document.location='profile.php'</script>";

        // If $checkPhone does contain a value 
        } else {
            // Call the function checkPhoneUser
            checkPhoneUse($DATABASE_HOST, $DATABASE_USER, $DATABASE_PASS, $DATABASE_NAME, $con, $type, $new, $original, $UserId);
        }
    }
}; 

// Function compareFirstName - takes DB variables, form details and UserId
// Function is very similar to comparePhone but instead compare's the Users
// first name which is complete in the same manner
function compareFirstName($DATABASE_HOST, $DATABASE_USER, $DATABASE_PASS, $DATABASE_NAME, $con, $type, $new, $original, $UserId){

    $different = compareDetails($new, $original); 

    if ($different == '0'){
        echo "<script>alert('Details must be different');document.location='profile.php'</script>";
    } else {

        $stmt = $con->prepare('SELECT `FirstName` FROM `user` WHERE `FirstName` = ? AND `UserId` = ?');
		
        $stmt->bind_param('si', $original, $UserId);
            
        $stmt->execute();
    
        $checkFirstName = ''; 
    
        $stmt->bind_result($checkFirstName);
    
        $stmt->fetch();
    
        $stmt->close();
    
        if ($checkFirstName == ''){
            echo "<script>alert('Original first name does not match');document.location='profile.php'</script>";

        } else {
         
            updateFirstName($DATABASE_HOST, $DATABASE_USER, $DATABASE_PASS, $DATABASE_NAME, $con, $type, $new, $original, $UserId);
        }
    }
}; 

// Function compareFirstName - takes DB variables, form details and UserId
// Function is very similar to comparePhone but instead compare's the Users
// last name which is complete in the same manner
function compareLastName($DATABASE_HOST, $DATABASE_USER, $DATABASE_PASS, $DATABASE_NAME, $con, $type, $new, $original, $UserId){
   

    $different = compareDetails($new, $original); 

    if ($different == '0'){
        echo "<script>alert('Details must be different');document.location='profile.php'</script>";
    } else {

        $stmt = $con->prepare('SELECT `LastName` FROM `user` WHERE `LastName` = ? AND `UserId` = ?');
		
        $stmt->bind_param('si', $original, $UserId);
            
        $stmt->execute();
    
        $checkLastName = ''; 
    
        $stmt->bind_result($checkLastName);
    
        $stmt->fetch();
    
        $stmt->close();
    
        if ($checkLastName == ''){
            echo "<script>alert('Users original last name does not match');document.location='profile.php'</script>";

        } else {
          
            updateLastName($DATABASE_HOST, $DATABASE_USER, $DATABASE_PASS, $DATABASE_NAME, $con, $type, $new, $original, $UserId);
        }
    }

}; 

// Function compareFirstName - takes DB variables, form details and UserId
// Function is very similar to comparePhone but instead compare's the Users
// password which is complete in the same manner
function comparePassword($DATABASE_HOST, $DATABASE_USER, $DATABASE_PASS, $DATABASE_NAME, $con, $type, $new, $original, $UserId){
  

    $different = compareDetails($new, $original); 

    if ($different == '0'){
        echo "<script>alert('Details must be different);document.location='profile.php'</script>";
    } else {

        $stmt = $con->prepare('SELECT `Password` FROM `user` WHERE `Password` = ? AND `UserId` = ?');
		
        $stmt->bind_param('si', $original, $UserId);
            
        $stmt->execute();
    
        $checkPassword = ''; 
    
        $stmt->bind_result($checkPassword);
    
        $stmt->fetch();
    
        $stmt->close();
    
        if ($checkPassword == ''){
            echo "<script>alert('Original password does not match');document.location='profile.php'</script>";

        } else {

            updatePassword($DATABASE_HOST, $DATABASE_USER, $DATABASE_PASS, $DATABASE_NAME, $con, $type, $new, $original, $UserId);
        }
    }
}; 

// Functio checkUsernameUse - takes DB variables, form details and UserId 
// It's purpose is to make sure the user cannot change their username
// to a username that is already in use by another user
function checkUsernameUse($DATABASE_HOST, $DATABASE_USER, $DATABASE_PASS, $DATABASE_NAME, $con, $type, $new, $original, $UserId){
    
    // Prepare and SQL statement that select the Username from user where username is equal to the username
    // stored in the variable $new
    $stmt = $con->prepare('SELECT `Username` FROM `user` WHERE `Username` = ?');
    
    // Bind the paramter $new to the SQL statement 
    $stmt->bind_param('s', $new);
    
    // Execute the SQL statment 
    $stmt->execute();

    // Init var $checkUsernameUse
    $checkUsernameUse = ''; 

    // Bind result of SQL statement to $checkUsernameUse
    $stmt->bind_result($checkUsernameUse);

    // Fetch SQL result
    $stmt->fetch();

    // Close db connection
    $stmt->close();

    // If $checkUsernameUser is empty
    if ($checkUsernameUse == ''){
        // Call function updateUsername
        updateUsername($DATABASE_HOST, $DATABASE_USER, $DATABASE_PASS, $DATABASE_NAME, $con, $type, $new, $original, $UserId);

    } else {
        // Else, if $checkUsernameUser is not empty 
        // Return user to profile.php and tell the user that the username is not valid
        echo "<script>alert('Username is not valid');document.location='profile.php'</script>";
        
    }

};

// Function compareUsername - takes DB variables, form details and UserId
// Function is very similar to comparePhone but instead compare's the Users
// username which is complete in the same manner
function compareUsername($DATABASE_HOST, $DATABASE_USER, $DATABASE_PASS, $DATABASE_NAME, $con, $type, $new, $original, $UserId){


    $different = compareDetails($new, $original); 

    if ($different == '0'){
        echo "<script>alert('Details must be different');document.location='profile.php'</script>";
    } else {

        $stmt = $con->prepare('SELECT `Username` FROM `user` WHERE `Username` = ? AND `UserId` = ?');
		
        $stmt->bind_param('si', $original, $UserId);
            
        $stmt->execute();
    
        $checkUsername = ''; 
    
        $stmt->bind_result($checkUsername);
    
        $stmt->fetch();
    
        $stmt->close();
    
        if ($checkUsername == ''){
            echo "<script>alert('Original username does not match);document.location='profile.php'</script>";

        } else {
    
            checkUsernameUse($DATABASE_HOST, $DATABASE_USER, $DATABASE_PASS, $DATABASE_NAME, $con, $type, $new, $original, $UserId);
        }
    }

}; 

// Function checkEmailUser - takes DB variables, form details and UserId
// It's function is to make sure the user cannot change their email to an
// email that is already in use by another user
function checkEmailUse($DATABASE_HOST, $DATABASE_USER, $DATABASE_PASS, $DATABASE_NAME, $con, $type, $new, $original, $UserId){

    // Prepare SQL statement that select Email from User where email is
    // equal to the email stored in the variable $new
    $stmt = $con->prepare('SELECT `Email` FROM `user` WHERE `Email` = ?');
	
    // Bind the paramater $new into the SQL statement 
    $stmt->bind_param('s', $new);
    
    // Execute SQL statement 
    $stmt->execute();

    // Init var $checkEmailUser
    $checkEmailUse = ''; 
    
    // Bind result of the SQL statement to var $checkEmailuser
    $stmt->bind_result($checkEmailUse);

    // Fetch result
    $stmt->fetch();

    // Close db connection
    $stmt->close();

    // if $checkEmailUse is empty
    if ($checkEmailUse == ''){
        
        // Call function updateEmail 
        updateEmail($DATABASE_HOST, $DATABASE_USER, $DATABASE_PASS, $DATABASE_NAME, $con, $type, $new, $original, $UserId);

    } else {
        // Else, if $checkEmailUse is not empty 
        // Return user to profile.php and tell the user that the email is not valud
        echo "<script>alert('Email is not valid);document.location='profile.php'</script>";
        
    }

};



// Function compareEmail - takes DB variables, form details and UserId
// Function is very similar to comparePhone but instead compare's the Users
// email which is complete in the same manner
function compareEmail($DATABASE_HOST, $DATABASE_USER, $DATABASE_PASS, $DATABASE_NAME, $con, $type, $new, $original, $UserId){
  

    $different = compareDetails($new, $original); 

    if ($different == '0'){
        echo "<script>alert('Details must be different');document.location='profile.php'</script>";
    } else {

        $stmt = $con->prepare('SELECT `Email` FROM `user` WHERE `Email` = ? AND `UserId` = ?');
		
        $stmt->bind_param('si', $original, $UserId);
            
        $stmt->execute();
    
        $checkEmail = ''; 
    
        $stmt->bind_result($checkEmail);
    
        $stmt->fetch();
    
        $stmt->close();
    
        if ($checkEmail == ''){
            echo "<script>alert('Original email does not match');document.location='profile.php'</script>";

        } else {
          
            checkEmailUse($DATABASE_HOST, $DATABASE_USER, $DATABASE_PASS, $DATABASE_NAME, $con, $type, $new, $original, $UserId);
        }
    }
}; 

// Function validateUsername - takes db variables, form details and UserId
function validateUsername($DATABASE_HOST, $DATABASE_USER, $DATABASE_PASS, $DATABASE_NAME, $con, $type, $new, $original, $UserId){
    // If $new does not contain chars other than a-z, A-Z, 0-9
    if (preg_match('/^[a-zA-Z0-9]+$/', $new) == 0) {
        // Tell the user that the username is not valid 
        echo "<script>alert('Username is not valid');document.location='profile.php'</script>";
    } else {
        // If it does, call function compareUsername
        compareUsername($DATABASE_HOST, $DATABASE_USER, $DATABASE_PASS, $DATABASE_NAME, $con, $type, $new, $original, $UserId); 
    }; 
}; 
// Function validatePassword - takes db variables, form details and UserId
function validatePassword($DATABASE_HOST, $DATABASE_USER, $DATABASE_PASS, $DATABASE_NAME, $con, $type, $new, $original, $UserId){

    // If the length of $new is more than 20 chars or less than 5 chars
    if (strlen($new) > 20 || strlen($new) < 5) {

        // Tell the user that their password must be between 5 and 20 chars long and return them to profile.php 
        echo "<script>alert('Password must be between 5 and 20 characters long');document.location='profile.php'</script>";

    } else {

        // Else, call function comparePassword 
        comparePassword($DATABASE_HOST, $DATABASE_USER, $DATABASE_PASS, $DATABASE_NAME, $con, $type, $new, $original, $UserId); 

    }; 
};


// Function validateEmail - takes db variables, form details and UserId
function validateEmail($DATABASE_HOST, $DATABASE_USER, $DATABASE_PASS, $DATABASE_NAME, $con, $type, $new, $original, $UserId){
    // if $new is not a valid email 
    if (!filter_var($new, FILTER_VALIDATE_EMAIL)){

        // Tell the user that the email is not valid and return them to profile.php
        echo "<script>alert('Email is not valid');document.location='profile.php'</script>";

    } else {

        // If the email is valid call the function compareEmail 
        compareEmail($DATABASE_HOST, $DATABASE_USER, $DATABASE_PASS, $DATABASE_NAME, $con, $type, $new, $original, $UserId); 

    }
} 


// Function checkType - takes db variables, form details and UserId
// Check which form the user has submitted and calls the correct function
// This is done via if and elseif statements 
function checkType($DATABASE_HOST, $DATABASE_USER, $DATABASE_PASS, $DATABASE_NAME, $con, $type, $new, $original, $UserId){

    if ($type == 'Username'){
        validateUsername($DATABASE_HOST, $DATABASE_USER, $DATABASE_PASS, $DATABASE_NAME, $con, $type, $new, $original, $UserId);
    } elseif ($type == 'Password') {
        validatePassword($DATABASE_HOST, $DATABASE_USER, $DATABASE_PASS, $DATABASE_NAME, $con, $type, $new, $original, $UserId);
    } elseif ($type == 'Email') {
        validateEmail($DATABASE_HOST, $DATABASE_USER, $DATABASE_PASS, $DATABASE_NAME, $con, $type, $new, $original, $UserId); 
    } elseif ($type == 'LastName') {
        compareLastName($DATABASE_HOST, $DATABASE_USER, $DATABASE_PASS, $DATABASE_NAME, $con, $type, $new, $original, $UserId); 
    } elseif ($type == 'FirstName') {
        compareFirstName($DATABASE_HOST, $DATABASE_USER, $DATABASE_PASS, $DATABASE_NAME, $con, $type, $new, $original, $UserId); 
    } elseif ($type == 'Phone') {
        comparePhone($DATABASE_HOST, $DATABASE_USER, $DATABASE_PASS, $DATABASE_NAME, $con, $type, $new, $original, $UserId); 
    }
}; 

// // Function compareDetails - takes two vars: $new and $original 
function compareDetails($new, $original){
    // If $new is equal to $original 
    if ($new == $original){
        // Tell the user that the details cannot be the same and return them to profile.php 
        echo "<script>alert('Details cannot be the same');document.location='profile.php'</script>";
        // Return 0 
        return 0; 
    } else {
        // Else return 1 
        return 1; 
    }; 
}; 

?> 