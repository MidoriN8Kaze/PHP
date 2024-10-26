<?php
session_start();
include "../Conexion/conexion.php";
if (!isset($_SESSION['rol']) || $_SESSION['rol'] !== 'Admin') {
    header("Location: ../Login/accesodenegado.php");
    exit();
}

$sql = "SELECT productos.nombre AS producto,
 clientes.nombre AS proveedor, ventas.cantidad AS cantidad,
  ventas.valorunitario AS unidad, ventas.valorventa AS total,
   ventas.fecha AS fecha FROM ventas JOIN productos
    ON ventas.idproductos = productos.id 
    JOIN clientes ON ventas.idclientes = clientes.id WHERE ventas.estado = 1;";

$result = $conectado->query($sql);


?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Añadir Clientes</title>
    <link href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">

    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.datatables.net/1.11.5/js/jquery.dataTables.min.js"></script>
    <link rel="stylesheet" href="https://cdn.datatables.net/1.11.5/css/jquery.dataTables.min.css">

    <style>
        body {
            background-color: #f8f9fa;
        }

        .container {
            margin-top: 20px;
        }

        h1 {
            margin-bottom: 20px;
        }
    </style>
</head>

<body class="container">

    <h1 class="text-center">Compras realizadas</h1>
    <a href="admin.php" class="btn btn-warning mb-3">Volver</a>

    <div class="container-mt5">
        <table class="table table-bordered table-striped" id="table">
            <thead class="thead-dark">
                <tr>
                    <th>Productos</th>
                    <th>Proveedor</th>
                    <th>Cantidad</th>
                    <th>Valor unidad</th>
                    <th>Valor total</th>
                    <th>Fecha</th>
                </tr>
            </thead>
            <tbody>
                <?php while ($row = $result->fetch_assoc()) : ?>
                    <tr>
                        <td><?php echo $row["producto"]; ?></td>
                        <td><?php echo $row["proveedor"]; ?></td>
                        <td><?php echo $row["cantidad"]; ?></td>
                        <td><?php echo $row["unidad"]; ?></td>
                        <td><?php echo $row["total"]; ?></td>
                        <td><?php echo $row["fecha"]; ?></td>
                    </tr>
                <?php endwhile ?>
            </tbody>
        </table>
    </div>
    </div>
    <script>
        $(document).ready(function() {
            $('#table').DataTable();
        });
    </script>

</body>

</html>