<?php
session_start();
if (!isset($_SESSION['identificacion'])) {
    header("Location: sesion.html");
    exit();
}

$conexion = new mysqli('localhost', 'root', '', 'guru');
if ($conexion->connect_error) {
    die("Error de conexión: " . $conexion->connect_error);
}

$nombre = $_POST['nombre'];
$descripcion = $_POST['descripcion'];
$precio = $_POST['precio'];
$imagen = $_FILES['imagen'];
$vendedor_id = $_SESSION['identificacion'];  // Obtén el ID del vendedor desde la sesión

$ruta_imagen = 'imagenes_productos/' . basename($imagen['name']);
if (!move_uploaded_file($imagen['tmp_name'], $ruta_imagen)) {
    die("Error al mover el archivo de imagen.");
}

// Inserta el producto en la base de datos con el vendedor_id
$sql = "INSERT INTO productos (nombre, descripcion, precio, imagen_url, vendedor_id) VALUES (?, ?, ?, ?, ?)";
$stmt = $conexion->prepare($sql);
$stmt->bind_param("ssdsi", $nombre, $descripcion, $precio, $ruta_imagen, $vendedor_id);

if ($stmt->execute()) {
    echo "Producto subido exitosamente.";
} else {
    echo "Error al subir el producto: " . $stmt->error;
}

$stmt->close();
$conexion->close();

?>
