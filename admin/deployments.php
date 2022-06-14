
<?php

    include_once '../tools/database.php';
    include '../tools/utf-8.php';
    include '../tools/funciones.php';
    $mensaje='';
    session_start();

    if(!isset($_SESSION['rol'])){
        header('location: ../login.php');
    }else{
        if($_SESSION['rol'] != 1){
            header('location: ../login.php');
        }
    }
                  

    try{
        $database = new Database();
        $consulta  ="SELECT wave, quantity, deploymentdate, comment  FROM  deployments";
        $datos = $database->conectar()->prepare($consulta);
        $datos->execute();
        $resultado = $datos->fetchAll();
    }catch(PDOException $Error) {$mensaje = "Ocurrió un error al cargar los registros."; }

    if(isset($_POST['add']))
    {  
        $wave  = $_POST['wave'];
        $quantity = $_POST['quantity'];
        $date   = $_POST['date'];
        $comment     = $_POST['comment'];
       
        try{
            $database = new Database();
            $query = 'SELECT COUNT(*)+1 FROM deployments';
            $stmt = $database->conectar()->prepare($query);
            $stmt->execute();
            $registro = $stmt->fetch(PDO::FETCH_NUM);
           
        }catch(PDOException $Error) { $mensaje = "Ocurrió un error al cargar los registros."; }
       
        try{
        
        $query = 'INSERT INTO deployments (wave, quantity, deploymentdate, comment) VALUES (:wave, :quantity, :date, :comment)';
        $stmt = $database->conectar()->prepare($query);
        $stmt->bindParam(':wave', $wave);
        $stmt->bindParam(':quantity', $quantity);
        $stmt->bindParam(':date', $date);
        $stmt->bindParam(':comment', $comment);

        if ($stmt->execute()) {
            $mensaje = 'Deployment Added.';
            header('location: deployments.php');
        } else {
            $mensaje = 'Lo sentimos, ocurrió un error al agregar equipo.';
        }
        }
        catch(PDOException $Error){
            { $mensaje = "Ocurrió un error al cargar los registros."; }
        }
        
    }  

    if(isset($_POST['delete'])){

       
        if(isset($_POST['wave'])){
            foreach($_POST['wave'] as $waveid){


                try{
                    $database = new Database();
                    $query = 'SELECT COUNT(*)+1 FROM deployments';
                    $stmt = $database->conectar()->prepare($query);
                    $stmt->execute();
                    $registro = $stmt->fetch(PDO::FETCH_NUM);
                   
                }catch(PDOException $Error) { $mensaje = "Ocurrió un error al cargar los registros."; }
                try{
                    $query2 = "DELETE FROM `deployments` WHERE `Wave` = :wave" ;   
                    $stmt2 = $database->conectar()->prepare($query2);
                    $stmt2->bindValue(':wave', $waveid);
                    $stmt2->execute();
                    
                    }
                    catch(PDOException $Error){
                        echo $Error;
                    }
                }
           
        }
    }


?>
<?php include "../tools/head.php" ?>
<body>
    

    <?php include "../tools/navbar.php"?>
        <div class="container-fluid px-3 py-2 px-md-5">
            <div class="d-flex bd-highlight text-center">
                <div class="p-2 flex-shrink-1 d-flex align-items-center">
                    <a href="./admin.php" class="btn btn-success mx-1 d-flex justify-content-center" data-bs-toggle="tooltip" data-bs-placement="top" title="Back"><span class="material-icons">arrow_back</span></a> 
                </div>
                <div class="p-2 w-100">
                    <h1 class="display-6">Deployment</h1>
                </div>
            </div>

            <div class="col-sm-6">
                <a href="#addDeploymentModal" class="btn btn-success" data-toggle="modal"> <span>Add New Deployment</span></a>
                 
             </div>
            
            <div class="table-responsive table-hover shadow bg-body rounded">
            <div class="btn-group pull-right">
        
        
            <script src="../tools/table2excel/dist/jquery.table2excel.js"></script>
    </div>
    
            <table id="data_table" class="table align-middle mb-0 table-sm">
                    <thead class="text-dark" style="background-color:#F3CC4F;">
                        <tr>
                            <th scope="col">Wave</th>
                            <th scope="col">Quantity</th>
                            <th scope="col">DeploymentDate</th>
                            <th scope="col">Comment</th>
                            <th scope="col">Action</th>                            
                        </tr>
                    </thead>
                    <tbody>
                    <?php foreach ($resultado as $fila ) {?>
                        <tr>

                            <td><?php echo escape($fila['wave']); ?></td>
                            <td><?php echo escape($fila['quantity']); ?></td>
                            <td><?php echo escape($fila['deploymentdate']); ?></td>
                            <td><?php echo escape($fila['comment']); ?></td>
                            <td><a name='wavenum' href="bulktransaction.php?wave=<?php echo $fila['wave'] ?>">View</a></td> 
                            
                        </tr>
                    <?php } ?>
                    </tbody>


            </table>            
            <script type="text/javascript">
	            $(document).ready(function() {
                $('#checkBoxAll').click(function() {
                    if ($(this).is(":checked"))
                        $('.chkCheckBoxId').prop('checked', true);
                    else
                        $('.chkCheckBoxId').prop('checked', false);
                });
                 });
            </script>
 <!-- Add Modal HTML -->
<div id="addDeploymentModal" class="modal fade">
  <div class="modal-dialog">
   <div class="modal-content">
    <form method="post">
     <div class="modal-header">      
      <h4 class="modal-title">Add Deployment</h4>
      <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
     </div>
     <div class="modal-body">     
      <div class="form-group">
       <label>Wave</label>
       <input type="text" class="form-control" name="wave" placeholder="Enter Wave" required>
      </div>
      <div class="form-group">
       <label>Quantity</label>
       <input type="text" class="form-control" name="quantity" placeholder="Enter Quantity" required>
      </div>
      <div class="form-group">
       <label>Deployment Date</label>
       <input type="date" class="form-control" name="date" placeholder="Enter Date" required>
      </div>
      <div class="form-group">
       <label>Comment</label>
       <input type="text" class="form-control" name="comment" placeholder="Enter Comment" required>
      </div>     
     </div>
     <div class="modal-footer">
      <input type="button" class="btn btn-default" data-dismiss="modal" value="Cancel">
      <input type="submit" class="btn btn-success" name="add" value="Add">
     </div>
    </form>
   </div>
  </div>
 </div>
 
    
</body>