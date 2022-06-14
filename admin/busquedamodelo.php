<?php
    include_once '../tools/database.php';  
    // Get the user id 
    $serial = $_GET['product_no'];
    // Database connection
    if ($serial !== "") {
        // Get corresponding first name and 
        // last name for that user id   
        $database = new Database(); 
        $query = "SELECT id FROM producto_modelo WHERE Product_No = '$serial'";
        $stmt = $database->conectar()->prepare($query);
        $stmt->execute();
        $rows2 = $stmt->fetch();
        // Get the first name
        $id = $rows2["id"];
        $query = "SELECT modelo FROM atributo WHERE id = '$id'";
        $stmt = $database->conectar()->prepare($query);
        $stmt->execute();
        $rows2 = $stmt->fetch();
        $modelo = $row2["modelo"]
    }
    // Store it in a array
    $result2 = $modelo;  
    // Send in JSON encoded form
    $myJSON = json_encode($result2);
    echo $myJSON;
?>