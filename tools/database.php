<?php
 date_default_timezone_set('America/El_Salvador');
class Database{
    private $host;
    private $db;
    private $user;
    private $password;

    public function __construct(){
        $this->host = '127.0.0.1';
        $this->db = 'ims';
        $this->user = 'root';
        $this->password = '';
    }
    function conectar(){
        try{
            $conexion = "mysql:host=" . $this->host . ";dbname=" . $this->db;
            $opciones = [
                PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
                PDO::ATTR_EMULATE_PREPARES   => false,
            ];
            $pdo = new PDO($conexion, $this->user, $this->password, $opciones);
            return $pdo;
        }catch(PDOException $e){
            print_r('Error connection: ' . $e->getMessage());
        }
    }
}
?>