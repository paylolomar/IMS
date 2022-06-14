<?php
    include_once '../tools/database.php';
    if(isset($_POST['no_serial'])){
        $serial=$_POST["no_serial"];
        $database = new Database();
        $query=("SELECT no_serial FROM equipo WHERE no_serial = '$serial'");
        $stmt = $database->conectar()->prepare($query);
        $stmt->execute();
        if(!$stmt->fetchColumn()) {
            echo "NOEXISTE";   
        } else {
            echo "EXISTE";
            exit();
        }
    }
?>