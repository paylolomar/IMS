<?php
    session_start();
    include_once '../tools/database.php';
    if(!isset($_SESSION['rol'])){
        header('location: ../login.php');
    }else{
        if($_SESSION['rol'] != 1){
            header('location: ../login.php');
        }
    }

    try{

        $database = new Database();
        $query = "SELECT COUNT(*) AS 'count' FROM `equipo`   WHERE id_estado = '3'";
        $stmt = $database->conectar()->prepare($query);
        $stmt->execute();
        $wah = $stmt->fetchAll();

        $database = new Database();
        $query = "SELECT COUNT(*) AS 'count' FROM `equipo` WHERE id_estado NOT IN (4, 5, 6)";
        $stmt = $database->conectar()->prepare($query);
        $stmt->execute();
        $equip = $stmt->fetchAll();

        $database = new Database();
        $query = "SELECT COUNT(*) AS 'count' FROM `equipo` WHERE id_estado  = '1'";
        $stmt = $database->conectar()->prepare($query);
        $stmt->execute();
        $stock = $stmt->fetchAll();

        $database = new Database();
        $query = "SELECT COUNT(*) AS 'count' FROM equip_assignment as ea JOIN empleados as ee on ee.eeid=ea.eeid WHERE ee.fecha_terminacion != ''";
        $stmt = $database->conectar()->prepare($query);
        $stmt->execute();
        $term = $stmt->fetchAll();
        
                    

    }
    catch(PDOException $e){


    }

