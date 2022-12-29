<?php 
// TOOD: Bug fixes surrounding alert statmenets
// So far, notabley the alert for if the values are the same

ini_set('display_errors', 1);

ini_set('display_startup_errors', 1);

error_reporting(E_ALL);

session_start();
// If the user is not logged in redirect to the login page...
if (!isset($_SESSION['loggedin'])) {
	header('Location: index.html');
	exit;
}

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


// echo "Modal Handle", "<br>"; 
$type = ''; 
$original = ''; 
$new = ''; 

$type = $_POST['type']; 
$original = $_POST['original'];
$new = $_POST['new']; 

// echo "Type: ", $type, "<br>";
// echo "Original: ", $original, "<br>";
// echo "New: ", $new, "<br>";

// compareDetails($DATABASE_HOST, $DATABASE_USER, $DATABASE_PASS, $DATABASE_NAME, $con, $type, $new, $original); 
$UserId = $_SESSION['id']; 
checkType($DATABASE_HOST, $DATABASE_USER, $DATABASE_PASS, $DATABASE_NAME, $con, $type, $new, $original, $UserId);

function updatePhone($DATABASE_HOST, $DATABASE_USER, $DATABASE_PASS, $DATABASE_NAME, $con, $type, $new, $original, $UserId){


    $stmt = $con->prepare("UPDATE `User` SET `Phone`= ? WHERE `Phone` = ? AND `UserId` = ?");
		
    $stmt->bind_param('sss', $new, $original, $UserId);
        
    $stmt->execute();

    $stmt->close(); 

    header("Location:profile.php");
}; 

function updatePassword($DATABASE_HOST, $DATABASE_USER, $DATABASE_PASS, $DATABASE_NAME, $con, $type, $new, $original, $UserId){


    $stmt = $con->prepare("UPDATE `User` SET `Password`= ? WHERE `Password` = ? AND `UserId` = ?");
		
    $stmt->bind_param('sss', $new, $original, $UserId);
        
    $stmt->execute();

    $stmt->close(); 

    header("Location:profile.php");

}; 

function updateLastName($DATABASE_HOST, $DATABASE_USER, $DATABASE_PASS, $DATABASE_NAME, $con, $type, $new, $original, $UserId){
  

    $stmt = $con->prepare("UPDATE `User` SET `LastName`= ? WHERE `LastName` = ? AND `UserId` = ?");
		
    $stmt->bind_param('sss', $new, $original, $UserId);
        
    $stmt->execute();

    $stmt->close(); 

    header("Location:profile.php");
}; 

function updateFirstName($DATABASE_HOST, $DATABASE_USER, $DATABASE_PASS, $DATABASE_NAME, $con, $type, $new, $original, $UserId){
 

    $stmt = $con->prepare("UPDATE `User` SET `FirstName`= ? WHERE `FirstName` = ? AND `UserId` = ?");
		
    $stmt->bind_param('sss', $new, $original, $UserId);
        
    $stmt->execute();

    $stmt->close(); 

    header("Location:profile.php");

}; 

function updateEmail($DATABASE_HOST, $DATABASE_USER, $DATABASE_PASS, $DATABASE_NAME, $con, $type, $new, $original, $UserId){


    $stmt = $con->prepare("UPDATE `User` SET `Email`= ? WHERE `Email` = ? AND `UserId` = ?");
		
    $stmt->bind_param('sss', $new, $original, $UserId);
        
    $stmt->execute();

    $stmt->close(); 

    header("Location:profile.php");

}; 

function updateUsername($DATABASE_HOST, $DATABASE_USER, $DATABASE_PASS, $DATABASE_NAME, $con, $type, $new, $original, $UserId){


    $stmt = $con->prepare("UPDATE `User` SET `Username`= ? WHERE `Username` = ? AND `UserId` = ?");
		
    $stmt->bind_param('sss', $new, $original, $UserId);
        
    $stmt->execute();

    $stmt->close();

    header("Location:profile.php");

}; 

function checkPhoneUse($DATABASE_HOST, $DATABASE_USER, $DATABASE_PASS, $DATABASE_NAME, $con, $type, $new, $original, $UserId){

    $stmt = $con->prepare('SELECT `Phone` FROM `user` WHERE `Phone` = ?');
		
    $stmt->bind_param('s', $new);
        
    $stmt->execute();

    $checkPhoneUse = ''; 

    $stmt->bind_result($checkPhoneUse);

    $stmt->fetch();

    $stmt->close();

    if ($checkPhoneUse == ''){

        updatePhone($DATABASE_HOST, $DATABASE_USER, $DATABASE_PASS, $DATABASE_NAME, $con, $type, $new, $original, $UserId);

    } else {
        echo "<script>alert('Phone number is not valid');document.location='profile.php'</script>";
        
    }

};




