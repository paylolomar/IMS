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
        $consulta  ="SELECT eq.no_serial, ee.estado, ub.nombre_ubicacion, atr.modelo, atr.tipo  FROM  equipo as eq JOIN estado_equipo as ee ON ee.id=eq.id_estado JOIN atributo as atr on atr.id=eq.id_atributo JOIN ubicaciones as ub on ub.id=eq.id_ubicacion;";
        $datos = $database->conectar()->prepare($consulta);
        $datos->execute();
        $resultado = $datos->fetchAll();
    }catch(PDOException $Error) {$mensaje = "Ocurrió un error al cargar los registros."; }

    if (isset($_POST['submit']) && isset($_POST['busqueda']) && $_POST['busqueda']!=''){
        try{
        $search = $_POST['busqueda'];
        $database = new Database();
        $consulta  = "SELECT eq.no_serial, ee.estado, ub.nombre_ubicacion, atr.modelo, atr.tipo  FROM  equipo as eq JOIN estado_equipo as ee ON ee.id=eq.id_estado JOIN atributo as atr on atr.id=eq.id_atributo JOIN ubicaciones as ub on ub.id=eq.id_ubicacion WHERE (eq.no_serial LIKE '%".$search."%' OR ee.estado LIKE '%".$search."%' OR ub.nombre_ubicacion LIKE '%".$search."%' OR atr.modelo LIKE '%".$search."%' OR atr.tipo LIKE '%".$search."%');";
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
                <a href="./equipscreen.php" class="btn btn-success mx-1 d-flex justify-content-center" data-bs-toggle="tooltip" data-bs-placement="top" title="Back"><span class="material-icons">arrow_back</span></a> 
            </div>
            <div class="p-2 w-100">
                <h1 class="display-6">Equipment</h1>
            </div>
        </div>
        <form class="d-flex py-3" method="POST">
                <input class="form-control ml-2 ml-md-5 mr-1" name="busqueda" type="search" placeholder="Search ...">
                <button type="submit" name="submit" class="btn btn-outline-primary ml-1 mr-2 mr-md-5" data-bs-toggle="tooltip" data-bs-placement="top" title="Search">Search</button>
        </form>
        <div class="table-responsive table-hover shadow bg-body rounded">
        <div class="btn-group pull-right">

        <form action="importequipos.php" method="post" enctype="multipart/form-data">
            <input type="file" name="file" />
            <input type="submit" class="btn btn-primary" name="importSubmit" value="IMPORT">
        </form>

        <script src="../tools/table2excel/dist/jquery.table2excel.js"></script>
	
</div>
<?php

    if ($resultado && $datos->rowCount()>0) {?>

        <button class="btn btn-success exportToExcel">Export to XLS</button>

        <table id="data_table" class="table align-middle mb-0 table2excel">
            <thead class="text-dark" style="background-color:#F3CC4F;">
                <tr>
                    
                    <th scope="col">Serial</th>
                    <th scope="col">Status</th>
                    <th scope="col">Location</th>
                    <th scope="col">Model</th>
                    <th scope="col">Type</th>
                    
                </tr>
            </thead>
            <tbody>
            <?php foreach ($resultado as $fila ) {?>
                <tr>
                    
                    
                    <td><?php echo escape($fila['no_serial']); ?></td>
                    <td><?php echo escape($fila['estado']); ?></td>
                    <td><?php echo escape($fila['nombre_ubicacion']); ?></td>
                    <td><?php echo escape($fila['modelo']); ?></td>
                    <td><?php echo escape($fila['tipo']); ?></td>
                    
                </tr>
            <?php } ?>
            </tbody>
        </table>

        
        <script>
			$(function() {
				$(".exportToExcel").click(function(e){
					var table = $(this).next('.table2excel');
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
            $mensaje = 'No results: <strong>'.$_POST['busqueda'].'</strong>';
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