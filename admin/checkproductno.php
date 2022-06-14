<?php
    include_once '../tools/database.php';
    if(isset($_POST['product_no'])){
        $serial=$_POST["product_no"];
        $database = new Database();
        $query=("SELECT product_no FROM producto_modelo WHERE product_no = '$serial'");
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