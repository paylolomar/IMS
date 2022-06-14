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
        $consulta  ="SELECT * FROM  empleados ;";
        $datos = $database->conectar()->prepare($consulta);
        $datos->execute();
        $resultado = $datos->fetchAll();
    }catch(PDOException $Error) {$mensaje = "Ocurrió un error al cargar los registros."; }

    if (isset($_POST['submit']) && isset($_POST['busqueda']) && $_POST['busqueda']!=''){
        try{
        $search = $_POST['busqueda'];
        $database = new Database();
        $consulta  = "SELECT *  FROM  empleados  WHERE (eeid LIKE '%".$search."%' OR nombres LIKE '%".$search."%' OR apellidos LIKE '%".$search."%' OR departamento LIKE '%".$search."%' OR fecha_terminacion LIKE '%".$search."%'  OR direccion LIKE '%".$search."%'  OR cedula_id LIKE '%".$search."%' OR personal_mail LIKE '%".$search."%' OR supervisor LIKE '%".$search."%');";
        $datos = $database->conectar()->prepare($consulta);
        $datos->execute();
        $resultado = $datos->fetchAll();
        }catch(PDOException $Error) {$mensaje = "Ocurrió un error al cargar los registros."; }
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
                <h1 class="display-6">Employees</h1>
            </div>
        </div>
        <form class="d-flex py-3" method="POST">
                <input class="form-control ml-2 ml-md-5 mr-1" name="busqueda" type="search" placeholder="Search ...">
                <button type="submit" name="submit" class="btn btn-outline-primary ml-1 mr-2 mr-md-5" data-bs-toggle="tooltip" data-bs-placement="top" title="Search">Search</button>
        </form>
        <div class="table-responsive table-hover shadow bg-body rounded">
        <div class="btn-group pull-right">

        <form action="importemployees.php" method="post" enctype="multipart/form-data">
            <input type="file" name="file" />
            <input type="submit" class="btn btn-primary" name="importSubmit" value="IMPORT">
        </form>

        <script src="../tools/table2excel/dist/jquery.table2excel.js"></script>
	
</div>
<?php

    if ($resultado && $datos->rowCount()>0) {?>


        <table id="data_table" class="table align-middle mb-0 table2excel">
            <thead class="text-dark" style="background-color:#F3CC4F;">
                <tr>
                    
                    <th scope="col">EID</th>
                    <th scope="col">First Name</th>
                    <th scope="col">Last Name</th>
                    <th scope="col">Department</th>
                    <th scope="col">Termination Date</th>
                    <th scope="col">ID</th>
                    <th scope="col">Address</th>
                    <th scope="col">Personal Mail</th>
                    <th scope="col">Supervisor</th>
                    
                </tr>
            </thead>
            <tbody>
            <?php foreach ($resultado as $fila ) {?>
                <tr>
                    
                    
                    <td><?php echo escape($fila['eeid']); ?></td>
                    <td><?php echo escape($fila['nombres']); ?></td>
                    <td><?php echo escape($fila['apellidos']); ?></td>
                    <td><?php echo escape($fila['departamento']); ?></td>
                    <td><?php echo escape($fila['fecha_terminacion']); ?></td>
                    <td><?php echo escape($fila['cedula_id']); ?></td>
                    <td><?php echo escape($fila['direccion']); ?></td>
                    <td><?php echo escape($fila['personal_mail']); ?></td>
                    <td><?php echo escape($fila['supervisor']); ?></td>

                </tr>
            <?php } ?>
            </tbody>
        </table>

        <button class="btn btn-success exportToExcel">Export to XLS</button>
        <script>
			$(function() {
				$(".exportToExcel").click(function(e){
					var table = $(this).prev('.table2excel');
					if(table && table.length){
						var preserveColors = (table.hasClass('table2excel_with_colors') ? true : false);
						$(table).table2excel({
							exclude: ".noExl",
							name: "Excel Document Name",
							filename: "myFileName" + new Date().toISOString().replace(/[\-\:\.]/g, "") + ".xls",
							fileext: ".xls",
							exclude_img: true,
							exclude_links: true,
							exclude_inputs: true,
							preserveColors: preserveColors
						});
					}
				});
				
			});
		</script>
        
    <?php 
        } else { 
            $mensaje = 'No hay resultados para esta busqueda: <strong>'.$_POST['busqueda'].'</strong>';
        } 
        if(!empty($mensaje)){
            echo '<div class="alert alert-danger m-0 alert-dismissible">';
            echo '<button type="button" class="close" data-dismiss="alert">&times;</button>';
            echo '<strong>Mensaje: </strong>'.$mensaje.'.';
            echo '</div>';
        } 
    ?>


</body>
<?php include "../tools/footer.php" ?>