<?php
session_start();
// Verifica si el usuario ha iniciado sesión
if (!isset($_SESSION['identificacion'])) {
    header("Location: sesion.html"); // Redirige a la página de inicio de sesión si no está autenticado
    exit();
}
// Conectar a la base de datos
$conexion = new mysqli('localhost', 'root', '', 'guru');

// Verificar la conexión
if ($conexion->connect_error) {
    die("Conexión fallida: " . $conexion->connect_error);
}




// Obtener el historial de compras
$sql = "SELECT * FROM historial_compras ORDER BY fecha DESC";
$resultado = $conexion->query($sql);
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Historial de Compras</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 20px;
            background-color: #f0f0f0;
        }
        .container {
            max-width: 1000px;
            margin: 0 auto;
            background-color: white;
            padding: 20px;
            border-radius: 10px;
            box-shadow: 0 0 10px rgba(0,0,0,0.1);
        }
        h1 {
            color: #333;
            text-align: center;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }
        th, td {
            padding: 12px;
            text-align: left;
            border-bottom: 1px solid #ddd;
        }
        th {
            background-color: #ddd590;
            color: white;
        }
        tr:hover {
            background-color: #f5f5f5;
        }
        .button {
            display: inline-block;
            padding: 10px 20px;
            background-color: #a38746;
            color: white;
            text-decoration: none;
            border-radius: 5px;
            margin-top: 20px;
        }
        .button:hover {
            background-color: #94cf70;
        }
    </style>
</head>
<body>
    <div class="container">
        <h1>Historial de Compras</h1>
        
        <?php if ($resultado->num_rows > 0): ?>
            <table>
                <tr>
                    <th>Fecha</th>
                    <th>Productos</th>
                    <th>Total</th>
                </tr>
                <?php while($row = $resultado->fetch_assoc()): ?>
                    <tr>
                        <td><?php echo date('d/m/Y H:i', strtotime($row['fecha'])); ?></td>
                        <td>
                            <?php 
                            $productos = json_decode($row['productos'], true);
                            echo implode(', ', $productos);
                            ?>
                        </td>
                        <td>$<?php echo number_format($row['total'], 2); ?></td>
                    </tr>
                <?php endwhile; ?>
            </table>
        <?php else: ?>
            <p>No hay compras registradas.</p>
        <?php endif; ?>
        
        <a href="index.php" class="button">Volver al Inicio</a>
    </div>
</body>
</html>

<?php
$conexion->close();
?>
