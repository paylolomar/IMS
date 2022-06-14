<?php
    include_once '../tools/database.php';
    date_default_timezone_set('America/El_Salvador');
    session_start();

    if(!isset($_SESSION['rol'])){
        header('location: ../login.php');
    }else{
        if($_SESSION['rol'] != 1){
            header('location: ../login.php');
        }
    }

    $mensaje = '';
    //Revisar si se presiona el boton de agregar y validar que exista informacion en los campos y estos no esten vacios
    if(isset($_POST['returna'])){

        $descripcion=$_POST['descripcion'];
        $fecha=date("Y-m-d H:i:s");
        $id_usuario=$_SESSION['id_user'];
        $eeid=$_POST['eeid']; 
        $no_serial=$_POST['no_serial'];

        
        try{
            $database = new Database();
            $query = "INSERT INTO `movimiento` (`descripcion`, `fecha`, `razon`, `id_usuario`, `eeid`, `no_serial`) VALUES ('', :fecha, 'Return - Separation', :id_usuario, :eeid, :no_serial)";
            $stmt = $database->conectar()->prepare($query);
            $stmt->bindParam(':fecha', $fecha);
            $stmt->bindParam(':id_usuario', $id_usuario);
            $stmt->bindParam(':eeid', $eeid);
            $stmt->bindParam(':no_serial', $no_serial);

        if ($stmt->execute()) {
            
            header('location: agregartransaccion.php?message=Success');
            $mensaje = 'Success.';
        } else {
            $mensaje = 'Error.';
        }
        }
        catch(PDOException $e){
            
        }

        try{
            $database = new Database();
            $query = 'UPDATE equipo SET id_estado = 1 WHERE no_serial=:no_serial';
            $stmt = $database->conectar()->prepare($query);
            
            $stmt->bindParam(':no_serial', $no_serial);
            $stmt->execute();
        }
        catch(PDOException $Error){

        }
    }

    if(isset($_POST['returnb'])){


        $fecha=date("Y-m-d H:i:s");
        $id_usuario=$_SESSION['id_user'];
        $eeid=$_POST['eeid']; 
        $no_serial=$_POST['no_serial'];

        try{

        $database = new Database();
        $query ="INSERT INTO `movimiento` (`descripcion`, `fecha`, `razon`, `id_usuario`, `eeid`, `no_serial`) VALUES ('', :fecha, 'Return - Back to Site', :id_usuario, :eeid, :no_serial)";
        $stmt = $database->conectar()->prepare($query);
        
        $stmt->bindParam(':fecha', $fecha);
        $stmt->bindParam(':id_usuario', $id_usuario);
        $stmt->bindParam(':eeid', $eeid);
        $stmt->bindParam(':no_serial', $no_serial);
        }
        catch(PDOException $e){

        }

        "INSERT INTO `movimiento` (`descripcion`, `fecha`, `razon`, `id_usuario`, `eeid`, `no_serial`) VALUES ('', :fecha, 'Return - Back to Site', :id_usuario, :eeid, :no_serial)";
        $stmt = $database->conectar()->prepare($query);
        
        $stmt->bindParam(':fecha', $fecha);
        $stmt->bindParam(':id_usuario', $id_usuario);
        $stmt->bindParam(':eeid', $eeid);
        $stmt->bindParam(':no_serial', $no_serial);

        if ($stmt->execute()) {
            
            $mensaje = 'Success.';
        } else {
            $mensaje = 'Error.';
        }

        try{
            $database = new Database();
            $query = 'UPDATE equipo SET id_estado = 1 WHERE no_serial=:no_serial';
            $stmt = $database->conectar()->prepare($query);
            
            $stmt->bindParam(':no_serial', $no_serial);
            $stmt->execute();
        }
        catch(PDOException $Error){

        }
    }

    if(isset($_POST['submit'])){
        
        if(isset($_POST['no_serial']) && isset($_POST['id_atributo']) && isset($_POST['id_estado']) && isset($_POST['id_ubicacion']) ){
            if ($_POST['no_serial']!='' && $_POST['id_atributo']!='' && $_POST['id_estado']!='' && $_POST['id_ubicacion']!=''){
                
                
                $no_serial=$_POST['no_serial'];
                $id_atributo=(int)$_POST['id_atributo'];
                $id_estado=(int)$_POST['id_estado'];
                $id_ubicacion=(int)$_POST['id_ubicacion'];

                try{
                    $database = new Database();
                    $query = 'SELECT COUNT(*)+1 FROM equipo';
                    $stmt = $database->conectar()->prepare($query);
                    $stmt->execute();
                    $registro = $stmt->fetch(PDO::FETCH_NUM);
                   
                }catch(PDOException $Error) { $mensaje = "Ocurrió un errError."; }

                if($registro == true){

                    
                    try{
                        $database = new Database();
                        $query = 'INSERT INTO equipo( no_serial, id_atributo, id_estado, id_ubicacion) VALUES (:no_serial, :id_atributo, :id_estado, :id_ubicacion)';
                        $stmt = $database->conectar()->prepare($query);
                        $stmt->bindParam(':no_serial', $no_serial);
                        $stmt->bindParam(':id_atributo', $id_atributo);
                        $stmt->bindParam(':id_estado', $id_estado);
                        $stmt->bindParam(':id_ubicacion', $id_ubicacion);

                        if ($stmt->execute()) {
                            $mensaje = 'Success';
                            
                        } else {
                            $mensaje = 'Error.';
                        }

                    }catch(PDOException $Error) { $mensaje = "Error."; }
                    
                }
                
            }else{
                $mensaje='Please fill all required info'; 
            }
        }
    }            
