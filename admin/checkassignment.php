<?php

include_once '../tools/database.php';
  
// Get the user id 
$serial = $_GET['no_serial'];
  
// Database connection
  
if ($serial !== "") {
      
    // Get corresponding first name and 
    // last name for that user id   
    $database = new Database(); 
    $query = "SELECT ea.eeid, ee.estado FROM equip_assignment as ea
    JOIN equipo as e on e.no_serial=ea.no_serial
    JOIN estado_equipo as ee on ee.id=id_estado
    WHERE ea.no_serial = '$serial'";
    $stmt = $database->conectar()->prepare($query);
    $stmt->execute();
    $rows3 = $stmt->fetch();
   

    
    // Get the eeid
    $eid = $rows3["eeid"];
    $status = $rows3["estado"];
  
    
}
  
// Store it in a array
$result3 = array("$eid", "$status");
  
// Send in JSON encoded form
$myJSON = json_encode($result3);
echo $myJSON;
?>