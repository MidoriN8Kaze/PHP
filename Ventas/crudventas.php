<?php
session_start();
include '../Conexion/conexion.php';
if (!isset($_SESSION['rol']) || $_SESSION['rol'] !== 'Cliente') {
    header("Location: ../Login/accesodenegado.php");
    exit();
}

if (!isset($_GET['idcliente'])) {
    die("No se ha seleccionado un cliente.");
}

$idcliente = $_GET['idcliente'];

// Usar prepared statement para seleccionar productos
$sql = "SELECT productos.id AS id, 
               productos.nombre AS nombreproducto,
               productos.descripcion AS descripcion,
               productos.precio AS precio,
               productos.stock AS stock
        FROM productos
        WHERE productos.estado = 1 AND productos.stock > 0;";
$stmt = $conectado->prepare($sql);
$stmt->execute();
$result = $stmt->get_result();

// Manejar la compra
if (isset($_POST['id']) && isset($_POST['cantidad'])) {
    $idproducto = $_POST['id'];
    $cantidad = $_POST['cantidad'];

    // Obtener stock actual del producto
    $sqlStock = "SELECT stock, precio FROM productos WHERE id = ?";
    $stmtStock = $conectado->prepare($sqlStock);
    $stmtStock->bind_param("i", $idproducto); // i para integer
    $stmtStock->execute();
    $resultStock = $stmtStock->get_result();
    $producto = $resultStock->fetch_assoc();

    // Comprobar si hay suficiente stock
    if ($producto['stock'] >= $cantidad) {
        $nuevo_stock = $producto['stock'] - $cantidad;

        // Actualizar el stock del producto
        $sqlUpdateStock = "UPDATE productos SET stock = ? WHERE id = ?";
        $stmtUpdateStock = $conectado->prepare($sqlUpdateStock);
        $stmtUpdateStock->bind_param("ii", $nuevo_stock, $idproducto); // ii para dos integers
        if ($stmtUpdateStock->execute()) {
            $precio_unitario = $producto['precio'];
            $valor_venta = $precio_unitario * $cantidad;

            // Insertar la venta en la tabla `ventas`
            $sqlInsertVenta = "INSERT INTO ventas (idclientes, idproductos, cantidad, valorunitario, valorventa, fecha, estado)
                               VALUES (?, ?, ?, ?, ?, CURDATE(), 1)";
            $stmtInsertVenta = $conectado->prepare($sqlInsertVenta);
            $stmtInsertVenta->bind_param("iiidd", $idcliente, $idproducto, $cantidad, $precio_unitario, $valor_venta); // iiidd para dos integers y tres doubles
            
            if ($stmtInsertVenta->execute()) {
                $mensaje = "<div class='alert alert-success'>Compra exitosa y registrada en ventas!</div>";
                header("Refresh: 2;"); // Refrescar después de 2 segundos
            } else {
                $mensaje = "<div class='alert alert-danger'>Error al registrar la venta.</div>";
            }
        } else {
            $mensaje = "<div class='alert alert-danger'>Error al procesar la compra.</div>";
        }
    } else {
        $mensaje = "<div class='alert alert-warning'>No hay suficiente stock disponible.</div>";
    }
}

?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Productos Disponibles</title>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <link rel="stylesheet" href="https://cdn.datatables.net/1.11.5/css/jquery.dataTables.min.css">
    <script src="https://cdn.datatables.net/1.11.5/js/jquery.dataTables.min.js"></script>
    <script>
        function confirmarCompra() {
            return confirm("¿Estás seguro de que deseas realizar esta compra?");
        }
    </script>
</head>
<body>
<div class="container mt-4">
    <a href="../Login/logout.php" class="btn btn-danger btn-sm">Salir</a>
    <h2>Productos Disponibles</h2>

    <!-- Mostrar mensaje de estado -->
    <?php if (!empty($mensaje)) echo $mensaje; ?>

    <table class="table table-bordered" id="table">
        <thead>
        <tr>
            <th>Nombre</th>
            <th>Descripción</th>
            <th>Precio</th>
            <th>Stock</th>
            <th>Acción</th>
        </tr>
        </thead>
        <tbody>
        <?php while ($row = $result->fetch_assoc()) : ?>
            <tr>
                <td><?php echo htmlspecialchars($row["nombreproducto"]); ?></td>
                <td><?php echo htmlspecialchars($row["descripcion"]); ?></td>
                <td><?php echo htmlspecialchars($row["precio"]); ?></td>
                <td><?php echo htmlspecialchars($row["stock"]); ?></td>
                <td>
                    <form action="" method="post" onsubmit="return confirmarCompra();">
                        <input type="hidden" name="id" value="<?php echo $row['id']; ?>">
                        <input type="hidden" name="idcliente" value="<?php echo $idcliente; ?>">
                        <input type="number" name="cantidad" min="1" max="<?php echo $row['stock']; ?>" required>
                        <button type="submit" class="btn btn-success btn-sm">
                            <img src="imagenes/shopping-cart-icon-shopping-cart-2f1cbe.webp" height="32px" width="32px">
                        </button>
                    </form>
                </td>
            </tr>
        <?php endwhile; ?>
        </tbody>
    </table>
</div>

<script>
    $(document).ready(function() {
        $('#table').DataTable();
    });
</script>

</body>
</html>
