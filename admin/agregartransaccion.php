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
    if(isset($_GET['message'])){
        $mensaje = $_GET['message'];
    }
    else{
        $mensaje='';
    }
        

    if(isset($_POST['submit'])){
        
        if(isset($_POST['descripcion']) && isset($_POST['razon']) && isset($_POST['eeid']) && isset($_POST['no_serial']) ){
            if ($_POST['razon']!='' && $_POST['eeid']!='' && $_POST['no_serial']!=''){
                
                if(isset($_POST['id_atributo'])&& isset($_POST['id_estado_eq'])){

                    $no_serial=$_POST['no_serial'];
                    $model=(int)$_POST['id_atributo']; 
                    $status=(int)$_POST['id_estado_eq'];

                    try{
                        $database = new Database();
                        $query = 'INSERT INTO `equipo` (`no_serial`, `id_atributo`, `id_estado`, `id_ubicacion`) VALUES (:no_serial, :id_atributo, :id_estado_eq, 1)';
                        $equip = $database->conectar()->prepare($query);
                        $equip->bindParam(':no_serial', $no_serial);
                        $equip->bindParam(':id_atributo', $model);
                        $equip->bindParam(':id_estado_eq', $status);
                        $equip->execute();
                    }
                    catch(PDOException $Error){
                        $mensaje = "Error";
                        
                    }

                }
                
                $descripcion=$_POST['descripcion'];
                $fecha=date("Y-m-d H:i:s");
                $razon=$_POST['razon'];
                $id_usuario=$_SESSION['id_user'];
                $eeid=$_POST['eeid']; 
                $no_serial=$_POST['no_serial'];

                try{
                    $database = new Database();
                    $query = 'SELECT COUNT(*)+1 FROM movimiento';
                    $stmt = $database->conectar()->prepare($query);
                    $stmt->execute();
                    $registro = $stmt->fetch(PDO::FETCH_NUM);
                   
                }catch(PDOException $Error) { $mensaje = "Error"; }

                if($registro == true){

                    
                    try{
                        $database = new Database();
                        $query = 'INSERT INTO `movimiento` (`descripcion`, `fecha`, `razon`, `id_usuario`, `eeid`, `no_serial`) VALUES (:descripcion, :fecha, :razon, :id_usuario, :eeid, :no_serial)';
                        $stmt = $database->conectar()->prepare($query);
                        $stmt->bindParam(':descripcion', $descripcion);
                        $stmt->bindParam(':fecha', $fecha);
                        $stmt->bindParam(':razon', $razon);
                        $stmt->bindParam(':id_usuario', $id_usuario);
                        $stmt->bindParam(':eeid', $eeid);
                        $stmt->bindParam(':no_serial', $no_serial);

                        if ($stmt->execute()) {
                           
                            header('location: agregartransaccion.php?message=Success'.$fecha);
                            $mensaje = 'Success.';
                        } else {
                            $mensaje = 'Error.';
                        }

                        

                    }catch(PDOException $Error) { $mensaje = "Error"; }


                    if (isset($_POST['razon'])) {
                        switch ($_POST['razon']) {
                            case 'Assignment':
                                
                                try{
                                    $database = new Database();
                                    $query = 'UPDATE equipo SET id_estado = 3 WHERE no_serial=:no_serial';
                                    $stmt = $database->conectar()->prepare($query);
                                    
                                    $stmt->bindParam(':no_serial', $no_serial);
                                    $stmt->execute();
                                }
                                catch(PDOException $Error){
            
                                }
                                break;
                            case 'Return – Back to Site':
                                echo "Return – Back to Site";
                                try{
                                    $database = new Database();
                                    $query = 'UPDATE equipo SET id_estado = 1 WHERE no_serial=:no_serial';
                                    $stmt = $database->conectar()->prepare($query);
                                    
                                    $stmt->bindParam(':no_serial', $no_serial);
                                    $stmt->execute();
                                }
                                catch(PDOException $Error){
            
                                }
                                break;
                            case 'Return – Replacement':
                                echo "Return – Replacement";
                                try{
                                    $database = new Database();
                                    $query = 'UPDATE equipo SET id_estado = 4 WHERE no_serial=:no_serial';
                                    $stmt = $database->conectar()->prepare($query);
                                    
                                    $stmt->bindParam(':no_serial', $no_serial);
                                    $stmt->execute();
                                }
                                catch(PDOException $Error){
            
                                }
                                break;
                            case 'Return - Separation':
                               
                                try{
                                    $database = new Database();
                                    $query = 'UPDATE equipo SET id_estado = 1 WHERE no_serial=:no_serial';
                                    $stmt = $database->conectar()->prepare($query);
                                    
                                    $stmt->bindParam(':no_serial', $no_serial);
                                    $stmt->execute();
                                }
                                catch(PDOException $Error){
            
                                }
                                break;
                            case 'Repair':
                                
                                try{
                                    $database = new Database();
                                    $query = 'UPDATE equipo SET id_estado = 1 WHERE no_serial=:no_serial';
                                    $stmt = $database->conectar()->prepare($query);
                                    
                                    $stmt->bindParam(':no_serial', $no_serial);
                                    $stmt->execute();
                                }
                                catch(PDOException $Error){
            
                                }
                                break;
                        }
                    }
                   
                }
                
            }else{
                $mensaje='Debe llenar los campos requeridos.'; 
            }
        }
    }            
