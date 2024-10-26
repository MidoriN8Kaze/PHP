<?php
session_start();

if (!isset($_SESSION['rol']) || $_SESSION['rol'] !== 'Admin') {
    header("Location: ../Login/accesodenegado.php");
    exit();
}


?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin</title>
    <link href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">

    <style>
        .col-md-4 {
            display: flex;
            justify-content: center;
            flex-direction: column;
        }

        .row {
            justify-content: center;
            margin-top: 10px;
        }
        .rowadmin {
            justify-content: center;
            margin-top: 10px;
            border: inset 10px;
            background-color: aliceblue;
        }

        a {
            margin-top: 20px;

        }
    </style>

</head>

<body>

    <div class="container mt-5">
        <div class="rowadmin">
            <h1 class="text-center">Panel de Administración</h1>
        </div>

        <a href="../Login/logout.php" class="btn btn-danger btn-lg">Salir</a>
        <br>
        <div class="row">
            <div class="col-md-4 text-center">
                <a href="crudclientes.php" class="btn btn-success btn-lg">Agregar un nuevo proveedor</a>
                <br>
            </div>
            <div class="col-md-4 text-center">
                <a href="crudclientes.php" class="btn btn-success btn-lg">Agregar un nuevo cliente</a>
                <br>
            </div>

        </div>
        <div class="row">
            <div class="col-md-4 text-center">
                <a href="vistacompras.php" class="btn btn-success btn-lg">Ver compras realizadas</a>
                <br>
            </div>
            <div class="col-md-4 text-center">
                <a href="vistaventas.php" class="btn btn-success btn-lg">Ver ventas realizadas</a>
                <br>
            </div>
        </div>
    </div>

    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.5.2/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>