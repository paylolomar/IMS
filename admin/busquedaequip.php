<?php
    include_once '../tools/database.php';  
    // Get the user id 
    $serial = $_GET['no_serial'];
    // Database connection
    if ($serial !== "") {
        // Get corresponding first name and 
        // last name for that user id   
        $database = new Database(); 
        $query = "SELECT a.marca, a.modelo, e.estado FROM equipo AS eq JOIN atributo AS a ON a.id=eq.id_atributo JOIN estado_equipo AS e ON e.id=eq.id_estado WHERE no_serial = '$serial'";
        $stmt = $database->conectar()->prepare($query);
        $stmt->execute();
        $rows2 = $stmt->fetch();
        // Get the first name
        $marca = $rows2["marca"];
        // Get the last name
        $modelo = $rows2["modelo"];
        $estado = $rows2["estado"];
    }
    // Store it in a array
    $result2 = array("$marca", "$modelo", "$estado");  
    // Send in JSON encoded form
    $myJSON = json_encode($result2);
    echo $myJSON;
?>

