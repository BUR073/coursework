<!-- crontab - 0 00 * * 1-7 -->
<?php 

ini_set('display_errors', 1);

ini_set('display_startup_errors', 1);

error_reporting(E_ALL);

// Lines above prints errors for debugging
$DATABASE_HOST = 'localhost';

$DATABASE_USER = 'root';

$DATABASE_PASS = '';

$DATABASE_NAME = 'phplogin';

// Database connection details

$con = mysqli_connect($DATABASE_HOST, $DATABASE_USER, $DATABASE_PASS, $DATABASE_NAME);

// Connect to database 

if (mysqli_connect_errno()) {

	exit('Failed to connect to MySQL: ' . mysqli_connect_error());
       
}

$hours_open = 12;

// Hours to be open eg 9 - 5

$time_open = 6; 

// Time the gym opens - 6am 

$date = date("Y/m/d"); 

function formatTime1($time) {

    $lengthTime = strlen($time);

    // Calculate length of $time

    $quadzero = "0000";
    
    // Define var containg '00' 

    if ($lengthTime == 2) {

        // If $lengthTime is equal to two 

        $time .= $quadzero; 

        // Append $doublezer0 (two zeros) - fully converted to 24 hour time format 

        return $time; 

        // Return $time - 24h time format 


    } else { // If $lengthTime does not equal 2... 

        // Append quadzero

        $time .= $quadzero; 

        $one = "0"; 

        // Append quadzero and time to a single zero

        $one .= $time;

        return $one; 

    }
}

function newId($DATABASE_HOST, $DATABASE_USER, $DATABASE_PASS, $DATABASE_NAME, $con) {

    // Function newId finds the highest SlotId currently stored into the database
    // and adds 1 to it and returns the new SlotId
    // This stops slots from having the same Id 

    $stmt = $con->prepare('SELECT MAX(SlotId) FROM `Slot`');
  
    $stmt->execute();

    $highId = ''; 

    $stmt->bind_result($highId);

    $stmt->fetch();

    $stmt->close();

    $highId++; 

    return $highId; 

}

function InsertVar($DATABASE_HOST, $DATABASE_USER, $DATABASE_PASS, $DATABASE_NAME, $con, $Type, $SlotId, $TimeStart, $TimeFinish, $Date, $NumberUsers) {
    // For helping debugging, will be removed soon
    echo 'Id: ' . $SlotId . "<br>" . "<br>"; 

    echo 'Slot Start'. $TimeStart . "<br>" . "<br>"; 

    echo 'Slot Finish'. $TimeFinish . "<br>" . "<br>"; 

    echo 'Type: ', $Type . "<br>" . "<br>"; 

    echo 'Date: ', $Date . "<br>" . "<br>"; 

    echo '---------------------------------------------' . "<br>"; 

    // Below is where all the required variables for the slot are inserted into the database
   
    $stmt = $con->prepare('INSERT INTO `Slot`(`SlotId`, `TimeStart`, `TimeFinish`, `Date`, `NumberUsers`, `Type`) VALUES (?, ?, ?, ?, ?, ?) '); {
        
        $stmt->bind_param('ssssss', $SlotId, $TimeStart, $TimeFinish, $Date, $NumberUsers, $Type); 
        
        $stmt->execute();

        $stmt->close();
 
     }
}
function createSlotDay($DATABASE_HOST, $DATABASE_USER, $DATABASE_PASS, $DATABASE_NAME, $con, $hours_open, $time_open, $Date) {
    while($hours_open != 0) { // Start of while loop 

        // Echo var to make it easier to debug
        
        $slot_start = $time_open; 
        // Slot_start = Time open - Will increase as loop loops 
        
        $slot_finish = $slot_start + 1; 
    
        // Slot_finsih is 1 hour later than slot start
        
        // Insert values into table

        // The 4 lines below increase both the start time for the slot
        // and the end time for the slot
        // The new times are also sent to the function fromattime1 which 
        // formats them correctly to be inserted into the database 

    
        $TimeStart = $slot_start; 
    
        $TimeStartFormat = formattime1($TimeStart); 
        
        $TimeFinish = $slot_finish;
    
        $TimeFinishFormat = formattime1($TimeFinish); 
    
        //$Date = date("Y/m/d"); 
        
        $NumberUsers = 0; 
        
        // This short for loop cycles through the values `0` and `1`
        // These values set the stot type, either weights or cardio
        // Values are then passed into the InsertVar function to be 
        // inserted into the database and create a new slot
        
        for ($x = 0; $x <= 1; $x+=1) { 
    
            // Starts for loop that both creates the id variables for the slots (0, 1) and inserts both of them into their repsective slots
    
            $SlotId = newId($DATABASE_HOST, $DATABASE_USER, $DATABASE_PASS, $DATABASE_NAME, $con);
    
            InsertVar($DATABASE_HOST, $DATABASE_USER, $DATABASE_PASS, $DATABASE_NAME, $con, $x, $SlotId, $TimeStartFormat, $TimeFinishFormat, $Date, $NumberUsers); 
    
          }
    
         $hours_open--; 
        
         $time_open++;
    
        }
    

}
// New function addDate
// Takes a date value as input and returns a date value
// Variable $currentDate stores the date on which the loop is currently on

function addDate($currentDate){
    // This adds 1 day to the variable $currentDate and stores it in the variable $dateNew
    $dateNew = date('Y-m-d', strtotime($currentDate. ' + 1 days'));

    // $dateNew is returned to be inserted into the slot
    return $dateNew;
}

function loopSlot($DATABASE_HOST, $DATABASE_USER, $DATABASE_PASS, $DATABASE_NAME, $con, $hours_open, $time_open, $date){
    
    // Sets the current date
    // This will be changed immediatley when the loops starts

    $dateNew = date("Y/m/d");

    for ($x = 1; $x <= 6; $x++) {

        // Calls the function addDate and inputs the variable $dateNew
        // $dateNew is the variable that stores the date which is also changed by the addDate function
        // This allows it to continue to increase the date by 1 day as it loops 

        $dateNew = addDate($dateNew);

        // Creates the slot
        createSlotDay($DATABASE_HOST, $DATABASE_USER, $DATABASE_PASS, $DATABASE_NAME, $con, $hours_open, $time_open, $dateNew);
        
      }
}
// Creates the first slot for the current day
// This is done before the loop starts as 1 day is immediatley added
// Without doing this you would not create slots for the current day

createSlotDay($DATABASE_HOST, $DATABASE_USER, $DATABASE_PASS, $DATABASE_NAME, $con, $hours_open, $time_open, $date);

// Rest of the 6 days of slots created in this function 
loopSlot($DATABASE_HOST, $DATABASE_USER, $DATABASE_PASS, $DATABASE_NAME, $con, $hours_open, $time_open, $date)



?>