?>
<?php include "../tools/head.php" ?>
<body>


    <?php include "../tools/navbar.php" ?>



    <div class="content-wrapper">
    <div class="container-fluid">
      <div class="row">

      <!-- Icon Cards-->
        <div class="col-lg-4 col-md-4 col-sm-6 col-12 mb-2 mt-4">
            <div class="inforide">
              <div class="row">
                <div class="col-lg-3 col-md-4 col-sm-4 col-4 rideone">
                    <img src="">
                </div>
                <div class="col-lg-9 col-md-8 col-sm-8 col-8 fontsty">
                <svg xmlns="http://www.w3.org/2000/svg" width="30" height="30" fill="currentColor" class="bi bi-pc-display" viewBox="0 0 16 16">
                    <path d="M8 1a1 1 0 0 1 1-1h6a1 1 0 0 1 1 1v14a1 1 0 0 1-1 1H9a1 1 0 0 1-1-1V1Zm1 13.5a.5.5 0 1 0 1 0 .5.5 0 0 0-1 0Zm2 0a.5.5 0 1 0 1 0 .5.5 0 0 0-1 0ZM9.5 1a.5.5 0 0 0 0 1h5a.5.5 0 0 0 0-1h-5ZM9 3.5a.5.5 0 0 0 .5.5h5a.5.5 0 0 0 0-1h-5a.5.5 0 0 0-.5.5ZM1.5 2A1.5 1.5 0 0 0 0 3.5v7A1.5 1.5 0 0 0 1.5 12H6v2h-.5a.5.5 0 0 0 0 1H7v-4H1.5a.5.5 0 0 1-.5-.5v-7a.5.5 0 0 1 .5-.5H7V2H1.5Z"/>
                </svg>
                    <h4>Total Equipment</h4>
                    <h2>
                        <?php 
                                foreach ($equip as $equipcount ) {
                            
                                    echo $equipcount['count'];
                                } 
                            
                        ?>
                            
                    </h2>
                </div>
              </div>
            </div>
        </div>

        <div class="col-lg-4 col-md-4 col-sm-6 col-12 mb-2 mt-4">
            <div class="inforide">
              <div class="row">
                <div class="col-lg-3 col-md-4 col-sm-4 col-4 ridetwo">
                    <img src="">
                </div>
                <div class="col-lg-9 col-md-8 col-sm-8 col-8 fontsty">
                    <svg xmlns="http://www.w3.org/2000/svg" width="30" height="30" fill="currentColor" class="bi bi-people-fill" viewBox="0 0 16 16">
                        <path d="M7 14s-1 0-1-1 1-4 5-4 5 3 5 4-1 1-1 1H7zm4-6a3 3 0 1 0 0-6 3 3 0 0 0 0 6z"/>
                        <path fill-rule="evenodd" d="M5.216 14A2.238 2.238 0 0 1 5 13c0-1.355.68-2.75 1.936-3.72A6.325 6.325 0 0 0 5 9c-4 0-5 3-5 4s1 1 1 1h4.216z"/>
                        <path d="M4.5 8a2.5 2.5 0 1 0 0-5 2.5 2.5 0 0 0 0 5z"/>
                    </svg>
                    <svg xmlns="http://www.w3.org/2000/svg" width="30" height="30" fill="currentColor" class="bi bi-pc-display-horizontal" viewBox="0 0 16 16">
                        <path d="M1.5 0A1.5 1.5 0 0 0 0 1.5v7A1.5 1.5 0 0 0 1.5 10H6v1H1a1 1 0 0 0-1 1v3a1 1 0 0 0 1 1h14a1 1 0 0 0 1-1v-3a1 1 0 0 0-1-1h-5v-1h4.5A1.5 1.5 0 0 0 16 8.5v-7A1.5 1.5 0 0 0 14.5 0h-13Zm0 1h13a.5.5 0 0 1 .5.5v7a.5.5 0 0 1-.5.5h-13a.5.5 0 0 1-.5-.5v-7a.5.5 0 0 1 .5-.5ZM12 12.5a.5.5 0 1 1 1 0 .5.5 0 0 1-1 0Zm2 0a.5.5 0 1 1 1 0 .5.5 0 0 1-1 0ZM1.5 12h5a.5.5 0 0 1 0 1h-5a.5.5 0 0 1 0-1ZM1 14.25a.25.25 0 0 1 .25-.25h5.5a.25.25 0 1 1 0 .5h-5.5a.25.25 0 0 1-.25-.25Z"/>
                    </svg>
                    <h4>Assigned Equipment</h4>
                    <h2>
                    <?php 
                            foreach ($wah as $wahcount ) {
                        
                                echo $wahcount['count'];
                            } 
                        
                        ?>
                    </h2>
                </div>
              </div>
            </div>
        </div>

        <div class="col-lg-4 col-md-4 col-sm-6 col-12 mb-2 mt-4">
            <div class="inforide">
              <div class="row">
                <div class="col-lg-3 col-md-4 col-sm-4 col-4 ridetwo">
                    <img src="">
                </div>
                <div class="col-lg-9 col-md-8 col-sm-8 col-8 fontsty">
                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-pc" viewBox="0 0 16 16">
                      <path d="M5 0a1 1 0 0 0-1 1v14a1 1 0 0 0 1 1h6a1 1 0 0 0 1-1V1a1 1 0 0 0-1-1H5Zm.5 14a.5.5 0 1 1 0 1 .5.5 0 0 1 0-1Zm2 0a.5.5 0 1 1 0 1 .5.5 0 0 1 0-1ZM5 1.5a.5.5 0 0 1 .5-.5h5a.5.5 0 0 1 0 1h-5a.5.5 0 0 1-.5-.5ZM5.5 3h5a.5.5 0 0 1 0 1h-5a.5.5 0 0 1 0-1Z"/>
                </svg>
                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-pc" viewBox="0 0 16 16">
                      <path d="M5 0a1 1 0 0 0-1 1v14a1 1 0 0 0 1 1h6a1 1 0 0 0 1-1V1a1 1 0 0 0-1-1H5Zm.5 14a.5.5 0 1 1 0 1 .5.5 0 0 1 0-1Zm2 0a.5.5 0 1 1 0 1 .5.5 0 0 1 0-1ZM5 1.5a.5.5 0 0 1 .5-.5h5a.5.5 0 0 1 0 1h-5a.5.5 0 0 1-.5-.5ZM5.5 3h5a.5.5 0 0 1 0 1h-5a.5.5 0 0 1 0-1Z"/>
                </svg>
                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-pc" viewBox="0 0 16 16">
                      <path d="M5 0a1 1 0 0 0-1 1v14a1 1 0 0 0 1 1h6a1 1 0 0 0 1-1V1a1 1 0 0 0-1-1H5Zm.5 14a.5.5 0 1 1 0 1 .5.5 0 0 1 0-1Zm2 0a.5.5 0 1 1 0 1 .5.5 0 0 1 0-1ZM5 1.5a.5.5 0 0 1 .5-.5h5a.5.5 0 0 1 0 1h-5a.5.5 0 0 1-.5-.5ZM5.5 3h5a.5.5 0 0 1 0 1h-5a.5.5 0 0 1 0-1Z"/>
                </svg>
                    <h4>Equipment in Stock</h4>
                    <h2>
                    <?php 
                            foreach ($stock as $stockcount ) {
                        
                                echo $stockcount['count'];
                            } 
                        
                        ?>
                    </h2>
                </div>
              </div>
            </div>
        </div>

        <div class="col-lg-4 col-md-4 col-sm-6 col-12 mb-2 mt-4">
            <div class="inforide">
              <div class="row">
                <div class="col-lg-3 col-md-4 col-sm-4 col-4 ridetwo">
                    <img src="">
                </div>
                <div class="col-lg-9 col-md-8 col-sm-8 col-8 fontsty">
                <svg xmlns="http://www.w3.org/2000/svg" width="30" height="30" fill="red" class="bi bi-exclamation-circle" viewBox="0 0 16 16">
                    <path d="M8 15A7 7 0 1 1 8 1a7 7 0 0 1 0 14zm0 1A8 8 0 1 0 8 0a8 8 0 0 0 0 16z"/>
                    <path d="M7.002 11a1 1 0 1 1 2 0 1 1 0 0 1-2 0zM7.1 4.995a.905.905 0 1 1 1.8 0l-.35 3.507a.552.552 0 0 1-1.1 0L7.1 4.995z"/>
                </svg>
                    <h4>Equipment Owned by Termed Employees</h4>
                    <a href="termed.php">View</a>
                    <h2>
                    <?php 
                            foreach ($term as $termcount ) {
                        
                                echo $termcount['count'];
                            } 
                        
                        ?>
                    </h2>
                </div>
              </div>
            </div>
        </div>

    </div>
  </div>
