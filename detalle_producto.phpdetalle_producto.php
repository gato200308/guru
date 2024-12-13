<?php
session_start();

// Verificar si se ha recibido un ID de producto
if (isset($_GET['id'])) {
    $producto_id = $_GET['id'];
    
    // Obtener el producto desde la base de datos (deberías definir esta función)
    $producto = obtenerProductoPorId($producto_id); // Asegúrate de crear esta función para obtener el producto

    if (!$producto) {
        echo "Producto no encontrado.";
        exit();
    }
} else {
    echo "ID de producto no válido.";
    exit();
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Detalle del Producto</title>
    <link rel="stylesheet" href="styles.css">
</head>
<body>
    <header>
        <h1>Detalles del Producto</h1>
        <nav>
            <a href="index.php">Productos</a>
            <a href="carrito.php">Ver Carrito</a>
        </nav>
    </header>

    <main>
        <div class="producto-detalle">
            <img src="<?php echo $producto['imagen_url']; ?>" alt="<?php echo $producto['nombre']; ?>" class="imagen-detalle">
            <h2><?php echo $producto['nombre']; ?></h2>
            <p><?php echo $producto['descripcion']; ?></p>
            <p>Precio: $<?php echo $producto['precio']; ?></p>
            <a href="carrito.php?id=<?php echo $producto['id']; ?>">Añadir al carrito</a>
        </div>
    </main>
</body>
</html>
