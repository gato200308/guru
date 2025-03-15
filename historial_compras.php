<?php
session_start();

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
    <link rel="stylesheet" href="styles.css">
    <style>
        .container {
            max-width: 1000px;
            margin: 20px auto;
            background-color: white;
            padding: 20px;
            border-radius: 10px;
            box-shadow: 0 0 10px rgba(0,0,0,0.1);
        }
        h1 {
            color: black;
            text-align: center;
            margin-bottom: 30px;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
            background-color: white;
        }
        th, td {
            padding: 12px;
            text-align: left;
            border-bottom: 1px solid #ddd590;
            color: black;
        }
        th {
            background-color: #ddd590;
            color: black;
            font-weight: bold;
        }
        tr:hover {
            background-color: #f5f5f5;
        }
        .button {
            display: inline-block;
            padding: 10px 20px;
            background-color: #ddd590;
            color: black;
            text-decoration: none;
            border-radius: 4px;
            margin-top: 20px;
            font-weight: bold;
        }
        .button:hover {
            background-color: #92b839;
        }
        .no-compras {
            color: black;
            text-align: center;
            padding: 20px;
        }
    </style>
</head>
<body>
    <header>
        <h1>Historial de Compras</h1>
        <nav class="navegacion-principal contenedor">
            <a href="sesion.html">SESION</a>
            <a href="index.php">PRODUCTOS</a>
            <a href="contacto.html">CONTACTO</a>
            <a href="cuenta.php">CUENTA</a>
            <a href="carrito.php" class="carrito-enlace">
                <img src="data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAABoAAAAaCAYAAACpSkzOAAAACXBIWXMAAAsTAAALEwEAmpwYAAABV0lEQVR4nLWVTyuEURTGf0WhNFnIQgk12SmfwG4s2NhIWVPzBSSysGSrrHwAErMQCzUrY2cpFnaz1EiRKfl3dfWot0lzz33feZ86m3PPeX63+77nXoAa4BLxDtwCS3RYtRbQX3wDM+SobmBTsDNy1iDwCbwB/YFap0itKxnM5w1al8F+3qBJGXwAXy1mLhDJujpQCsHqbQycEeSjGgLtqfAy5dFtae0iBJpVof8DhyJBXcC91sohUC/QVPFyJGhB+QegD4NO1XBOnK7Vt2FtKKvBD2/B2FNSzwswYAWNJI5n0dhTVf0OkbpR44GhdkoXsj+B4VjQtkDPQE+g9sh4o/yracOQukT4cZhI+3Q8GSFNYJUMOpTRWhYTi+YEetUw+mHOTbsR3+kRKGaBrQB3ej7agRpZQeNABTgBxgz51Kokdu1NQ/mOgI4N+dTyx+J37M1GDflf/QC6iamAjtlFMgAAAABJRU5ErkJggg==" alt="Carrito" title="Ver carrito">
            </a>
        </nav>
    </header>

    <div class="container">
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
            <p class="no-compras">No hay compras registradas.</p>
        <?php endif; ?>
        
        <a href="index.php" class="button">Volver al Inicio</a>
    </div>

    <footer>
        <p>&copy; 2024 GURU. Todos los derechos reservados.</p>
    </footer>
</body>
</html>

<?php
$conexion->close();
?>