</div>


    <div class="d-flex justify-content-center">  
        <div class="d-flex justify-content-center w-85 mt-5">
            <div class="mx-5 d-flex justify-content-center rounded-end shadow-lg" style="background:#C0C5D1">
                <div class="row row-cols-1 row-cols-md-3 g-4">
                    <div class="col p-5">
                        <div class="card h-100">
                               
                            <div class="card-body">
                                <div class="d-grid gap-2 mx-5 my-2">
                                    <a href="./agregarinventario.php" class="btn btn-primary">Add Equipment</a>
                                </div>
                                <p class="card-text text-center mt-3">Add to equipment to inventory</p>
                            </div>
                            
                        </div>
                    </div>
                    <div class="col p-5">
                        <div class="card h-100">
                               
                            <div class="card-body">
                                <div class="d-grid gap-2 mx-5 my-2">
                                    <a href="transacciones.php" class="btn btn-primary">Transaction History</a>
                                </div>
                                <p class="card-text text-center mt-3">View past transactions</p>
                            </div>
                        </div>
                    </div>
                    <div class="col p-5">
                        <div class="card h-100">
                               
                            <div class="card-body">
                                <div class="d-grid gap-2 mx-5 my-2">
                                    <a href="agregartransaccion.php" class="btn btn-primary">Individual Transaction</a>
                                </div>
                                <p class="card-text text-center mt-3">Create an individual transaction</p>
                            </div>
                            
                        </div>
                    </div>
                    <div class="col p-5">
                        <div class="card h-100">
                               
                            <div class="card-body">
                                <div class="d-grid gap-2 mx-5 my-2">
                                    <a href="equipscreen.php" class="btn btn-primary">Equipment</a>
                                </div>
                                <p class="card-text text-center mt-3">Equipment Inventory</p>
                            </div>
                            
                        </div>
                    </div>
                    <div class="col p-5">
                        <div class="card h-100">
                               
                            <div class="card-body">
                                <div class="d-grid gap-2 mx-5 my-2">
                                    <a href="deployments.php" class="btn btn-primary">Deployments</a>
                                </div>
                                <p class="card-text text-center mt-3">See current deployments</p>
                            </div>
                            
                        </div>
                    </div>
                    <div class="col p-5">
                        <div class="card h-100">
                               
                            <div class="card-body">
                                <div class="d-grid gap-2 mx-5 my-2">
                                    <a href="employees.php" class="btn btn-primary">Employees</a>
                                </div>
                                <p class="card-text text-center mt-3">View and import employee data</p>
                            </div>
                            
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</body>
<?php include "../tools/footer.php" ?>