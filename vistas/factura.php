<?php
require_once "config/Conexion.php";

// Obtener ID de la factura desde la URL
$idFactura = isset($_GET['id']) ? intval($_GET['id']) : 0;

// Obtener encabezado de factura
$sql_encabezado = "SELECT f.id, f.fecha, f.total, c.nombre, c.cedula, c.correo, c.telefono
                   FROM encabezado_factura f
                   INNER JOIN clientes c ON f.cedula_cliente = c.cedula
                   WHERE f.id = $idFactura";
$encabezado = ejecutarConsultaSimpleFila($sql_encabezado);

if (!$encabezado) {
    echo "❌ Factura no encontrada.";
    exit;
}

// Obtener detalle de factura
$sql_detalle = "SELECT p.nombre AS producto, d.cantidad, d.precioUnitario, d.subtotal
                FROM detalle_factura d
                INNER JOIN producto p ON d.id_producto = p.id
                WHERE d.id_encabezado = $idFactura";
$detalle = ejecutarConsulta($sql_detalle);
?>

<!DOCTYPE html>
<html>
<head>
    <title>Factura #<?php echo $encabezado['id']; ?></title>
    <link rel="stylesheet" href="public/css/bootstrap.css">
</head>
<body class="p-4">
    <div class="container border shadow p-4 rounded">
        <h2 class="text-center mb-4">Factura #<?php echo $encabezado['id']; ?></h2>

        <div class="mb-3">
            <strong>Fecha:</strong> <?php echo $encabezado['fecha']; ?><br>
            <strong>Cliente:</strong> <?php echo $encabezado['nombre']; ?><br>
            <strong>Cédula:</strong> <?php echo $encabezado['cedula']; ?><br>
            <strong>Email:</strong> <?php echo $encabezado['correo']; ?><br>
            <strong>Teléfono:</strong> <?php echo $encabezado['telefono']; ?>
        </div>

        <table class="table table-bordered">
            <thead class="table-secondary">
                <tr>
                    <th>Producto</th>
                    <th>Cantidad</th>
                    <th>Precio Unitario</th>
                    <th>Subtotal</th>
                </tr>
            </thead>
            <tbody>
                <?php while ($row = $detalle->fetch_assoc()) { ?>
                    <tr>
                        <td><?php echo $row['producto']; ?></td>
                        <td><?php echo $row['cantidad']; ?></td>
                        <td>₡<?php echo number_format($row['precioUnitario'], 2); ?></td>
                        <td>₡<?php echo number_format($row['subtotal'], 2); ?></td>
                    </tr>
                <?php } ?>
            </tbody>
            <tfoot class="table-light">
                <tr>
                    <th colspan="3" class="text-end">Total:</th>
                    <th>₡<?php echo number_format($encabezado['total'], 2); ?></th>
                </tr>
            </tfoot>
        </table>
    </div>
</body>
</html>
