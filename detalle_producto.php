<?php
// Conexión a la base de datos
require 'conexion.php';

// Obtener el ID del producto
$id = $_GET['id'] ?? null;

if ($id) {
    // Consultar el producto en la base de datos
    $query = "SELECT * FROM productos WHERE id = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param('i', $id);
    $stmt->execute();
    $resultado = $stmt->get_result();
    $producto = $resultado->fetch_assoc();

    if (!$producto) {
        echo "Producto no encontrado.";
        exit;
    }
} else {
    echo "ID del producto no proporcionado.";
    exit;
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Detalle del producto</title>
    <link rel="stylesheet" href="styles.css">
</head>
<body>
    <h1><?php echo $producto['nombre']; ?></h1>
    <img src="<?php echo $producto['imagen_url']; ?>" alt="<?php echo $producto['nombre']; ?>">
    <p>Precio: $<?php echo $producto['precio']; ?></p>
    <p><?php echo $producto['descripcion']; ?></p>
    <a href="index.php">Volver a productos</a>
</body>
</html>