?>
<?php include "../tools/head.php" ?>
<body class="form-v10">
<?php include "../tools/navbar.php" ?>
    <div class="page-content">
		<div class="form-v10-content mt-3">
                <form class="form-detail" method="POST">
                <script>
                    // Execute this code when page is
                    // totally loaded
                    $(document).ready(function () {
                        /* Setting the autocomplete of
                        input field to off to make 
                        autofill to disable */
                        $("#eeid").attr("autocomplete", "off");
                        $("#descripcion").attr("autocomplete", "off");
                        $("#no_serial").attr("autocomplete", "off");
                    });
                </script>

                <script>
                // onkeyup event will occur when the user 
                // release the key and calls the function
                // assigned to this event
                function GetEEID(str) {
                    if (str.length == 0) {
                        document.getElementById("first_name").value = "";
                        document.getElementById("last_name").value = "";
                        document.getElementById("program").value = "";
                        return;
                    }
                    else {
                        // Creates a new XMLHttpRequest object
                        var xmlhttp = new XMLHttpRequest();
                        xmlhttp.onreadystatechange = function () {
                            // Defines a function to be called when
                            // the readyState property changes
                            if (this.readyState == 4 && this.status == 200) {    
                                // Typical action to be performed
                                // when the document is ready
                                console.log(this.responseText);
                                var myObj = JSON.parse(this.responseText);
                                // Returns the response data as a
                                // string and store this array in
                                // a variable assign the value 
                                // received to first name input field
                                document.getElementById
                                ("first_name").value = myObj[0];
                                // Assign the value received to
                                // last name input field
                                document.getElementById
                                ("last_name").value = myObj[1];
                                document.getElementById
                                ("program").value = myObj[2];
                            }
                        };
                    // xhttp.open("GET", "filename", true);
                    xmlhttp.open("GET", "busquedaeeid.php?eeid=" + str, true);        
                    // Sends the request to the server
                    xmlhttp.send();
                    }
                }
                function GetEquip(str) {
                    if (str.length == 0) {
                        document.getElementById("make").value = "";
                        document.getElementById("model").value = "";
                        document.getElementById("status").value = "";
                        return;
                    }else{
                        // Creates a new XMLHttpRequest object
                        var xmlhttp = new XMLHttpRequest();
                        xmlhttp.onreadystatechange = function () {
                            // Defines a function to be called when
                            // the readyState property changes
                            if (this.readyState == 4 && this.status == 200) { 
                                // Typical action to be performed
                                // when the document is ready
                                console.log(this.responseText);
                                var myObj = JSON.parse(this.responseText);
                                // Returns the response data as a
                                // string and store this array in
                                // a variable assign the value 
                                // received to first name input field
                                document.getElementById
                                ("make").value = myObj[0];
                                // Assign the value received to
                                // last name input field
                                document.getElementById
                                ("model").value = myObj[1];
                                document.getElementById
                                ("status").value = myObj[2];
                            }
                        };
                    // xhttp.open("GET", "filename", true);
                    xmlhttp.open("GET", "busquedaequip.php?no_serial=" + str, true);
                    // Sends the request to the server
                    xmlhttp.send();
                    }
                }
                function GetModel(str) {
                    if (str.length == 0) {
                        document.getElementById("model").value = "";
                        return;
                    }else{
                        // Creates a new XMLHttpRequest object
                        var xmlhttp = new XMLHttpRequest();
                        xmlhttp.onreadystatechange = function () {
                            // Defines a function to be called when
                            // the readyState property changes
                            if (this.readyState == 4 && this.status == 200) { 
                                // Typical action to be performed
                                // when the document is ready
                                console.log(this.responseText);
                                var myObj = JSON.parse(this.responseText);
                                // Returns the response data as a
                                // string and store this array in
                                // a variable assign the value 
                                // received to first name input field
                                document.getElementById
                                ("model").value = myObj[0];
                            }
                        };
                    // xhttp.open("GET", "filename", true);
                    xmlhttp.open("GET", "busquedamodelo.php?product_no=" + str, true);
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
                    $('#id_atributo').prop('disabled', false).show();
                    $('#id_estado_eq').prop('disabled', false).show();
                }
                else
                {
                    $('#id_atributo').prop('disabled', true).hide();
                    $('#id_estado_eq').prop('disabled', true).hide();
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
<script>
    function Checkproduct_no()
    {
        var product_no=document.getElementById( "product_no" ).value;

        if(product_no)
        {
            $.ajax({
                type: 'POST',
                url: 'checkproductno.php',
                data: {
                    product_no:product_no,
            },
            success: function (response) {
                $( '#product_no' ).html(response);
                if(response=="NOEXISTE")   
                {
                    $('#id_atributo').prop('disabled', false).show();
                    $('#id_estado_eq').prop('disabled', false).show();
                }
                else
                {
                    $('#id_atributo').prop('disabled', true).hide();
                    $('#id_estado_eq').prop('disabled', true).hide();
                }
            }
            });
        }
        else
        {
        $( '#product_no' ).html("");
        return false;
    }
}
</script>


                <div class="form-left">	
                        <h2>New Transaction</h2>		
                        <div class="form-row" id="myForm">
                                <label for="eeid">Employee ID:</label><br/>
                                <input type="text" name="eeid" id="eeid"required pattern="[0-9A-Za-z]{3,30}" placeholder="EEID" title="Solo debe ingresar letras y numeros entre 3 y 30 caracteres." oninput="GetEEID(this.value)"  require>
                        </div>


                        <div class="form-row">
                        <label for="razon">Transaction Type:</label><br/>
                        <select name="razon" id="razon" required>
                            <option disabled selected value>Select</option>
                            <option value="Assignment">Assignment</option>
                            <option value="Return – Back to Site">Return – Back to Site</option>
                            <option value="Return – Replacement">Return – Replacement</option>
                            <option value="Return - Separation">Return - Separation</option>
                            <option value="Repair">Repair</option>
                            
                        </select>
						<span class="select-btn">
						  	<i class="zmdi zmdi-chevron-down"></i>
						</span>
					</div>	

					    <div class="form-row">
                            <label for="descripcion">Comment:</label><br/>
                            <input type="text" name="descripcion" id="descripcion"  placeholder="Comment" title="Solo debe ingresar letras y numeros entre 3 y 30 caracteres." >
                        </div>
                        
                        <div class="form-row">
                        
                                <label for="razon">No_Serial:</label><br/>
                                <input type="text" name="no_serial" id="no_serial" required pattern="[0-9A-Za-z]{3,30}" placeholder="No_Serial" title="Solo debe ingresar letras y numeros entre 3 y 30 caracteres."  oninput="GetEquip(this.value); CheckSerial(this.value)" require>
                        </div>

                        <div class="form-row">
                        
                                <label for="razon">Product_No:</label><br/>
                                <input type="text" name="product_no" id="product_no" placeholder="Product_No" title="Solo debe ingresar letras y numeros entre 3 y 30 caracteres."  oninput="GetEquip(this.value); CheckSerial(this.value)" require>
                        </div>
                    
                        <div class="form-row">
					<label for="id_atributo">Model:</label><br/>
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
                                }catch(PDOException $Error) { $mensaje = "Error"; }
                            ?>
                        </select>
                        <span class="select-btn">
                                <i class="zmdi zmdi-chevron-down"></i>
                            </span>
                    </div>

                    <div class="form-row">
					<label for="id_estado_eq">Status:</label><br/>
                        <select name="id_estado_eq" id="id_estado_eq" required >
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
                                }catch(PDOException $Error) { $mensaje = "Error"; }
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
                                
                                    <label class="font-weight-bold" style="color:white">First Name:</label>
                                    <input type="text" name="first_name" 
                                        id="first_name" class="form-control"
                                        
                                        value="" readonly >
                               
                        </div>
                        <div class="form-row">
                                
                                    <label class="font-weight-bold" style="color:white">Last Name:</label>
                                    <input type="text" name="last_name" 
                                        id="last_name" class="form-control"
                                       
                                        value="" readonly >
                            
                         </div>
                         <div class="form-row">
                                
                                    <label class="font-weight-bold" style="color:white">Program:</label>
                                    <input type="text" name="program" 
                                        id="programa" class="form-control"
                                        
                                        value="" readonly>
                                
                         </div>
                    
                        <div class="form-row">
                                    
                                    <label class="font-weight-bold" style="color:white">Make:</label>
                                    <input type="text" name="make" 
                                        id="make" class="form-control"
                                        
                                        value="" readonly >
                            
                        </div>
                        <div class="form-row">
                                
                                    <label class="font-weight-bold" style="color:white">Model:</label>
                                    <input type="text" name="model" 
                                        id="model" class="form-control"
                                        
                                        value="" readonly >
                            
                        </div>
                        <div class="form-row">
                                
                                    <label class="font-weight-bold" style="color:white">Status:</label>
                                    <input type="text" name="status" 
                                        id="status" class="form-control"
                                        
                                        value="" readonly>
                                
                        </div>

                    <div class="form-group">
                        
                        <div class="form-row-last-2 form-row-1" style="color:white">
                            <a class="cancel" href="./admin.php">Cancel</a>
                        </div>                   
                        <div class="form-row-last form-row-2">
                            <input type="submit" name="submit" class="register" value="Add">
                        </div>
                    </div>
                        					
				</div> 
            </form>
        </div>
    </div>

    
</body>
<?php include "../tools/footer.php" ?>