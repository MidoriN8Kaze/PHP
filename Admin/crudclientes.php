<?php
session_start();
include "../Conexion/conexion.php";
if (!isset($_SESSION['rol']) || $_SESSION['rol'] !== 'Admin') {
    header("Location: ../Login/accesodenegado.php");
    exit();
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {

    $name = $_POST['name'];
    $dni = $_POST['dni'];
    $telefono = $_POST['telefono'];

    $sql = "INSERT INTO `clientes` (`nombre`, `dni`, `telefono`) VALUES ('$name', '$dni', '$telefono');";

    if ($conectado->query($sql) === TRUE) {
        $mensaje = "<div class='alert alert-success'>Cliente creado exitosamente</div>";
        header('Location: crudclientes.php');
        exit();
    } else {
        echo "Error: " . $sql . "<br>" . $conectado->error;
    }
}

$vista = "SELECT * FROM `clientes` WHERE `estado` != 0";
$result = $conectado->query($vista);

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

    <h1 class="text-center">Añadir Cliente</h1>
    <a href="admin.php" class="btn btn-warning mb-3">Volver</a>

    <div class="row">
        <div class="col-md-5">
            <form action="" method="post">
                <div class="form-group">
                    <label for="name">Nombre:</label>
                    <input type="text" class="form-control" id="name" name="name" required>
                </div>
                <div class="form-group">
                    <label for="dni">DNI:</label>
                    <input type="text" class="form-control" id="dni" name="dni" required>
                </div>
                <div class="form-group">
                    <label for="telefono">Teléfono:</label>
                    <input type="text" class="form-control" id="telefono" name="telefono" required>
                </div>
                <div class="form-group">
                    <label for="age">Edad:</label>
                    <input type="number" class="form-control" id="age" name="age" required>
                </div>
                <button type="submit" class="btn btn-primary">Añadir Cliente</button>
            </form>
        </div>

        <div class="col-md-7">
            <table class="table table-bordered table-striped" id="tableclientes">
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
                                <a href="delete copy.php" class="btn btn-danger btn-sm" onclick="return confirm('¿Está seguro?')">Borrar</a>
                            </td>
                        </tr>
                    <?php endwhile ?>
                </tbody>
            </table>
        </div>
    </div>
    <script>
        $(document).ready(function() {
            $('#tableclientes').DataTable();
        });
    </script>

</body>

</html>