<header>
    <nav class="navbar navbar-expand-lg navbar-dark text-white" style="background-color: #1D4D9F;">
        <div class="container-fluid pt-2 pb-2">
            <a  href="../admin/admin.php" class="navbar-brand text-white" 
            <?php 
                if(isset($_SESSION['rol']) && $_SESSION['rol']){
                    switch($_SESSION['rol']){
                        case 1:
                            header(' href="admin.php" ');
                            break;
                        case 2:
                            header('href="techie.php"');
                            break;
                    }
                }
            ?>>
                <img src="../img/logo2-1.png" alt="" width="190" height="60" class="d-inline-block align-text-center">
                
            </a>
            <button class="navbar-toggler border-white" type="button" data-bs-toggle="collapse" data-bs-target="#navbarToggler1" aria-controls="navbarToggler1" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarToggler1">
                <ul class="navbar-nav me-auto mb-2 mb-lg-0">
                </ul>
                <li class="nav dropdown mr-5">
                    <a class="nav-link dropdown-toggle text-white d-flex align-items-center" href="#" id="navbarDropdown" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false"> 
                        <span class="material-icons my-auto mx-1">person</span> 
                          
                    </a>
                    <div class="dropdown-menu m-0 p-0" aria-labelledby="navbarDropdown">
                        <p class="dropdown-item d-flex justify-content-left text-wrap my-0 pt-2 pb-0">Signed in as:</p>
                        <p class="dropdown-item d-flex justify-content-left text-wrap my-0 pt-0 pb-2"><strong><?php echo $_SESSION['correo'];?></strong></p>
                        
                        <a class="dropdown-item d-flex justify-content-left text-white bg-dark" href="../logout.php"><span class="material-icons px-2">logout</span> Log Out</a>
                    </div>
                </li>
                    
            </div>
        </div>
    </nav>
</header>


