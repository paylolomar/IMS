<?php
    session_start();

    if(!isset($_SESSION['rol'])){
        header('location: ../login.php');
    }else{
        if($_SESSION['rol'] != 1){
            header('location: ../login.php');
        }
    }

?>
<?php include "../tools/head.php" ?>
<body>
    <?php include "../tools/navbar.php" ?>
    <div class="d-flex justify-content-center">  
        <div class="d-flex justify-content-center w-85 mt-5">
            <div class="mx-5 d-flex justify-content-center rounded-end shadow-lg" style="background:lightgray">
                <div class=" row row-cols-1 row-cols-md-2 g-4">
                    <div class="col p-5">
                        <div class="card h-100" style="width: 18rem;">
                            <div class="card-body">
                                <div class="d-grid gap-2 mx-0 my-2">
                                    <a href="./equipos.php" class="btn btn-primary">Equipment</a>
                                </div>
                                <p class="card-text text-center mt-3">See all equipment</p>
                            </div>
                            
                        </div>
                    </div>
                    <div class="col p-5">
                        <div class="card h-100" style="width: 18rem;">
                           
                            <div class="card-body">
                                <div class="d-grid gap-2 mx-0 my-2">
                                    <a href="./assignedequip.php" class="btn btn-primary">Assigned equipment</a>
                                </div>
                                <p class="card-text text-center mt-3">View all equipment currently in use by agents</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</body>
<?php include "../tools/footer.php" ?>