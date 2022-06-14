<?php

    include_once '../tools/database.php';
    include '../tools/utf-8.php';
    include '../tools/funciones.php';
    require('../tools/pdf/fpdf.php');
    setlocale(LC_ALL,"es_ES@euro","es_ES","esp");//Cambia region para mostrar fechas a español
      
   
     class PDF extends FPDF
    {
    //Cabecera de página
    function Header()
    {
        $this->SetFillColor(229,0,31);
        $this->Rect( 0,  1,  50,  5 ,  'F');
        $this->SetFillColor(69,172,54);
        $this->Rect( 50,  1,  10,  5 ,  'F');
        $this->SetFillColor(35,152,219);
        $this->Rect(60,  1,  10,  5 ,  'F');
        $this->SetFillColor(60,83,115);
        $this->Rect(70,  1,  1000,  5 ,  'F');
        
        $this->Image('../img/logo2-1.png',7,12,50);
        $this->SetFont('Times','',12);
        
        
        
    }
    }
        
    $mensaje='';
    session_start();
    

    if(!isset($_SESSION['rol'])){
        header('location: ../login.php');
    }else{
        if($_SESSION['rol'] != 1){
            header('location: ../login.php');
        }
    }
   
    
    if(isset($_POST['delete']) && $wave=$_SESSION['wave_id']){

        try{

            $wave=$_SESSION['wave_id'];
            $database = new Database(); 
            $query="DELETE FROM waves WHERE Wave = :wave "; /* Sql query para eliminar los registros en base al numero de wave*/
            $stmt = $database->conectar()->prepare($query);
            $stmt->bindParam(':wave', $wave);
            $stmt->execute();
                
            if ($stmt->execute()) {
                header("location: bulktransaction.php?wave=".$wave); /* recarga la pagina despues de ejecutar la consulta*/ 
            }
            else{
                echo 'error';
            }
        }
        catch(Exception $e){
            $mensaje = "Error"; 
        }   

    }


    //Mostrar datos en tabla basado en la wave seleccionada en la pantalla anterior
    if(isset($_GET['wave'])){
        try{
            $_SESSION['wave_id']=$_GET['wave'];
            $database = new Database();
            $consulta  ="SELECT w.wave, w.PC, w.PC_Model, w.Monitor1, w.Monitor1_Model, w.Monitor2, w.Monitor2_Model, w.eeid, emp.nombres, emp.apellidos, emp.cedula_id, emp.direccion, emp.departamento, emp.supervisor, emp.personal_mail FROM  waves as w 
            JOIN deployments as d on d.wave=w.wave 
            JOIN empleados as emp ON emp.eeid=w.eeid
            WHERE w.eeid=emp.eeid AND w.wave=:wave";
            $datos = $database->conectar()->prepare($consulta);
            $datos->bindParam(':wave',$_SESSION['wave_id']);
            $datos->execute();
            $resultado = $datos->fetchAll();
        }catch(PDOException $Error) {$mensaje = "Error"; }


        
        
    }

    
    //Creacion de reportes
    if(isset($_POST['salida'])){
        
        
        $pdf = new PDF();
        $pdf->SetMargins(15, 25);
        foreach ($resultado as $fila ){
        
            
            $pc=$fila['PC'];
            $pcm=$fila['PC_Model'];
            $m1=$fila['Monitor1'];
            $mmod1=$fila['Monitor1_Model'];
            $m2=$fila['Monitor2'];
            $mmod2=$fila['Monitor2_Model'];
            $wave=$_SESSION['wave_id'];
            
        $fecha=date("Y-m-d H:i:s");
            $id_usuario=$_SESSION['id_user'];
            $eid=$fila['eeid'];
            $nom= $fila['nombres'];
            $ape= $fila['apellidos'];
            $ced= $fila['cedula_id'];
            $dir= $fila['direccion'];
            $dep= $fila['departamento'];
                
            //Añade paginas por cada registro en la tabla
            $pdf->AddPage();
            
            $pdf->SetFont('Times','B',12);
            $pdf->Cell(170,20,'Pase de Salida',0,0,'C');
            $pdf->ln();
            
            
            $pdf->SetFont('Times','',12);
            $pdf->Cell(275,1,'Tegucigalpa, '. (strftime("%d de ".ucwords(strftime("%B")) ." del %Y"))  .'',0,0,'C');
            $pdf->Write(7,'Estimado Sub Administrador Milenys Avila,'); 
            
            $pdf->ln();
            
            $pdf->Write(7,'Altia Technology Park');
            
            $pdf->ln();
            $pdf->ln();
            
            $pdf->MultiCell(0,4.5,'Mediante el decreto legislativo PCM-031-2020 donde faculta a las empresas tanto publicas como privadas a trabajar bajo la modalidad de teletrabajo, solicitamos de sus buenos oficiospara autorizar la salida del equipo descrito en este pase de salida  debido a la situación actual del COVID-19. El empleado descrito a continuación se estará llevando el equipo de computo de nuestras instalaciones para su respectivo trabajo en su hogar. El equipo saldrá el dia de hoy '.date("d/m/Y"). 'con fecha indefinida para retorno, cuando ya no estemos en riesgo de poder regresar a trabajar en nuestras instalaciones.',0,'J');
            
            $pdf->SetFont('Times','B',6);
            $pdf->ln();
            $pdf->ln();
            $pdf->SetFillColor(142,169,219);
            $pdf->Cell(25,20,'Equipo',1,0,'C',1);
            
            $pdf->Cell(25,20,'Serie',1,0,'C',1);
            $pdf->Cell(25,20,'Modelo',1,0,'C',1);
            $pdf->Cell(35,20,'Nombre Completo',1,0,'C',1);
            $pdf->Cell(35,20,'Identidad',1,0,'C',1);
            $pdf->Cell(35,20,'Direccion',1,0,'C',1);
            $pdf->ln();
            $x=$pdf->GetX();
            $y=$pdf->GetY();
            $pdf->MultiCell(25,20,'PC',1,'C',1);
            $pdf->SetXY($x+25,$y);
            $pdf->Cell(25,20,''.$pc.'',1,0,'C');
            $pdf->Cell(25,20,''.$pcm.'',1,0,'C');
            $pdf->Cell(35,100,''.$nom. ' ' .$ape.'',1,0,'C');
            $pdf->Cell(35,100,''.$ced.'',1,0,'C');
            $pdf->Cell(35,100,''.$dir.'',1,0,'C');
            $pdf->ln();
            
            $x=$pdf->GetX();
            $y=$pdf->GetY();
            $pdf->SetXY($x,$y-80);
            $pdf->MultiCell(25,20,'Monitor',1,'C',1);
            $pdf->SetXY($x+25,$y-80);
            $pdf->Cell(25,20,''.$m1.'',1,0,'C');
            $pdf->Cell(25,20,''.$mmod1.'',1,0,'C');
            $pdf->ln();
            $x=$pdf->GetX();
            $y=$pdf->GetY();
            $pdf->MultiCell(25,20,'Monitor',1,'C',1);
            $pdf->SetXY($x+25,$y);
            $pdf->Cell(25,20,''.$m2.'',1,0,'C');
            $pdf->Cell(25,20,''.$mmod2.'',1,0,'C');
            $pdf->ln();
            $x=$pdf->GetX();
            $y=$pdf->GetY();
            $pdf->MultiCell(25,20,'Mouse',1,'C',1);
            $pdf->SetXY($x+25,$y);
            $pdf->Cell(25,20,'N/A',1,0,'C');
            $pdf->Cell(25,20,'LOGITECH MS216',1,0,'C');

            $pdf->ln();
            $x=$pdf->GetX();
            $y=$pdf->GetY();
            $pdf->MultiCell(25,20,'Keyboard',1,'C',1);
            $pdf->SetXY($x+25,$y);
            $pdf->Cell(25,20,'N/A',1,0,'C');
            $pdf->Cell(25,20,'LOGITECH KB116',1,0,'C');
            
            
        }
        $pdf->Output("Pass.pdf","I");

       
    }
    
 ///////////////////Reporte Waiver Español
    if(isset($_POST['waiveres'])){

        $pdf = new PDF();
        $pdf->SetMargins(15, 25);
        foreach ($resultado as $fila ){
            
            $pc=$fila['PC'];
            $pcm=$fila['PC_Model'];
            $m1=$fila['Monitor1'];
            $mmod1=$fila['Monitor1_Model'];
            $m2=$fila['Monitor2'];
            $mmod2=$fila['Monitor2_Model'];
            $wave=$_SESSION['wave_id'];
            
            $fecha=date("Y-m-d H:i:s");
            $id_usuario=$_SESSION['id_user'];
            $eid=$fila['eeid'];
            $nom= $fila['nombres'];
            $ape= $fila['apellidos'];
            $ced= $fila['cedula_id'];
            $dir= $fila['direccion'];
            $dep= $fila['departamento'];
            $sup= $fila['supervisor'];
            $mail= $fila['personal_mail'];
            $pdf->AddPage();

            $pdf->SetFont('Helvetica','B',12);
            $pdf->Cell(170,20,'Política de Trabajo Remoto en Casa',0,0,'C');
            $pdf->ln();
            $pdf->SetFont('helvetica','B',12);
            $pdf->MultiCell(0,4.5,'1. Objetivo ',0,'J');
            $pdf->SetFont('helvetica','',12);
            $pdf->MultiCell(0,4.5,'El propósito de esta política es establecer pautas para los empleados mientras trabajan desde su casa.',0,'J');
            $pdf->SetFont('helvetica','B',12);
            $pdf->ln();
            $pdf->MultiCell(0,4.5,'2. Alcance',0,'J');
            $pdf->SetFont('helvetica','',12);
            $pdf->MultiCell(0,4.5,'Esta política es aplicada a los empleados a quiénes se les ha permitido trabajar en una oficina en casa. La consideración para el trabajo en casa requiere de una recomendación escrita por parte del supervisor directo y será finalizada por el Gerente del Departamento y el Dpto. de Recursos Humanos.',0,'J');
            $pdf->SetFont('helvetica','B',12);
            $pdf->ln();
            $pdf->MultiCell(0,4.5,'3. Pautas de la Política',0,'J');
            $pdf->MultiCell(0,4.5,'Cumplimiento de políticas y procedimientos:',0,'J');
            $pdf->SetFont('helvetica','',12);
            $pdf->MultiCell(0,4.5,"Los empleados siguen obligados a cumplir con todas las reglas, prácticas e instrucciones de la compañía como se describe en el manual de Políticas de Recursos Humanos
    Expectativas del espacio de trabajo
            
    El trabajo remoto solo se realizara desde la residencia principal del empleado y se le requiere al empleado:
• Designar un espacio de trabajo silencioso y sin distracciones.
• Designar un espacio de trabajo dedicado a la ubicación e instalación de equipos que se utilizaran durante el trabajo desde casa (Teletrabajo).
• Mantener el espacio de trabajo bajo condiciones seguras, libre de descuidos y otros peligros contra el empleado y el equipo de trabajo.
• La Empresa no se responsabiliza de los costos asociados con la preparación de la oficina en el hogar del empleado, como ser la remodelación, mobiliario, o iluminación, ni de reparaciones o modificaciones en el espacio de la oficina en el hogar.
            
• El empleado debe presentar a la Gerencia, tres fotografías del espacio asignado al trabajo en casa antes de la implementación, donde sea posible.",0,'J');
            $pdf->SetFont('helvetica','B',12);
            $pdf-> Cell(0,4.5,'• Horario:',0,'J');
            $pdf->SetFont('helvetica','',12);
            $pdf->MultiCell(0,4.5,'El Supervisor directo aprobara un horario general, el cual será comunicado al empleado. Cualquier cambio que hubiese al horario deberá ser comunicado inmediatamente al supervisor.',0,'J');
            $pdf->SetFont('helvetica','B',12);
            $pdf-> Cell(0,4.5,'• Asistencia:',0,'J');
            $pdf->SetFont('helvetica','',12);
            $pdf->MultiCell(0,4.5,'El empleado debe cumplir con los horarios de asistencia y descanso según lo acordado con su gerente y de conformidad con las políticas de la empresa.

            Parte las políticas de asistencia incluye: tener habilitada la plataforma “Slack” para mantener comunicación con el equipo de trabajo y supervisores. El empleado debe conectarse a la misma desde el inicio de su jornada laboral hasta la finalización de la misma.',0,'J');
            $pdf->SetFont('helvetica','B',12);
            $pdf-> Cell(0,4.5,'• Objetivos de Desempeño:',0,'J');
            $pdf->SetFont('helvetica','',12);
            $pdf->MultiCell(0,4.5,'Se alienta al Supervisor a presentar un plan de trabajo formal para que el empleado que trabaja en casa. El plan identificara y describirá áreas de responsabilidad, tareas diarias y objetivos a la largo plazo como también metas a corto plazo. Los informes de desempeño de los Agentes de Servicio al Cliente deberán publicarse a diario.',0,'J');
            $pdf->SetFont('helvetica','B',12);
            $pdf-> Cell(0,4.5,'• Disponibilidad y Comunicación:',0,'J');
            $pdf->SetFont('helvetica','',12);
            $pdf->MultiCell(0,4.5,'Los empleados que realizan trabajo remoto deben conectarse a la plataforma de comunicación de la empresa todos los días para asegurarse que estén accesibles y puedan participar fácilmente con los demás miembros de su equipo. Los empleados deben estar disponibles por teléfono, correo electrónico, equipos de Microsoft, Slack o cualquier otra credencial de acceso (si el cliente lo especifica) durante su horario de trabajo programado, incluida la visualización por medio de cámara web (solamente si es obligatorio) y también deberá estar presenta para las reuniones de empleados, si es necesario. Cualquier excepción requerirá permiso previo del supervisor directo.',0,'J');
            $pdf->SetFont('helvetica','B',12);
            $pdf-> Cell(0,4.5,'• Función del Supervisor:',0,'J');
            $pdf->SetFont('helvetica','',12);
            $pdf->MultiCell(0,4.5,"El Supervisor se asegurara que el empleado este trabajando de acuerdo con las Política de Trabajo en casa, revisara y aprobara los registros de horas trabajadas de la manera y en el tiempo necesario, deberá monitorear y supervisar la Política de Trabajo en casa de manera regular, y programara reuniones de manera que se incluyan métodos para compartir y comunicar información con los empleados que trabajan en casa.
• Los materiales de la compañía llevados a casa, si los hay, deben mantenerse en el área de trabajo designada en el hogar y nadie más debe tener acceso a ellos.
• La compañía se reserve el derecho de hacer visitas in situ a la ubicación del trabajo remoto (si fuera necesario) con el propósito de determinar que el lugar cumple con las reglas establecidas en esta política y para mantener o recuperar equipo, software, datos o suministros que sean propiedad de la empresa.
• El empleado solo puede usar las cuentas de la computadora y el espacio de trabajo autorizado por la compañía. Se prohíbe el uso de cuentas, identidades, dispositivos y herramientas de seguridad (token) de otra persona, la presentación de información o credenciales falsas o engañosas, y el uso no autorizado de sistemas/ servicios de información.
• Los empleados son responsables de todo el uso de los sistemas de información realizados bajo sus credenciales de usuario (Identidad) y se espera que tomen todas las precauciones, incluidas las medidas de seguridad de contraseñas y protección de archivos para evitar el uso de cuentas o archivos por personas/entidades no autorizadas. Está prohibido compartir sus contraseñas, credenciales y otros token de acceso con otras personas.
• Con el propósito de proteger el acceso a los sistemas de información contra el uso no autorizado e incorrecto, y proteger a aquellos usurarios autorizados de los efectos del uso no autorizado, o uso inadecuado, la empresa tiene el derecho, con o sin previo aviso, de monitorear, registrar, limitar o restringir cualquier cuenta de usuario, acceso y/o uso de la cuenta. La compañía también puede monitorear, registrar, inspeccionar, copiar, eliminar o alterar cualquier información, archivo o recurso del sistema a su criterio y discreción. Adicionalmente, la compañía tiene el derecho de inspeccionar periódicamente los sistemas y tomar cualquier otra acción necesaria para proteger sus sistemas de información, incluido el monitoreo del tecleado realizado por los empleados. La compañía tiene derecho y se reserva el derecho de tener acceso a todos los archivos y correos electrónicos en sus sistemas.
• The company reserves the right to reject from the network or block electronic communications and content deemed not to be in compliance with this or other policies governing use of company’s information systems.
• El equipo suministrado por el empleado, si la organización lo considera apropiado, será mantenido por el empleado. Sin embargo la compañía no acepta responsabilidad por danos o reparaciones a los equipos que sean propiedad de los empleados.
• La compañía se reserva el derecho para rechazar o bloquear del acceso de red, las comunicaciones electrónicas o el contenido que considere no cumple con esta u otras políticas que rigen el uso de los sistemas de información de la compañía.

- Equipo Proporcionado Por el Empleado
• Computadora y su sistema informático (si no lo proporciona la empresa)
• Ancho de banda de Internet
• Cámara Web (no es obligatorio a menos que sea especificado); la Camara web será proporcionada por la empresa cuando su uso sea obligatorio.

- Equipo Proporcionado por la Empresa y Expectativas de su uso y manejo.
• Se proporcionara a los empleados el equipo esencial para la ejecución de sus labores. El listado de dicho equipo será notificado.

- El equipo proporcionado por la empresa, es propiedad de la misma y los empleados tienen el deber de mantenerlo seguro y evitar cualquier uso indebido siguiendo las pautas descritas a continuación:
• Mantener el equipo protegido por medio del uso de una contraseña.
• Guardar el equipo en un espacio seguro y limpio cuando no esté en uso.
• Respetar y seguir toda la información encriptada, estándares de protección y configuraciones.
• Abstenerse de descargar programas (software) sospechosos, no autorizados o ilegales.
• Asegurarse siempre de bloquear el sistema cuando se tome un descanso. Deberá cumplir con los términos de licencia de software y acuerdos de derechos de autor, y con los requisitos y procedimientos de protección contra virus informáticos.

",0,'J');

        $pdf->ln();
        $pdf->SetFont('helvetica','B',12);
        $pdf->MultiCell(0,4.5,'· Derecho de confiscar/inspeccionar los dispositivos informáticos que son propiedad de la empresa:',0,'J');
        $pdf->SetFont('helvetica','',12);
        $pdf->MultiCell(0,4.5,'La compañía se reserva el derecho a confiscar el dispositivo y/o copiar cualquier información almacenada en dicho dispositivo según el criterio y discreción de la empresa en relación a la investigación del mal uso, incumplimiento de esta política, irregularidades de las labores o acciones legales, en cualquier momento, con o sin previo aviso o permiso del usuario o usuarios de la computadora y cualquier otro dispositivo propiedad de la empresa. Adicionalmente los dispositivos de propiedad privada conectados a la red de la empresa están sujetos a inspección por parte de la empresa y el personal autorizado por ella.',0,'J');
        $pdf->ln();
        $pdf->SetFont('helvetica','B',12);
        $pdf->MultiCell(0,4.5,'· Seguridad del Hogar ',0,'J');
        $pdf->SetFont('helvetica','',12);
        $pdf->MultiCell(0,4.5,'Se espera que el espacio de trabajo del empleado en el hogar debe cumplir con los siguientes estándares:

• Los niveles de temperatura, ventilación, iluminación y ruido deben ser adecuados para mantener una oficina en casa.
• El equipo eléctrico debe mantenerse libre de riesgos que puedan causar daños físicos (cables rotos, expuestos o sueltos, accesorios sueltos o flojos, conductores descubiertos, etc.)
El sistema eléctrico debe permitir la conexión a polo tierra de los equipos electrónicos (tomacorrientes triple)
• El espacio de trabajo en casa debe estar libre de obstrucciones para permitir la visibilidad y el movimiento, incluyendo las puertas.
• Los cables de los teléfonos, cables eléctricos y los protectores contra sobretensiones deben estar asegurados debajo de un escritorio o adjunto a una base segura.
• El espacio asignado al trabajo en casa debe estar libre de materiales combustibles, los pisos deben estar en buen estado y las alfombras bien aseguradas.',0,'J');
        $pdf->ln();
        $pdf->SetFont('helvetica','B',12);
        $pdf->MultiCell(0,4.5,'4.',0,'J');
        $pdf->SetFont('helvetica','',12);
        $pdf->MultiCell(0,4.5,'Los empleados que se retiren de la empresa están obligados a devolver todo archivo confidencial y el equipo a la empresa. El equipo deberá ser entregado en la misma condición en la que se asignó. El equipo de Sistemas (I.T.) revisara el equipo y su recuperación, si hay una necesidad de reparación esta será cobrada al empleado por medio de deducción incluida en la liquidación de sus Derechos Laborales.',0,'J');
        $pdf->ln();
        $pdf->SetFont('helvetica','B',12);
        $pdf->MultiCell(0,4.5,'5. Consecuencias de la Violación de la Política',0,'J');
        $pdf->SetFont('helvetica','',12);
        $pdf->MultiCell(0,4.5,'Todo empleado que de un mal uso, uso no autorizado, inapropiado, ilegal de los sistemas de información de la compañía o el incurra en el incumplimiento de esta política está sujeto a la aplicación de acciones disciplinarias por parte de la empresa hasta e incluyendo la terminación de contrato y también procesos legales posteriores a la terminación del contrato de trabajo.',12);
        $pdf->ln();
        $pdf->SetFont('helvetica','B',12);
        $pdf->MultiCell(0,4.5,'6.',0,'J');
        $pdf->SetFont('helvetica','',12);
        $pdf->MultiCell(0,4.5,'La compañía se reserve el derecho de revisar esta política y realizar cambios y modificaciones parciales o totales a su discreción, sujeto a las leyes, normas y reglamentos aplicables.',12);
        $pdf->ln();
        $pdf->SetFont('helvetica','B',12);
        $pdf->MultiCell(0,4.5,'7.',0,'J');
        $pdf->SetFont('helvetica','',12);
        $pdf->MultiCell(0,4.5,'Cualquier desviación en la política deberá ser aprobada por el Director de Operaciones de la empresa.

        He leído y he sido informado sobre el contenido, los requisitos y las expectativas de la Política de Trabajo Remoto En Casa. He recibido una copia de la Política y acepto cumplir con las pautas, expectativas y prohibiciones establecidas en ella como condición de mi empleo.
        
        Nombre del empleado__________________________________________

        Firma del Empleado _______________________________________

        Fecha _______________________
        
        ',12);
        
        
        $pdf->AddPage();
        
        $pdf->SetFont('helvetica','B',12);
        $pdf->Cell(170,20,'Acta de Entrega, Recepción y Autorización de Deducción en caso de Daño o extravío de Equipo',0,0,'C');
        $pdf->ln();
        $pdf->SetFont('helvetica','',12);
        $pdf->MultiCell(0,4.5,"Reconozco que Startek Honduras S.A. de C.V. me hizo entrega del equipo y artículos enumerados a continuación para el uso exclusivo de la ejecución de mis funciones laborales como empleado de la compañía.",12);
        $pdf->ln();

        $x=$pdf->GetX();
        $y=$pdf->GetY();
        $pdf->MultiCell(70,8,'Ubicacion de la Empresa',1,'C');
        $pdf->SetXY($x+70,$y);
        $pdf->MultiCell(70,8,'Tegucigalpa Altia',1,'C');
        $x=$pdf->GetX();
        $y=$pdf->GetY();
        $pdf->MultiCell(70,8,'Cliente',1,'C');
        $pdf->SetXY($x+70,$y);
        $pdf->MultiCell(70,8,''.$dep.'',1,'C');
        $x=$pdf->GetX();
        $y=$pdf->GetY();
        $pdf->MultiCell(70,8,'Nombre del Empleado',1,'C');
        $pdf->SetXY($x+70,$y);
        $pdf->MultiCell(70,8,''.$nom.' '.$ape.'',1,'C');
        $x=$pdf->GetX();
        $y=$pdf->GetY();
        $pdf->MultiCell(70,8,'Numero del Empleado',1,'C');
        $pdf->SetXY($x+70,$y);
        $pdf->MultiCell(70,8,''.$eid.'',1,'C');
        $x=$pdf->GetX();
        $y=$pdf->GetY();
        $pdf->MultiCell(70,8,'Correo Electronico Personal',1,'C');
        $pdf->SetXY($x+70,$y);
        $pdf->MultiCell(70,8,''.$mail.'',1,'C');
        $x=$pdf->GetX();
        $y=$pdf->GetY();
        $pdf->MultiCell(70,8,'Nombre del Supervisor',1,'C');
        $pdf->SetXY($x+70,$y);
        $pdf->MultiCell(70,8,''.$sup.'',1,'C');
        $x=$pdf->GetX();
        $y=$pdf->GetY();
        /////////////////////
        
        $pdf->MultiCell(70,8,'Fecha de Entrega',1,'C');
        $pdf->SetXY($x+70,$y);
        $pdf->MultiCell(70,8,''.strftime("%m/%d/%Y").'',1,'C');
        $x=$pdf->GetX();
        $y=$pdf->GetY();
        $pdf->MultiCell(70,8,'Computadora de Mesa/Portatil',1,'C');
        $pdf->SetXY($x+70,$y);
        $pdf->MultiCell(70,8,'Desktop',1,'C');
        $x=$pdf->GetX();
        $y=$pdf->GetY();
        $pdf->MultiCell(70,8,'Marca de la Computadora',1,'C');
        $pdf->SetXY($x+70,$y);
        $pdf->MultiCell(70,8,''.$pcm.'',1,'C');
        $x=$pdf->GetX();
        $y=$pdf->GetY();
        $pdf->MultiCell(70,8,'No. de Serie de la Computadora',1,'C');
        $pdf->SetXY($x+70,$y);
        $pdf->MultiCell(70,8,''.$pc.'',1,'C');
        $x=$pdf->GetX();
        $y=$pdf->GetY();
        $pdf->MultiCell(70,8,'Valor de la Computadora',1,'C');
        $pdf->SetXY($x+70,$y);
        $pdf->MultiCell(70,8,'375 USD',1,'C');

        ////////
        $x=$pdf->GetX();
        $y=$pdf->GetY();
        $pdf->MultiCell(70,8,'Marca del Monitor 1',1,'C');
        $pdf->SetXY($x+70,$y);
        $pdf->MultiCell(70,8,''.$mmod1.'',1,'C');
        $x=$pdf->GetX();
        $y=$pdf->GetY();
        $pdf->MultiCell(70,8,'No. de Serie Monitor 1',1,'C');
        $pdf->SetXY($x+70,$y);
        $pdf->MultiCell(70,8,''.$m1.'',1,'C');
        $x=$pdf->GetX();
        $y=$pdf->GetY();
        $pdf->MultiCell(70,8,'Valor de Monitor 1',1,'C');
        $pdf->SetXY($x+70,$y);
        $pdf->MultiCell(70,8,'80 USD',1,'C');
        $x=$pdf->GetX();
        $y=$pdf->GetY();
        $pdf->MultiCell(70,8,'Marca del Monitor 2',1,'C');
        $pdf->SetXY($x+70,$y);
        $pdf->MultiCell(70,8,''.$mmod2.'',1,'C');
        $x=$pdf->GetX();
        $y=$pdf->GetY();
        $pdf->MultiCell(70,8,'No. de Serie Monitor 2',1,'C');
        $pdf->SetXY($x+70,$y);
        $pdf->MultiCell(70,8,''.$m2.'',1,'C');
        $x=$pdf->GetX();
        $y=$pdf->GetY();
        $pdf->MultiCell(70,8,'Valor de Monitor 2',1,'C');
        $pdf->SetXY($x+70,$y);
        $pdf->MultiCell(70,8,'80 USD',1,'C');


        

        $pdf->ln();
       
        $pdf->MultiCell(0,4.5,"En referencia al equipo entregado a mi persona, acepto las siguientes condiciones:

        1. El Equipo asignado permanece siendo propiedad de la Empresa en todo momento.
        
        2. Es mi responsabilidad cuidar y mantener la integridad del equipo de trabajo que se me ha asignado. Debo reportar de inmediato a mi supervisor o representante del Dpto. de Sistemas cualquier falla, daño o pérdida del equipo.
        
        3. Debo regresar el equipo completo a la empresa en el momento que la empresa lo requiera o al momento de terminación da mi contrato.
        
        Por este medio tengo conocimiento y acepto que en caso de daño, extravió o falta de devolución del equipo asignado, dentro de los tres (3) días posteriores a la solicitud formal por parte de la empresa y/o la terminación de mí contrato, Startek Honduras S.A. de C.V puede realizar la deducción correspondiente a la cantidad por la cual el equipo está valorado. Dicha deducción podrá realizarse de mi salario, liquidación de mis Derechos Adquiridos, bonificaciones y/o demás pagos realizados por la empresa hasta cubrir los valores asignados, cumpliendo con las ordenanzas de ley aplicables. Adicionalmente comprendo y acepto que seré responsable del pago de cualquier cantidad que no sea cubierta por las deducciones anteriormente descritas. Por último, comprendo que de no pagar los montos adeudados por los motivos antes mencionados, la Empresa se reserva el derecho de cobrar dichos montos por la vía judicial, agregando a este valor el pago de intereses y honorarios de los abogados contratados en este proceso.
        
        Comprendo y acepto que este documento no constituye una garantía de empleo con la empresa. Mi contratación permanece voluntaria lo que significa que está sujeto a la cancelación por mi parte o por parte de la empresa según los estándares establecidos por ley y siguiendo los protocolos autorizados por el Reglamento Interno de la Empresa y la Ley Laboral de Honduras.
        
        Nombre y Firma del Empleado: ___________________________________________
        
        Numero de Identidad:______________________________________________
        
        Firma del Empleado:______________________________________________
        
        Fecha:___________________________________________",12);
            
            
            
            
        }
        $pdf->Output("WaiverES.pdf","I");
    }
    ///////////////
    ///////////////Reporte en Ingles
    ///////////////
    if(isset($_POST['waivereng'])){

        $pdf = new PDF();
        $pdf->SetMargins(15, 25);
        foreach ($resultado as $fila ){
           
            $pc=$fila['PC'];
            $pcm=$fila['PC_Model'];
            $m1=$fila['Monitor1'];
            $mmod1=$fila['Monitor1_Model'];
            $m2=$fila['Monitor2'];
            $mmod2=$fila['Monitor2_Model'];
            $wave=$_SESSION['wave_id'];
            
            $fecha=date("Y-m-d H:i:s");
            $id_usuario=$_SESSION['id_user'];
            $eid=$fila['eeid'];
            $nom= $fila['nombres'];
            $ape= $fila['apellidos'];
            $ced= $fila['cedula_id'];
            $dir= $fila['direccion'];
            $dep= $fila['departamento'];
            $sup= $fila['supervisor'];
            $mail= $fila['personal_mail'];
            $pdf->AddPage();

            $pdf->SetFont('Helvetica','B',12);
            $pdf->Cell(170,20,'Remote Work Policy',0,0,'C');
            $pdf->ln();
            $pdf->SetFont('helvetica','B',12);
            $pdf->MultiCell(0,4.5,'1. Objective ',0,'J');
            $pdf->SetFont('helvetica','',12);
            $pdf->MultiCell(0,4.5,'The purpose of this policy is to set guidelines for employees while working from home.',0,'J');
            $pdf->SetFont('helvetica','B',12);
            $pdf->ln();
            $pdf->MultiCell(0,4.5,'2. Scope',0,'J');
            $pdf->SetFont('helvetica','',12);
            $pdf->MultiCell(0,4.5,'This policy applies to employees who are permitted to work in a home office. The consideration for remote work requires a written recommendation from the direct supervisor and shall be finalized by the Head of Department and HR.',0,'J');
            $pdf->SetFont('helvetica','B',12);
            $pdf->ln();
            $pdf->MultiCell(0,4.5,'3. Policy Guidelines',0,'J');
            $pdf->MultiCell(0,4.5,'Compliance with Policies and Procedures:',0,'J');
            $pdf->SetFont('helvetica','',12);
            $pdf->MultiCell(0,4.5,"Employees remain obligated to comply with all company rules, practices and instructions as outlined in the HR Policy manual.
 Workspace Expectations
            
Remote work shall only be performed from the employee’s primary residence and employees are required to:  
• Designate a workspace that is quiet and distraction free.
• Workspace that is dedicated for placement and installation of equipment to be used while teleworking.
• Maintain this workspace in a safe condition, free from hazards and other dangers to the employee and equipment.
• The company shall not be responsible for costs associated with the setup of the employee's home office, such as remodeling, furniture or lighting, nor for repairs or modifications to the home office space.
• The employee is authorized to mobilize the equipment provided by the company to the City of ___________________ specifically in _____________________ with the understanding that such mobilization will be borne by the employee and it will be responsible to return the equipment when the company considers it appropriate.
            
• Employee is expected to submit three photos of the home workspace to management prior to implementation wherever feasible.",0,'J');
            $pdf->SetFont('helvetica','B',12);
            $pdf-> Cell(0,4.5,'• Schedule:',0,'J');
            $pdf->SetFont('helvetica','',12);
            $pdf->MultiCell(0,4.5,'A general schedule must be communicated to the employee and agreed to by the direct supervisor. Deviations from that schedule if any, should be immediately communicated to the supervisor.',0,'J');
            $pdf->SetFont('helvetica','B',12);
            $pdf-> Cell(0,4.5,'• Attendance:',0,'J');
            $pdf->SetFont('helvetica','',12);
            $pdf->MultiCell(0,4.5,'The employee must adhere to attendance and break schedules as agreed upon with their manager and in compliance with the company policy.',0,'J');
            $pdf->SetFont('helvetica','B',12);
            $pdf-> Cell(0,4.5,'• Performance objectives:',0,'J');
            $pdf->SetFont('helvetica','',12);
           
            $pdf->MultiCell(0,4.5,'The Supervisor is encouraged to submit a formal work plan for the employee working remotely. The plan will identify and outline areas of responsibilities, daily tasks and measurable long-term objectives and short terms goals. For agent staff, the performance reports must be published on a daily basis.',0,'J');
            $pdf->SetFont('helvetica','B',12);
            $pdf-> Cell(0,4.5,'• Availability and Communication:',0,'J');
            $pdf->SetFont('helvetica','',12);
            $pdf->MultiCell(0,4.5,'Employees undertaking remote work must be logged into the company’s communication platform each day to ensure that they are accessible and can easily participate with their team members. Employees must be available by phone, email, Microsoft Teams, Slack or any other login id (if client specified) during scheduled shift hours including web cam viewing (if mandated only) and also be present for staff meetings, if required. Any exceptions shall require prior permission from the direct supervisor.',0,'J');
            $pdf->SetFont('helvetica','B',12);
            $pdf-> Cell(0,4.5,'• Role of supervisor:',0,'J');
            $pdf->SetFont('helvetica','',12);
            $pdf->MultiCell(0,4.5,"The Supervisor shall ensure that the employee is working in accordance with the Remote Work Policy, review and sign-off on records of hours worked (timesheets) as and when required, monitor and review the Remote Work Policy.
Company materials taken home, if any, should be kept in the designated work area at home and not be made accessible to others.
• The company reserves the right to make on-site visits to the remote work location (only if need be) for purposes of determining that the site complies with the rules set forth herein and to maintain, or retrieve company-owned equipment, software, data or supplies.
• Employee may use only the computer accounts and workspace authorized by the company. Use of another person's account, identity, security devices/tokens, or presentment of false or misleading information or credentials, or unauthorized use of information systems/services is prohibited.
• Employees are responsible for all use of information systems conducted under their user ID(s) and are expected to take all precautions including password security and file protection measures to prevent use of their accounts and files by unauthorized persons/entities. Sharing of passwords or other access tokens with others is prohibited.
• To protect access to information systems against unauthorized or improper use, and to protect authorized users from the effects of unauthorized or improper usage, the company has the right with or without notice, to monitor, record, limit or restrict any user account, access and/or usage of account. The company may also monitor, record, inspect, copy, remove or otherwise alter any data, file, or system resources in its sole discretion. The company further has the right to periodically inspect systems and take any other actions necessary to protect its information systems including monitoring employee keystrokes. The company also has access rights to all files and electronic mail on its terminal systems. Anyone using these systems expressly consents to such oversight.
• Equipment supplied by the employee, if deemed appropriate by the organization, will be maintained by the employee. However, the company accepts no responsibility for damage or repairs to employee-owned equipment.
• The company reserves the right to reject from the network or block electronic communications and content deemed not to be in compliance with this or other policies governing use of company’s information systems.

- Equipment to be Provided by Employee

• Computer System (if not provided for by the company)

• Internet Bandwidth

• Web Camera (not a mandatory requirement unless specified); Web Camera will be provided by the company when mandatory use is expected.

- Employer Provided Equipment and Maintenance / Usage Expectations

• Employees will be provided equipment that is essential to their job duties and the list of such equipment shall be notified.

- Equipment provided by the company is company property and employees must keep it safe and avoid any misuse by adhering to the below guidelines:
• Keep equipment password protected.
• Store equipment in a safe and clean space when not in use.
• Follow all data encryption, protection standards and settings.
• Refrain from downloading suspicious, unauthorized or illegal software.
• Make sure to always lock the system when taking a break.
• Comply with the terms of computer software license and copyright agreements, computer virus and protection requirements and procedures.
",0,'J');

        $pdf->ln();
        $pdf->SetFont('helvetica','B',12);
        $pdf->MultiCell(0,4.5,'· Right to seize/inspect company-owned Computing Devices: ',0,'J');
        $pdf->SetFont('helvetica','',12);
        $pdf->MultiCell(0,4.5,'The company reserves the right at any time, with or without prior notice or permission from the user or users of a computer or other company-owned computing device, to seize such device and/or copy, any and all information from the data storage mechanisms of such device as may be required in the sole discretion of the company in connection with investigations of possible wrongdoing or legal action. In addition to the foregoing, privately owned devices connected to the company network are also subject to inspection by authorized company personnel.',0,'J');
        $pdf->ln();
        $pdf->SetFont('helvetica','B',12);
        $pdf->MultiCell(0,4.5,'· Home Safety ',0,'J');
        $pdf->SetFont('helvetica','',12);
        $pdf->MultiCell(0,4.5,'It is expected that the employee’s workspace at home must meet the following standards:

• Temperature, ventilation, lighting, and noise levels are adequate for maintaining a home office.        
• Electrical equipment is free of recognized hazards that would cause physical harm (frayed, exposed, or loose wires; loose fixtures, bare conductors, etc.).   
• Electrical system allows for grounding of electrical equipment (three-prong receptacles).
• The home workspace (including doorways) is free of obstructions to permit visibility and movement.
• Phones lines, electrical cords, and surge protectors are secured under a desk or alongside a baseboard.
• The home workspace should be free of combustibles, floors are in good repair, and carpets are well secured.',0,'J');
        $pdf->ln();
        $pdf->SetFont('helvetica','B',12);
        $pdf->MultiCell(0,4.5,'4.',0,'J');
        $pdf->SetFont('helvetica','',12);
        $pdf->MultiCell(0,4.5,'Employees who leave the company are obligated to return any confidential files and equipment back to the company. The equipment shall be in the same condition as at the time of issuance. The I.T. team shall inspect the equipment and recovery, if any for repairmen, shall be charged to the employee at the time of clearance and full and final settlement.',0,'J');
        $pdf->ln();
        $pdf->SetFont('helvetica','B',12);
        $pdf->MultiCell(0,4.5,'5. Consequences of Policy Violation',0,'J');
        $pdf->SetFont('helvetica','',12);
        $pdf->MultiCell(0,4.5,'Any unauthorized, inappropriate, illegal or other use of company’s information systems or failure to comply with this policy shall subject the violator to disciplinary action by the company, including, but not limited to, termination of employment and criminal prosecution.',12);
        $pdf->ln();
        $pdf->SetFont('helvetica','B',12);
        $pdf->MultiCell(0,4.5,'6.',0,'J');
        $pdf->SetFont('helvetica','',12);
        $pdf->MultiCell(0,4.5,'The company reserves the right to revise all or any portion of this policy at any time and from time to time in its sole discretion, subject to applicable laws, rules and regulations.',12);
        $pdf->ln();
        $pdf->SetFont('helvetica','B',12);
        $pdf->MultiCell(0,4.5,'7.',0,'J');
        $pdf->SetFont('helvetica','',12);
        $pdf->MultiCell(0,4.5,'Any deviation in the policy shall need approval of the Global CPO.

I have read and been informed about the content, requirements, and expectations of the Remote Work Policy. I have received a copy of the policy and agree to abide the policy guidelines as a condition of my employment.
        
Employee Name __________________________________________

Employee Signature _______________________________________

Date _______________________
        
        ',12);
        
        
        $pdf->AddPage();
        
        $pdf->SetFont('helvetica','B',12);
        $pdf->Cell(170,20,'Company Equipment Return and Cost Reimbursement Agreement',0,0,'C');
        $pdf->ln();
        $pdf->SetFont('helvetica','',12);
        $pdf->MultiCell(0,4.5,"I acknowledge that Startek (the Company) issued to me equipment, including the below-listed items (collectively, the 'Equipment') for my exclusive use for work-related functions. In consideration of the issuance of the Equipment to me, I agree as follows: (1) The Equipment remains the Company's property at all times; (2) I will maintain the Equipment to the best of my ability and will immediately report any/all malfunctions, loss, damage, etc. to my supervisor and/or relevant Information Technology (IT) representative; and (3) I will return the Equipment to the Company on the earlier of (a) the Company's request or (b) the date I separate from the Company's employment.",12);
        $pdf->ln();

        $x=$pdf->GetX();
        $y=$pdf->GetY();
        $pdf->MultiCell(70,8,'Campus Location',1,'C');
        $pdf->SetXY($x+70,$y);
        $pdf->MultiCell(70,8,'Tegucigalpa Altia',1,'C');
        $x=$pdf->GetX();
        $y=$pdf->GetY();
        $pdf->MultiCell(70,8,'Client',1,'C');
        $pdf->SetXY($x+70,$y);
        $pdf->MultiCell(70,8,''.$dep.'',1,'C');
        $x=$pdf->GetX();
        $y=$pdf->GetY();
        $pdf->MultiCell(70,8,'Agent Name',1,'C');
        $pdf->SetXY($x+70,$y);
        $pdf->MultiCell(70,8,''.$nom.' '.$ape.'',1,'C');
        $x=$pdf->GetX();
        $y=$pdf->GetY();
        $pdf->MultiCell(70,8,'Lawson ID',1,'C');
        $pdf->SetXY($x+70,$y);
        $pdf->MultiCell(70,8,''.$eid.'',1,'C');
        $x=$pdf->GetX();
        $y=$pdf->GetY();
        $pdf->MultiCell(70,8,'Personal Mail',1,'C');
        $pdf->SetXY($x+70,$y);
        $pdf->MultiCell(70,8,''.$mail.'',1,'C');
        $x=$pdf->GetX();
        $y=$pdf->GetY();
        $pdf->MultiCell(70,8,'Supervisor',1,'C');
        $pdf->SetXY($x+70,$y);
        $pdf->MultiCell(70,8,''.$sup.'',1,'C');
        $x=$pdf->GetX();
        $y=$pdf->GetY();
        /////////////////////
        
        $pdf->MultiCell(70,8,'Date Issued',1,'C');
        $pdf->SetXY($x+70,$y);
        $pdf->MultiCell(70,8,''.strftime("%m/%d/%Y").'',1,'C');
        $x=$pdf->GetX();
        $y=$pdf->GetY();
        $pdf->MultiCell(70,8,'Laptop/Desktop',1,'C');
        $pdf->SetXY($x+70,$y);
        $pdf->MultiCell(70,8,'Desktop',1,'C');
        $x=$pdf->GetX();
        $y=$pdf->GetY();
        $pdf->MultiCell(70,8,'Laptop/Desktop Make',1,'C');
        $pdf->SetXY($x+70,$y);
        $pdf->MultiCell(70,8,''.$pcm.'',1,'C');
        $x=$pdf->GetX();
        $y=$pdf->GetY();
        $pdf->MultiCell(70,8,'Laptop/Desktop Serial',1,'C');
        $pdf->SetXY($x+70,$y);
        $pdf->MultiCell(70,8,''.$pc.'',1,'C');

        $x=$pdf->GetX();
        $y=$pdf->GetY();
        $pdf->MultiCell(70,8,'Computer Value',1,'C');
        $pdf->SetXY($x+70,$y);
        $pdf->MultiCell(70,8,'375 USD',1,'C');
        
       
        ////////
        $x=$pdf->GetX();
        $y=$pdf->GetY();
        $pdf->MultiCell(70,8,'Monitor 1 Make',1,'C');
        $pdf->SetXY($x+70,$y);
        $pdf->MultiCell(70,8,''.$mmod1.'',1,'C');
        $x=$pdf->GetX();
        $y=$pdf->GetY();
        $pdf->MultiCell(70,8,'Monitor 1 Serial No',1,'C');
        $pdf->SetXY($x+70,$y);
        $pdf->MultiCell(70,8,''.$m1.'',1,'C');
        $x=$pdf->GetX();
        $y=$pdf->GetY();
        $pdf->MultiCell(70,8,'Monitor 1 Value',1,'C');
        $pdf->SetXY($x+70,$y);
        $pdf->MultiCell(70,8,'80 USD',1,'C');
        $x=$pdf->GetX();
        $y=$pdf->GetY();
        $pdf->MultiCell(70,8,'Monitor 2 Make',1,'C');
        $pdf->SetXY($x+70,$y);
        $pdf->MultiCell(70,8,''.$mmod2.'',1,'C');
        $x=$pdf->GetX();
        $y=$pdf->GetY();
        $pdf->MultiCell(70,8,'Monitor 2 Serial No',1,'C');
        $pdf->SetXY($x+70,$y);
        $pdf->MultiCell(70,8,''.$m2.'',1,'C');
        $x=$pdf->GetX();
        $y=$pdf->GetY();
        $pdf->MultiCell(70,8,'Monitor 2 Value',1,'C');
        $pdf->SetXY($x+70,$y);
        $pdf->MultiCell(70,8,'80 USD',1,'C');


        

        $pdf->ln();
       
        $pdf->MultiCell(0,4.5,"By signing below, I understand and agree that, in the event the Equipment becomes lost or damaged, or I fail to return the Equipment to the Company, within three (3) business days of the occurrence of number (3) above, the Company may deduct from my compensation (including, without limitation, paychecks (including final paycheck), bonuses, expense reimbursement payments, commissions, wages, salary, profit share, and any other monies to be paid to me by the Company), to the extent permitted by law, the amount necessary to recover the value of the Equipment. I also understand and agree that I will be responsible for repayment of any amount that remains unpaid after any such deductions. I further agree that if I fail to repay the amount owed, the Company may move to collect such amount, plus interest and reasonable and necessary attorney’s fees, in a court of competent jurisdiction.

        I further understand and agree that nothing in this Agreement constitutes a guarantee of ongoing employment and my employment with the Company remains at-will, meaning that it is subject to termination by me or the Company, with or without cause, with or without notice, at any time, unless I have a separate employment agreement with the Company that states otherwise.
        
        I also understand that I am working under Honduras Legislation, according to the Honduras Job Code and Startek’s Honduras Internal Job Code, therefore, the regulations and disciplinary matrix is still applicable under this modality.
        
        ________________________________________
        
        Employee's Signature
        
        ________________________________________
        
        Employee's Name (Print)
        
        ____________________
        
        Date",12);

       
    }
        $pdf->Output("WaiverEN.pdf","I");
    }
    //Agrega equipos y realiza la transaccion simultaneamente.
    if(isset($_POST['add']) && $wave=$_SESSION['wave_id']){
       
        foreach($resultado as $fila ){

            $pc=$fila['PC'];
            $pcm=$fila['PC_Model'];
            $m1=$fila['Monitor1'];
            $mmod1=$fila['Monitor1_Model'];
            $m2=$fila['Monitor2'];
            $mmod2=$fila['Monitor2_Model'];
            $wave=$_SESSION['wave_id'];
            $fecha=date("Y-m-d H:i:s");
            $id_usuario=$_SESSION['id_user'];
            $eid=$fila['eeid'];
            
            /* Sql queries para añadir registros a tabla de equipo*/
                try{

                $database = new Database(); 
                
                $query1="INSERT IGNORE INTO equipo (`no_serial`, `id_atributo`, `id_estado`, `id_ubicacion`) VALUES (:no_serial, (SELECT id
                FROM atributo
                where modelo in ('$pcm')
                group by id
                having count(id) = 1), 3, 1)"; 
                $stmt1 = $database->conectar()->prepare($query1);
                $stmt1->bindParam(':no_serial', $pc);
               
                $stmt1->execute() ;
                    
            
            }
            catch(PDOException $e){
                 
                        echo $e;
             }   
            try{

                $database = new Database(); 
                
                $query2="INSERT IGNORE INTO equipo (`no_serial`, `id_atributo`, `id_estado`, `id_ubicacion`)  VALUES (:monitor_s1, (SELECT id
                FROM atributo
                where modelo in ('$mmod1')
                group by id
                having count(id) = 1), 3, 1)"; 
                $stmt2 = $database->conectar()->prepare($query2);
                $stmt2->bindParam(':monitor_s1', $m1);
                $stmt2->execute();
            }
            catch(Exception $e){
              echo "Monitor Model not found please check ";
            }   
            
            try{

                $database = new Database(); 
                $query3="INSERT IGNORE INTO equipo (`no_serial`, `id_atributo`, `id_estado`, `id_ubicacion`)  VALUES (:monitor_s2, (SELECT id
                FROM atributo
                where modelo in ('$mmod2')
                group by id
                having count(id) = 1), 3, 1)"; 
                $stmt3 = $database->conectar()->prepare($query3);
                $stmt3->bindParam(':monitor_s2', $m2);
                
                $stmt3->execute();
                    
            }
            catch(Exception $e){
                echo "Monitor Model not found please check ";
            }   
            
             /* Sql queries para añadir registros a tabla de movimientos*/
            try{
                $database = new Database();
                $query4 = 'INSERT IGNORE INTO `movimiento` (`descripcion`, `fecha`, `razon`, `id_usuario`, `eeid`, `no_serial`) VALUES (:descripcion, :fecha, "Assignment", :id_usuario, :eeid, :no_serial)';
                $stmt4 = $database->conectar()->prepare($query4);
                $stmt4->bindParam(':descripcion', $wave);
                $stmt4->bindParam(':fecha', $fecha);
                $stmt4->bindParam(':id_usuario', $id_usuario);
                $stmt4->bindParam(':eeid', $eid);
                $stmt4->bindParam(':no_serial', $pc);
                $stmt4->execute();

            }catch(PDOException $e) { 
              
                 echo $e;
            }
            
            
            
            try{
                $database = new Database();
                $query5 = 'INSERT IGNORE INTO `movimiento` (`descripcion`, `fecha`, `razon`, `id_usuario`, `eeid`, `no_serial`) VALUES (:descripcion, :fecha, "Assignment", :id_usuario, :eeid, :no_serial)';
                $stmt5 = $database->conectar()->prepare($query5);
                $stmt5->bindParam(':descripcion', $wave);
                $stmt5->bindParam(':fecha', $fecha);
                $stmt5->bindParam(':id_usuario', $id_usuario);
                $stmt5->bindParam(':eeid', $eid);
                $stmt5->bindParam(':no_serial', $m1);
                $stmt5->execute();

            }catch(PDOException $e) { 
               
                 
            }

            
            try{
                $database = new Database();
                $query6 = 'INSERT IGNORE INTO `movimiento` (`descripcion`, `fecha`, `razon`, `id_usuario`, `eeid`, `no_serial`) VALUES (:descripcion, :fecha, "Assignment", :id_usuario, :eeid, :no_serial)';
                $stmt6 = $database->conectar()->prepare($query6);
                $stmt6->bindParam(':descripcion', $wave);
                $stmt6->bindParam(':fecha', $fecha);
                $stmt6->bindParam(':id_usuario', $id_usuario);
                $stmt6->bindParam(':eeid', $eid);
                $stmt6->bindParam(':no_serial', $m2);
                $stmt6->execute();

            }catch(PDOException $e) { 
                
                 
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
                        <a href="./deployments.php" class="btn btn-success mx-1 d-flex justify-content-center" data-bs-toggle="tooltip" data-bs-placement="top" title="Back"><span class="material-icons">arrow_back</span></a> 
                </div>
            </div>

            <div class="col-sm-6">
                <a href="#deleteDeploymentModal" class="btn btn-danger" data-toggle="modal"> <span>Delete</span></a>  
                <a href="#proccessModal" class="btn btn-success" data-toggle="modal"> <span>Process Wave</span></a>      
                <form method="post" target="_blank" class="btn-group pull-right"><input type="submit" name="salida" class="btn btn-success" value="Generate Pass"></form>
                <form method="post" target="_blank" class="btn-group pull-right"><input type="submit" name="waivereng" class="btn btn-success" value="Generate Waiver (EN)"></form>
                <form method="post" target="_blank" class="btn-group pull-right"><input type="submit" name="waiveres" class="btn btn-success" value="Generate Waiver (ES)"></form>
            </div>
            
            <div class="table-responsive table-hover shadow bg-body rounded">
            <div class="btn-group pull-right">
    </div>
    <form action="importdeployment.php" method="post" enctype="multipart/form-data">
            <input type="file" name="file" />
            <input type="submit" class="btn btn-primary" name="importDeploy" value="IMPORT">
    </form>
    <div class="form-left">
        <table id="data_table" class="table align-middle mb-0 table-sm">
                    <thead class="text-dark" style="background-color:#F3CC4F;">
                        <tr>
                            
                            <th scope="col">No.</th>
                            <th scope="col">Wave</th>
                            <th scope="col">PC</th>
                            <th scope="col">PC Model</th>
                            <th scope="col">MONITOR 1</th>
                            <th scope="col">MONITOR 1 Model</th>
                            <th scope="col">MONITOR 2</th>
                            <th scope="col">MONITOR 2 Model</th>
                            <th scope="col">EEID</th>
                            
                        </tr>
                    </thead>
                    <tbody>
                    <?php $i=1; foreach ($resultado as $fila ) {?>
                        <tr>
                            <td> 
                                <?php 
                                        echo $i; 
                                        $i++; 
                                    ?>
                            </td>
                            <td><?php echo escape($fila['wave']); ?></td>
                            <td><?php echo escape($fila['PC']); ?></td>
                            <td><?php echo escape($fila['PC_Model']); ?></td>
                            <td><?php echo escape($fila['Monitor1']); ?></td>
                            <td><?php echo escape($fila['Monitor1_Model']); ?></td>
                            <td><?php echo escape($fila['Monitor2']); ?></td>
                            <td><?php echo escape($fila['Monitor2_Model']); ?></td>
                            <td><?php echo escape($fila['eeid']); ?></td>
                        </tr>
                    <?php } ?>
                    </tbody>

        </table>                    
       
     </div>

    <div id="deleteDeploymentModal" class="modal fade">
  <div class="modal-dialog">
   <div class="modal-content">
    <form method="post">
     <div class="modal-header">      
      <h4 class="modal-title">Delete Deployment</h4>
      <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
     </div>
     <div class="modal-body">     
      <p>Are you sure you want to remove this wave?</p>
      <p class="text-warning"><small>This action cannot be undone.</small></p>
     </div>
     <div class="modal-footer">
      <input type="button" class="btn btn-default" data-dismiss="modal" value="Cancel">
      <input type="submit" class="btn btn-danger" name="delete" value="Delete">
     </div>
    </form>
   </div>
  </div>
 </div>
 
 <div id="proccessModal" class="modal fade">
  <div class="modal-dialog">
   <div class="modal-content">
    <form method="post">
     <div class="modal-header">      
      <h4 class="modal-title">Process Wave</h4>
      <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
     </div>
     <div class="modal-body">     
      <p>The following wave will be processed</p>
      <p class="text-warning"><small>This action cannot be undone.</small></p>
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