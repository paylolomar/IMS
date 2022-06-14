<?php
    include_once '../tools/database.php';
    include_once '../tools/funciones.php';

    $mensaje = '';
    $error_correo = '';
    $error_password = '';
    if(isset($_POST['submit'])){

        if(isset($_POST['correo']) && isset($_POST['correo_confirm']) && isset($_POST['password']) && isset($_POST['password_confirm'])){
            
            if ($_POST['correo']!='' && $_POST['correo_confirm']!='' && $_POST['password']!='' && $_POST['password_confirm']!=''){
                
                if(strcmp($_POST['correo'],$_POST['correo_confirm']) === 0){

                    if(validar_correo($_POST['correo'])){

                        if(strcmp($_POST['password'],$_POST['password_confirm']) === 0){
                        
                            if(validar_clave($_POST['password'],$error_password)){
                            
                                $correo=trim(strtolower($_POST['correo']));
                                $password = password_hash($_POST['password'], PASSWORD_BCRYPT);
                                
                                try{
                                $database = new Database();
                                $query = 'SELECT LOWER(correo) FROM usuarios WHERE correo = :correo';
                                $stmt = $database->conectar()->prepare($query);
                                $stmt->bindParam(':correo', $correo);
                                $stmt->execute();
                                }catch(PDOException $Error) { $mensaje = "Error loading data."; }
                                if($stmt->rowCount()<1){
                                    try{
                                    $database = new Database();
                                    $query = 'SELECT COUNT(*)+1 FROM usuarios WHERE id_rol = 3';
                                    $stmt = $database->conectar()->prepare($query);
                                    $stmt->execute();
                                    $registro = $stmt->fetch(PDO::FETCH_NUM);
                                    }catch(PDOException $Error) { $mensaje = "Error loading data."; }
                                
                                    if($registro == true){

                                        try{
                                        $database = new Database();
                                        $query = 'INSERT INTO usuarios(correo, password, id_rol, id_estado_usr) VALUES (:correo, :password, 1, 1)';
                                        $stmt = $database->conectar()->prepare($query);
                                        $stmt->bindParam(':correo', $correo);
                                        $stmt->bindParam(':password', $password);
                                        }catch(PDOException $Error) { $mensaje = "Ocurrió un error al cargar los registros."; }
                                        if ($stmt->execute()) {
                                            header('location: ../login.php');
                                        } else {
                                            $mensaje = 'Lo sentimos, ocurrió un error al crear su cuenta.';
                                        }
                                        
                                    }

                                }else{
                                    $mensaje = 'Email is already assigned.';
                                }
                            }
                        }else{
                            $error_password="Passwords must match.";
                        }            
                    }else{
                        $error_correo = 'Enter a valid email address';  
                    }
                }else{
                    $error_correo="Email addresses must match.";
                }
            }else{        
                $mensaje='Please fill out all required info.'; 
            }
        }
    }
?>
<?php include "../tools/head.php" ?>
<body class="form-v10">
    <?php include "../tools/navbar-extern.php" ?>
	<div class="page-content">
		<div class="form-v10-content mt-3">
			<form class="form-detail" method="POST">
				<div class="form-left">
					<h2>Datos Inicio de Sesión</h2>								
					<div class="form-row">
						<input type="email" name="correo" class="input-text" required pattern="[^@]+@[^@]+.[a-zA-Z]{2,6}" placeholder="Email">
					</div>
					<div class="form-row">
						<input type="email" name="correo_confirm" class="input-text" required placeholder="Confirm Email">
					</div>
                    <?php if(!empty($error_correo)){
                        echo '<div class="alert alert-warning alert-dismissible mx-5">';
                        echo '<button type="button" class="close" data-dismiss="alert">&times;</button>';
                        echo '<strong>Mensaje: </strong>'.$error_correo.'.';
                        echo '</div>';
                    } 
                    ?>
					<div class="form-row">
						<input type="password" name="password" class="input-text" required pattern="(?=.*\d)(?=.*[a-z])(?=.*[A-Z]).{8,}" placeholder="Password">
					</div>
					<div class="form-row">
						<input type="password" name="password_confirm" class="input-text" required placeholder="Confirm Password">
					</div>
                    <?php if(!empty($error_password)){
                        echo '<div class="alert alert-warning alert-dismissible mx-5">';
                        echo '<button type="button" class="close" data-dismiss="alert">&times;</button>';
                        echo '<strong>Mensaje: </strong>'.$error_password.'.';
                        echo '</div>';
                    } 
                    ?>									
				</div>
                    <?php 
                        if(!empty($mensaje)){
                            echo '<div class="alert alert-danger alert-dismissible mx-5">';
                            echo '<button type="button" class="close" data-dismiss="alert">&times;</button>';
                            echo '<strong>Mensaje: </strong>'.$mensaje.'.';
                            echo '</div>';
                        }
                    ?>                            
                    <div class="form-group">
                        <div class="form-row-last-2 form-row-1">
                            <a class="cancel" href="../login.php">Cancel</a>
                        </div>                   
                        <div class="form-row-last form-row-2">
                            <input type="submit" name="submit" class="register" value="Register">
                        </div>
                    </div>                  					
				</div>                
			</form>
		</div>
	</div>
</body>
<?php include "../tools/footer.php" ?>