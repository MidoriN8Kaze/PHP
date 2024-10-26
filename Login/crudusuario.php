<?php
include '../Conexion/conexion.php';
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $name = $_POST['name'];
    $email = $_POST['email'];
    $password = $_POST['pass'];
    $idrol = $_POST['idrol'];

    $sql = "INSERT INTO `usuarios` (`id`, `idrol`, `nombre`, `email`, `pass`, `estado`) VALUES (NULL, '$idrol', '$name', '$email', '$password', '1');";

    if ($conectado->query($sql) == TRUE) {
        echo "<div style='color: green;'>Usuario creado exitosamente.</div>";
        header('Location: indexlogin.php');
    } else {
        echo "Error: " . $sql . "<br>" . $conectado->error;
    }
}

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Añadir Usuario</title>
    <link href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
</head>

<body class="container mt-5">
    <h1>Añadir un Usuario</h1>
    <a href="indexlogin.php" class="btn btn-warning">Volver</a>
    <form action="" method="post">

        <div class="form-group">
            <label for="idrol">Rol</label>
            <select class="form-control" id="idrol" name="idrol" required>
                <option value="">Seleccione un rol</option>
                <?php
                $select = "SELECT id, nombre FROM roles";
                $result = $conectado->query($select);

                // Verifica si hay resultados
                if ($result->num_rows > 0) {
                    // Salida de cada fila de datos
                    while ($row = $result->fetch_assoc()) {
                        echo '<option value="' . $row['id'] . '">' . $row['nombre'] . '</option>';
                    }
                } else {
                    echo '<option value="">No hay roles disponibles</option>';
                }
                ?>
            </select>
        </div>

        <div class="form-group">
            <label for="name">Nombre:</label>
            <input type="text" class="form-control" id="name" name="name" required>
        </div>
        <div class="form-group">
            <label for="email">Correo:</label>
            <input type="email" class="form-control" id="email" name="email" required>
        </div>
        <div class="form-group">
            <label for="age">Contraseña:</label>
            <input type="password" class="form-control" id="pass" name="pass" required>
        </div>
        <button type="submit" class="btn btn-primary">Añadir Usuario</button>
    </form>
    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.5.2/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>