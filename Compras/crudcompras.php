<?php 
include "../Conexion/conexion.php";
if (!isset($_SESSION['rol']) || $_SESSION['rol'] !== 'Proveedor') {
    header("Location: ../Login/accesodenegado.php");
    exit();
}
$valorcompra = 0;

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Capturamos todos los datos del formulario
    $idproducto = $_POST['producto'];
    $idproveedor = $_POST['proveedor'];
    $cantidad = $_POST['cantidad'];
    $valorunidad = $_POST['unidad'];
    $fecha = $_POST['fecha']; 

    // Validamos los valores numéricos
    if (is_numeric($cantidad) && is_numeric($valorunidad) && $cantidad > 0 && $valorunidad > 0) {
        $valorcompra = $cantidad * $valorunidad;
        
        // Verificamos que tengamos todos los datos necesarios
        if (!empty($idproducto) && !empty($idproveedor) && !empty($fecha)) {
            $sql = "INSERT INTO `compras` (`idproductos`, `idproveedores`, `cantidad`, `valorunitario`, `valorcompra`, `fecha`) 
                    VALUES (?, ?, ?, ?, ?, ?);";
            $sql2 = "UPDATE productos SET stock = stock + ? WHERE id = ?;";

            $stmt = $conectado->prepare($sql);
            $stmt2 = $conectado->prepare($sql2);

            $stmt->bind_param("iiidss", $idproducto, $idproveedor, $cantidad, $valorunidad, $valorcompra, $fecha);
            $stmt2->bind_param("ii", $cantidad, $idproducto);

            if ($stmt->execute() && $stmt2->execute()) {
                header('Location: crudcompras.php');
                exit(); 
            } else {
                echo "<div class='alert alert-danger'>Compra fallida. Error: " . $conectado->error . "</div>";
            }

            $stmt->close();
            $stmt2->close();
        } else {
            echo "<div class='alert alert-danger'>Todos los campos son requeridos.</div>";
        }
    } else {
        echo "<div class='alert alert-danger'>Cantidad y Valor Unitario deben ser números positivos.</div>";
    }
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Añadir Compra</title>
    <link href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
</head>

<body class="container mt-5">
<a href="../Login/logout.php" class="btn btn-danger btn-lg">Salir</a>
    <h1>Añadir una Compra</h1>
    <form action="" method="post">
        <div class="form-group">
            <label for="producto">Producto</label>
            <select class="form-control" id="producto" name="producto" required>
                <option value="">Seleccione un producto</option>
                <?php
                $select = "SELECT id, nombre FROM productos";
                $result = $conectado->query($select);

                if ($result->num_rows > 0) {
                    while ($row = $result->fetch_assoc()) {
                        echo '<option value="' . $row['id'] . '">' . $row['nombre'] . '</option>';
                    }
                } else {
                    echo '<option value="">No hay productos disponibles</option>';
                }
                ?>
            </select>
        </div>

        <div class="form-group">
            <label for="proveedor">Proveedor</label>
            <select class="form-control" id="proveedor" name="proveedor" required>
                <option value="">Seleccione un proveedor</option>
                <?php
                $select = "SELECT id, nombre FROM proveedores";
                $result = $conectado->query($select);

                if ($result->num_rows > 0) {
                    while ($row = $result->fetch_assoc()) {
                        echo '<option value="' . $row['id'] . '">' . $row['nombre'] . '</option>';
                    }
                } else {
                    echo '<option value="">No hay proveedores disponibles</option>';
                }
                ?>
            </select>
        </div>

        <div class="form-group">
            <label for="cantidad">Cantidad:</label>
            <input type="number" class="form-control" id="cantidad" name="cantidad" required min="1"
                   oninput="total.value = cantidad.value * unidad.value">
        </div>

        <div class="form-group">
            <label for="unidad">Valor Unitario:</label>
            <input type="number" class="form-control" id="unidad" name="unidad" required min="1"
                   oninput="total.value = cantidad.value * unidad.value">
        </div>

        <div class="form-group">
            <label for="total">Valor Compra:</label>
            <input type="number" class="form-control" id="total" name="total" value="<?php echo $valorcompra; ?>" readonly>
        </div>

        <div class="form-group">
            <label for="fecha">Fecha:</label>
            <input type="date" class="form-control" id="fecha" name="fecha" required>
        </div>

        <button type="submit" class="btn btn-primary">Añadir Compra</button>
    </form>

    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.bundle.min.js"></script>
</body>

</html>