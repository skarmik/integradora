<?php
$servidor='localhost';
$usuario='root';
$contrasena='';
$base_de_datos='boletos';

$conection = new mysqli($servidor, $usuario, $contrasena, $base_de_datos);

if($conection -> connect_error){
    die("conexion fallida: ". $conn->connect_error);
}

?>