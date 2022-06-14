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
                $no_serial   = $line[0];
                $id_atributo  = $line[1];
                $id_estado  = $line[2];
                $id_ubicacion = $line[3];
                
                $database = new Database();
                $query="INSERT INTO equipo (no_serial, id_atributo, id_estado, id_ubicacion) VALUES ('".$line[0]."','".$line[1]."','".$line[2]."','".$line[3]."');";
                $stmt = $database->conectar()->prepare($query);
                $stmt->execute();
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
header("Location: equipos.php".$qstring);