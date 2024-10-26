<?php
include "../Conexion/conexion.php";
session_start();
header("location:indexlogin.php");
session_destroy();
?>