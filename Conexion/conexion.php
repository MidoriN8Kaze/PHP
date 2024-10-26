<?php 
$host = "Localhost";
$user = "root";
$password ="";
$database ="productosphpcrud"; 

$conectado = new mysqli($host,$user,$password,$database);

try {
    //code...
} catch (\Throwable $th) {
    die("Conexión fallida: " . $conectado->connect_errno);
}
?>