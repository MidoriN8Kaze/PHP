<?php
session_start();
include "../Conexion/conexion.php";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $correo = $_POST["correo"];
    $contraseña = $_POST["contraseña"];

    $sql = "SELECT usuarios.email, usuarios.pass, 
            usuarios.estado, roles.nombre AS rol 
            FROM usuarios 
            JOIN roles ON usuarios.idrol = roles.id 
            WHERE usuarios.email = ? 
            AND usuarios.pass = ? 
            AND usuarios.estado = 1";

    if ($stmt = $conectado->prepare($sql)) {
        $stmt->bind_param("ss", $correo, $contraseña);
        $stmt->execute();
        $resultado = $stmt->get_result();

        if ($resultado->num_rows > 0) {
            $usuario = $resultado->fetch_assoc();
            $_SESSION['rol'] = $usuario['rol'];  
            $_SESSION['correo'] = $usuario['email']; 

            // Redirige según el rol
            switch ($_SESSION['rol']) {
                case 'Cliente':
                    header("Location: ../Ventas/vistaclientes.php");
                    break;
                case 'Proveedor':
                    header("Location: ../Compras/crudcompras.php");
                    break;
                case 'Admin':
                    header("Location: ../Admin/admin.php");
                    break;
                default:
                    echo "Error: Rol no reconocido.";
                    break;
            }
        } else {
            echo "Error: Usuario o contraseña incorrectos, o cuenta inactiva.";
        }
        $stmt->close();
    } else {
        echo "Error en la preparación de la consulta: " . $conectado->error;
    }
    $conectado->close();
}
?>


<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Iniciar Sesión</title>
    <link href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
</head>

<body class="container mt-5">

    <div class="row justify-content-center">
        <div class="col-md-6">
            <div class="card">
                <div class="card-body">
                    <h1 class="text-center mb-4">Iniciar Sesión</h1>

                    <form action="" method="post">
                        <div class="form-group">
                            <label for="email">Correo Electrónico</label>
                            <input type="email" class="form-control" id="email" name="correo" placeholder="User" required>
                        </div>
                        <div class="form-group">
                            <label for="contraseña">Contraseña</label>
                            <input type="password" class="form-control" id="contraseña" name="contraseña" placeholder="Password" required>
                        </div>
                        <button type="submit" class="btn btn-primary btn-block">Iniciar Sesión</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
    <hr class="my-4">
    <div class="row justify-content-center"><a href="crudusuario.php" class="btn btn-info">Crear usuario</a></div>
    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.5.2/dist/js/bootstrap.bundle.min.js"></script>

</body>

</html>