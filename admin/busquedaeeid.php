<?php

include_once '../tools/database.php';
  
// Get the user id 
$eeid = $_GET['eeid'];
  
// Database connection
  
if ($eeid !== "") {
      
    // Get corresponding first name and 
    // last name for that user id   
    $database = new Database(); 
    $query = "SELECT nombres, apellidos, departamento FROM empleados WHERE eeid LIKE '$eeid'";
    $stmt = $database->conectar()->prepare($query);
    $stmt->execute();
    $rows = $stmt->fetch();
   

    
    // Get the first name
    $first_name = $rows["nombres"];
  
    // Get the last name
    $last_name = $rows["apellidos"];

    $dept = $rows["departamento"];

    
}

  
// Store it in a array
$result = array("$first_name", "$last_name", "$dept");
  
// Send in JSON encoded form
$myJSON = json_encode($result);
echo $myJSON;
?>

