<?php
session_start();
include '../Conexion/conexion.php';
$id = $_GET['id'];

$sql = "UPDATE clientes SET estado = 0 WHERE id = $id";

if ($conectado->query($sql) === TRUE) {
    header('Location: crudclientes.php');
} else {
    echo "Error al eliminar el usuario: " . $conectado->error;
}

?>
