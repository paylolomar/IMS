<?php
// Load the database configuration file
include_once '../tools/database.php';

if(isset($_POST['importSubmit'])){
    
    // Allowed mime types
    $csvMimes = array('text/x-comma-separated-values', 'text/comma-separated-values', 'application/octet-stream', 'application/vnd.ms-excel', 'application/x-csv', 'text/x-csv', 'text/csv', 'application/csv', 'application/excel', 'application/vnd.msexcel', 'text/plain');
    
    // Validate whether selected file is a CSV file
    if(!empty($_FILES['file']['name']) && in_array($_FILES['file']['type'], $csvMimes)){
        
        // If the file is uploaded
        if(is_uploaded_file($_FILES['file']['tmp_name'])){
            
            // Open uploaded CSV file with read-only mode
            $csvFile = fopen($_FILES['file']['tmp_name'], 'r');
            
            // Skip the first line
            fgetcsv($csvFile);
            
            // Parse data from CSV file line by line
            while(($line = fgetcsv($csvFile)) !== FALSE){
                // Get row data
                $eid   = $line[0];
                $nombres  = $line[1];
                $apellidos  = $line[2];
                $departamento = $line[3];
                $terminacion = $line[4];
                $cedula= $line[5];
                $direccion  = $line[6];
                $mail  = $line[7];
                $supervisor   = $line[8];
            
                try{
                    $database = new Database();
                    $query="INSERT INTO `empleados` (`eeid`, `nombres`, `apellidos`, `departamento`, `fecha_terminacion`, `cedula_id`, `direccion`, `personal_mail`, `supervisor`) 
                    VALUES ('".$line[0]."','".$line[1]."','".$line[2]."','".$line[3]."','".$line[4]."','".$line[5]."','".$line[6]."' ,'".$line[7]."' ,'".$line[8]."') 
                    ON DUPLICATE KEY UPDATE 
                    nombres=VALUES(nombres), 
                    apellidos=VALUES(apellidos), 
                    departamento=VALUES(departamento), 
                    fecha_terminacion=VALUES(fecha_terminacion), 
                    cedula_id=VALUES(cedula_id), 
                    direccion=VALUES(direccion),
                    personal_mail=VALUES(personal_mail),
                    supervisor=VALUES(supervisor);";
                    $stmt = $database->conectar()->prepare($query);
                    $stmt->execute();
                }
                catch(PDOException $e){
                    
                }
                

                
                }
            
            // Close opened CSV file
            fclose($csvFile);
            
            $qstring = '?status=succ';
        }else{
            $qstring = '?status=err';
        }
    }else{
        $qstring = '?status=invalid_file';
    }
}

// Redirect to the listing page
header("Location: employees.php".$qstring);