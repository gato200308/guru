<?php
// Asegúrate de que solo los usuarios autenticados puedan acceder a esta página
session_start();
if (!isset($_SESSION['identificacion'])) {
    header("Location: sesion.html");
    exit();
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>SUBIR PRODUCTO</title>
    <link rel="stylesheet" href="styles_producto.css">
    <link rel="icon" href="imagenes/icono app2.jpg" type="image/x-icon">
</head>
<body>
    <form action="procesar_producto.php" method="post" enctype="multipart/form-data">
        <label for="nombre">Nombre del Producto:</label>
        <input type="text" name="nombre" id="nombre" required>

        <label for="descripcion">Descripción:</label>
        <textarea name="descripcion" id="descripcion" required></textarea>

        <label for="precio">Precio:</label>
<input type="number" name="precio" id="precio" step="any" required>


        <label for="imagen">Imagen del Producto:</label>
        <input type="file" name="imagen" id="imagen" accept="image/*" required>
        <p><small>Sube una imagen sin fondo para mejorar la presentación del producto.</small></p>

        <button type="submit">Subir Producto</button>
    </form>
    <footer>
        <p>&copy; 2024 Guru Sales</p>
    </footer>
</body>
</html>
