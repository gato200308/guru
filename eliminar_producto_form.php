<?php
// Habilitar la visualización de errores (solo para desarrollo)
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

// Iniciar sesión
session_start();

// Verifica si el usuario ha iniciado sesión
if (!isset($_SESSION['identificacion'])) {
    header("Location: sesion.html");
    exit();
}

// Configuración de la conexión a la base de datos
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "guru";

// Crea una conexión a la base de datos
$conn = new mysqli($servername, $username, $password, $dbname);

// Verifica la conexión
if ($conn->connect_error) {
    echo "<p>Error al conectar con la base de datos. Por favor, inténtalo más tarde.</p>";
    exit();
}

// Obtener la lista de productos
$result = $conn->query("SELECT id, nombre,imagen_url FROM productos");
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="styles_eliminarP.css">
    <title>Eliminar Productos</title>
    <style>
        .producto {
            display: flex;
            align-items: center;
            margin-bottom: 10px;
        }
        .producto img {
            width: 100px; /* Ajusta el tamaño de la imagen según sea necesario */
            height: auto;
            margin-right: 10px;
        }
    </style>
</head>
<body>
    <h2>Eliminar Productos</h2>
    <form method="POST" action="eliminar_producto.php">
        <label>Selecciona los productos para eliminar:</label><br>
        <?php
        if ($result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {
                echo '<div class="producto">';
                echo '<input type="checkbox" name="producto_ids[]" value="' . htmlspecialchars($row['id']) . '">';
                echo '<img src="' . htmlspecialchars($row['imagen_url']) . '" alt="' . htmlspecialchars($row['nombre']) . '">';
                echo '<span>' . htmlspecialchars($row['nombre']) . '</span>';
                echo '</div>';
            }
        } else {
            echo "<p>No hay productos disponibles</p>";
        }
        ?>
        <button type="submit" name="eliminar_productos" onclick="return confirm('¿Estás seguro de que deseas eliminar estos productos? Esta acción no se puede deshacer.');">Eliminar Seleccionados</button>
    </form>
    <form class="boton2" action="cuenta.php" method="get">
            <button type="submit">cerrar</button>
        </form>
</body>
</html>

<?php
$conn->close();
?>
