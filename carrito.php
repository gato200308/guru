<?php
session_start(); // Asegúrate de iniciar la sesión al principio del archivo

// Conectar a la base de datos
$conexion = new mysqli('localhost', 'root', '', 'guru');

// Verificar la conexión
if ($conexion->connect_error) {
    die("Conexión fallida: " . $conexion->connect_error);
}

// Verificar si el carrito tiene productos
if (!isset($_SESSION['carrito']) || empty($_SESSION['carrito'])) {
    echo "<p>Tu carrito está vacío.</p>";
} else {
    // Si hay productos, mostrar el carrito
    echo "<h2>Carrito de Compras</h2>";
    echo "<table>";
    echo "<tr><th>Producto</th><th>Precio</th><th>Cantidad</th><th>Total</th><th>Imagen</th><th>Acción</th></tr>";

    $totalCarrito = 0;

    // Recorrer los productos en el carrito
    foreach ($_SESSION['carrito'] as $key => $item) {
        // Obtener la información del producto desde la base de datos usando el nombre del producto
        $nombre_producto = $item['producto'];  // Nombre del producto en el carrito
        $query = "SELECT nombre, precio, imagen_url FROM productos WHERE nombre = '$nombre_producto'";
        $result = $conexion->query($query);

        if ($result->num_rows > 0) {
            $producto = $result->fetch_assoc();
            $subtotal = $producto['precio']; // Precio del producto
            $totalCarrito += $subtotal;

            echo "<tr>";
            echo "<td>" . $producto['nombre'] . "</td>";
            echo "<td>$" . number_format($producto['precio'], 2) . "</td>";
            echo "<td>1</td>"; // Cantidad fija en 1
            echo "<td>$" . number_format($subtotal, 2) . "</td>";

            // Mostrar la imagen usando la ruta almacenada en la columna 'imagen_url'
            echo "<td><img src='" . $producto['imagen_url'] . "' alt='" . $producto['nombre'] . "' class='imagen-producto' width='50'></td>";

            // Imagen para eliminar el producto con tamaño reducido
            echo "<td><a href='eliminar_producto_carri.php?producto=" . urlencode($nombre_producto) . "'><img src='data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAABoAAAAaCAYAAACpSkzOAAAACXBIWXMAAAsTAAALEwEAmpwYAAAArklEQVR4nO2WTQrCMBBG30LcdKH38Q6CvZN6MHGteIZ6ihZGRiZQwTQTGtpNPvgWyfw8EgYS8KkFXsAAiHmwvROFdBw1j7ktAXpaszPQjPYb4GKxx1zIBuit2e5PfG+x3nLdujuuyevbFEgKexGIxGCLgYKSCQm561cDycx1VBUk9eqC6jBIHYbVhyFX4q1/W+IhG8G3Rms7T/K1wGOnP6aktgYLJ8uxnkQh2uNHHwhHEn7bB9NrAAAAAElFTkSuQmCC' alt='delete' width='20'></a></td>";
            echo "</tr>";
        }
    }

    echo "<tr><td colspan='4'>Total:</td><td>$" . number_format($totalCarrito, 2) . "</td></tr>";
    echo "</table>";

    echo "<a href='procesar_pago.php'>Proceder a Pago</a>";
    echo "<br><a href='eliminar-carrito.php'>Vaciar Carrito</a>";
    echo "<br><a href='index.php'>Inicio</a>";
}

// Cerrar la conexión
$conexion->close();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <head>
    <link rel="stylesheet" type="text/css" href="styles-carrito.css">

<body>
    
</body>
</html>