?>


<?php include "../tools/head.php" ?>
<body class="form-v10">


<?php include "../tools/navbar.php" ?>
    <div class="page-content">
		<div class="form-v10-content mt-3">

                <script>
                   function GetAssignment(str) {
                        if (str.length == 0) {
                            document.getElementById("eeid").value = "";
                            document.getElementById("status").value = "";
                            return;
                        }
                        else {

                            // Creates a new XMLHttpRequest object
                            var xmlhttp = new XMLHttpRequest();
                            xmlhttp.onreadystatechange = function () {

                                // Defines a function to be called when
                                // the readyState property changes
                                if (this.readyState == 4 && 
                                        this.status == 200) {
                                        
                                    // Typical action to be performed
                                    // when the document is ready
                                    console.log(this.responseText);
                                    var myObj = JSON.parse(this.responseText);

                                    // Returns the response data as a
                                    // string and store this array in
                                    // a variable assign the value 
                                    // received to first name input field
                                        
                                    eid = document.getElementById("eeid").value = myObj[0];
                                    stat = document.getElementById("status").value = myObj[1];
                                    
                                }
                            };

                            // xhttp.open("GET", "filename", true);
                            xmlhttp.open("GET", "checkassignment.php?no_serial=" + str, true);
                                
                            // Sends the request to the server
                            xmlhttp.send();

                    
                        }
                    }
                   
                    

                </script>

                <script>
                    function CheckSerial()
                    {
                            var no_serial=document.getElementById( "no_serial" ).value;

                            if(no_serial)
                            {
                                $.ajax({
                                    type: 'POST',
                                    url: 'checkserial.php',
                                    data: {
                                        no_serial:no_serial,
                                },
                                success: function (response) {
                                    $( '#no_serial' ).html(response);
                                    if(response=="NOEXISTE")   
                                    {
                                                $('#add').prop('disabled', false).show();
                                                $('#returna').hide();
                                                $('#returnb').hide();
                                                $('#id_atributo').show();
                                                $('#status').val("");
                                                $('#eeid').val("");
                                                
                                    }
                                    else
                                    {
                                                $('#add').prop('disabled', true).hide();
                                                $('#returna').prop('disabled', false).show();
                                                $('#returnb').prop('disabled', false).show();
                                                $('#id_atributo').prop('disabled', true).hide();
                                                $('#id_ubicacion').prop('disabled', true).hide();
                                                $('#id_estado').prop('disabled', true).hide();
                                    }
                                }
                                });
                            }
                            else
                            {
                            $( '#no_serial' ).html("");
                            return false;
                        }
                    }
                </script>
                <form class="form-detail" method="POST">

                <div class="form-left">	
                <h2>Add Equipment</h2>						
					    <div class="form-row">
                        <label for="no_serial">No_Serial:</label><br/>
                        <input id= "no_serial" type="text" name="no_serial" required pattern="[0-9A-Za-z]{3,30}" placeholder="No_Serial" title="Solo debe ingresar letras y numeros entre 3 y 30 caracteres." oninput="GetAssignment(this.value);CheckSerial(this.value)" require>
                        </div>
                    <div class="form-row">
                        <label for="numero_tracking">Model:</label><br/>
                        <select name="id_atributo" id="id_atributo" required>
                                <option disabled selected value>Select</option>
                                <?php
                                    try{
                                    $database = new Database();
                                    $query = 'SELECT id, modelo FROM atributo';
                                    $stmt = $database->conectar()->prepare($query);
                                    $stmt->execute();
                                    $resultado = $stmt->fetchAll();
                                    if($resultado && $stmt->rowCount()>0) {
                                        foreach ($resultado as $fila) {
                                            echo '<option value="'.$fila['id'].'">'.$fila['modelo'].'</option>';
                                        }
                                    }
                                    }catch(PDOException $Error) { $mensaje = "Error."; }
                                ?>
                            </select>	
                            <span class="select-btn">
                                <i class="zmdi zmdi-chevron-down"></i>
                            </span>
                    </div>	
                    <div class="form-row">
                    <label for="numero_tracking">Status:</label><br/>
                        <select name="id_estado" id="id_estado" required>
                            <option disabled selected value>Select</option>
                            <?php
                                try{
                                $database = new Database();
                                $query = 'SELECT id, estado FROM estado_equipo';
                                $stmt = $database->conectar()->prepare($query);
                                $stmt->execute();
                                $resultado = $stmt->fetchAll();
                                if($resultado && $stmt->rowCount()>0) {
                                    foreach ($resultado as $fila) {
                                        echo '<option value="'.$fila['id'].'">'.$fila['estado'].'</option>';
                                    }
                                }
                                }catch(PDOException $Error) { $mensaje = "Error."; }
                            ?>
                        </select>
                        <span class="select-btn">
                                <i class="zmdi zmdi-chevron-down"></i>
                            </span>
                    </div>	
                    <div class="form-row">
					<label for="id_ubicacion">Location:</label><br/>
                        <select name="id_ubicacion" id="id_ubicacion" required>
                            <option disabled selected value>Select</option>
                            <?php
                                try{
                                $database = new Database();
                                $query = 'SELECT id, nombre_ubicacion FROM ubicaciones';
                                $stmt = $database->conectar()->prepare($query);
                                $stmt->execute();
                                $resultado = $stmt->fetchAll();
                                if($resultado && $stmt->rowCount()>0) {
                                    foreach ($resultado as $fila) {
                                        echo '<option value="'.$fila['id'].'">'.$fila['nombre_ubicacion'].'</option>';
                                    }
                                }
                                }catch(PDOException $Error) { $mensaje = "Error."; }
                            ?>
                        </select>
                        <span class="select-btn">
                                <i class="zmdi zmdi-chevron-down"></i>
                            </span>
                    </div>		
				</div>
           
                <div class="form-right">
                <br><br><br><br>				
                    <?php 
                        if(!empty($mensaje)){
                            echo '<div class="alert alert-info alert-dismissible mx-5">';
                            echo '<button type="button" class="close" data-dismiss="alert">&times;</button>';
                            echo '<strong>Alert: </strong>'.$mensaje.'.';
                            echo '</div>';
                        }
                    ?>       
                    
                        <div class="form-row">
                                
                                    <label class="font-weight-bold" style="color:white">Status:</label>
                                    <input type="text" name="status" 
                                        id="status" class="form-control"
                                        
                                        value="" readonly>
                                
                        </div>

                        <div class="form-row">
                                
                                    <label class="font-weight-bold" style="color:white">Assigned To:</label>
                                    <input type="text" name="eeid" 
                                        id="eeid" class="form-control"
                                        
                                        value="" readonly>
                                
                        </div>
                    <div class="form-group">
                        <div class="form-row-last-2 form-row-1">
                            <a class="cancel" href="./admin.php">Cancel</a>
                        </div>                   
                        <div class="form-row-last form-row-2">
                            <input id="add" type="submit" name="submit" class="register" value="Add">
                        </div>

                        <div class="form-row-last form-row-2">
                            <input id="returna" type="submit" name="returna" class="register" value="Return - Separation" style= "display: none">
                        </div>
                        <div class="form-row-last form-row-2">
                            <input id="returnb" type="submit" name="returnb" class="register" value="Return - Back to Site" style= "display: none" >
                        </div>

                    </div>                  					
				</div> 
            </form>
        </div>
    </div>
</body>
<?php include "../tools/footer.php" ?>