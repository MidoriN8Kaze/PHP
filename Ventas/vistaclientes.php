<?php
session_start();
include "../Conexion/conexion.php";
if (!isset($_SESSION['rol']) || $_SESSION['rol'] !== 'Cliente') {
    header("Location: ../Login/accesodenegado.php");
    exit();
}

$vista = "SELECT * FROM `clientes` WHERE `estado` != 0";
$result = $conectado->query($vista);

?>
<!DOCTYPE html>
<html lang="es">

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

    <h1 class="text-center">Seleccionar Cliente</h1>
    <a href="../Login/logout.php" class="btn btn-danger btn-lg">Salir</a>
    <div class="container mt-4">
        <table class="table table-bordered" id="tableclientes">
            <thead class="thead-dark">
                <tr>
                    <th>ID</th>
                    <th>Nombre</th>
                    <th>DNI</th>
                    <th>Teléfono</th>
                    <th>Acciones</th>
                </tr>
            </thead>
            <tbody>
                <?php while ($row = $result->fetch_assoc()) : ?>
                    <tr>
                        <td><?php echo $row["id"]; ?></td>
                        <td><?php echo $row["nombre"]; ?></td>
                        <td><?php echo $row["dni"]; ?></td>
                        <td><?php echo $row["telefono"]; ?></td>
                        <td>
                            <a href="crudventas.php?idcliente=<?php echo $row['id']; ?>" 
                            class="btn btn-success btn-sm" onclick="return confirm('¿Está seguro de seleccionar este cliente?')">Seleccionar</a>
                        </td>
                    </tr>
                <?php endwhile; ?>
            </tbody>
        </table>
    </div>

    <script>
        $(document).ready(function() {
            $('#tableclientes').DataTable();
        });
    </script>

</body>

</html>
