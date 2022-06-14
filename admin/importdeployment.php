<?php
// Load the database configuration file
include_once '../tools/database.php';

if(isset($_POST['importDeploy'])){
    
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
                $wave   = $line[0];
                $PC  = $line[1];
                $PCM  = $line[2];
                $Monitor1  = $line[3];
                $M1M  = $line[4];
                $Monitor2 = $line[5];
                $M2M  = $line[6];
                $eeid = $line[7];

                try{
                    $database = new Database();
                    $query="INSERT INTO waves (wave, PC, PC_Model,Monitor1,Monitor1_Model, Monitor2, Monitor2_Model, eeid) VALUES ('".$line[0]."','".$line[1]."','".$line[2]."','".$line[3]."','".$line[4]."','".$line[5]."','".$line[6]."','".$line[7]."');";
                    $stmt = $database->conectar()->prepare($query);
                    $stmt->execute();
                }
                catch(PDOExceptio $e){

                    
                }
                    
                }
            
            // Close opened CSV file
            fclose($csvFile);
            
            $qstring = '?wave='.$wave;
        }else{
            $qstring = '?status=err';
        }
    }else{
        $qstring = '?status=invalid_file';
    }
}

// Redirect to the listing page
header("Location: bulktransaction.php".$qstring);