function comparePhone($DATABASE_HOST, $DATABASE_USER, $DATABASE_PASS, $DATABASE_NAME, $con, $type, $new, $original, $UserId){
   

    $different = compareDetails($new, $original); 

    if ($different == '0'){
        echo "<script>alert('Details must be different');document.location='profile.php'</script>";
    } else {

        $stmt = $con->prepare('SELECT `Phone` FROM `user` WHERE `Phone` = ? AND `UserId` = ?');
		
        $stmt->bind_param('si', $original, $UserId);
            
        $stmt->execute();
    
        $checkPhone = ''; 
    
        $stmt->bind_result($checkPhone);
    
        $stmt->fetch();
    
        $stmt->close();
    
        if ($checkPhone == ''){
            echo "<script>alert('Original phone number does not match);document.location='profile.php'</script>";

        } else {
       
            checkPhoneUse($DATABASE_HOST, $DATABASE_USER, $DATABASE_PASS, $DATABASE_NAME, $con, $type, $new, $original, $UserId);
        }
    }
}; 

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

function checkUsernameUse($DATABASE_HOST, $DATABASE_USER, $DATABASE_PASS, $DATABASE_NAME, $con, $type, $new, $original, $UserId){
  

    $stmt = $con->prepare('SELECT `Username` FROM `user` WHERE `Username` = ?');
		
    $stmt->bind_param('s', $new);
        
    $stmt->execute();

    $checkUsernameUse = ''; 

    $stmt->bind_result($checkUsernameUse);

    $stmt->fetch();

    $stmt->close();

    if ($checkUsernameUse == ''){
 
        updateUsername($DATABASE_HOST, $DATABASE_USER, $DATABASE_PASS, $DATABASE_NAME, $con, $type, $new, $original, $UserId);

    } else {
        echo "<script>alert('Username is not valid');document.location='profile.php'</script>";
        
    }

};


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

function checkEmailUse($DATABASE_HOST, $DATABASE_USER, $DATABASE_PASS, $DATABASE_NAME, $con, $type, $new, $original, $UserId){


    $stmt = $con->prepare('SELECT `Email` FROM `user` WHERE `Email` = ?');
		
    $stmt->bind_param('s', $new);
        
    $stmt->execute();

    $checkEmailUse = ''; 

    $stmt->bind_result($checkEmailUse);

    $stmt->fetch();

    $stmt->close();

    if ($checkEmailUse == ''){
    
        updateEmail($DATABASE_HOST, $DATABASE_USER, $DATABASE_PASS, $DATABASE_NAME, $con, $type, $new, $original, $UserId);

    } else {
        echo "<script>alert('Email is not valid);document.location='profile.php'</script>";
        
    }

};




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


function validateUsername($DATABASE_HOST, $DATABASE_USER, $DATABASE_PASS, $DATABASE_NAME, $con, $type, $new, $original, $UserId){
  
    if (preg_match('/^[a-zA-Z0-9]+$/', $new) == 0) {
        echo "<script>alert('Username is not valid');document.location='profile.php'</script>";
    } else {
        compareUsername($DATABASE_HOST, $DATABASE_USER, $DATABASE_PASS, $DATABASE_NAME, $con, $type, $new, $original, $UserId); 
        // updateDetails($DATABASE_HOST, $DATABASE_USER, $DATABASE_PASS, $DATABASE_NAME, $con, $type, $new, $original); 
    }; 
}; 

function validatePassword($DATABASE_HOST, $DATABASE_USER, $DATABASE_PASS, $DATABASE_NAME, $con, $type, $new, $original, $UserId){

    if (strlen($new) > 20 || strlen($new) < 5) {
        echo "<script>alert('Password must be between 5 and 20 characters long');document.location='profile.php'</script>";
    } else {
        comparePassword($DATABASE_HOST, $DATABASE_USER, $DATABASE_PASS, $DATABASE_NAME, $con, $type, $new, $original, $UserId); 
        // updateDetails($DATABASE_HOST, $DATABASE_USER, $DATABASE_PASS, $DATABASE_NAME, $con, $type, $new, $original); 
    }; 
};



function validateEmail($DATABASE_HOST, $DATABASE_USER, $DATABASE_PASS, $DATABASE_NAME, $con, $type, $new, $original, $UserId){
    
    if (!filter_var($new, FILTER_VALIDATE_EMAIL)){
        echo "<script>alert('Email is not valid');document.location='profile.php'</script>";
    } else {
        compareEmail($DATABASE_HOST, $DATABASE_USER, $DATABASE_PASS, $DATABASE_NAME, $con, $type, $new, $original, $UserId); 
        // updateDetails($DATABASE_HOST, $DATABASE_USER, $DATABASE_PASS, $DATABASE_NAME, $con, $type, $new, $original);
    }
} 



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

function compareDetails($new, $original){
    if ($new == $original){
        echo "<script>alert('Details cannot be the same');document.location='profile.php'</script>";
        return 0; 
    } else {

        return 1; 
    }; 
}; 

?> 