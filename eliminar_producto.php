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

// Manejar la solicitud de eliminación de productos
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['eliminar_productos'], $_POST['producto_ids'])) {
    $producto_ids = $_POST['producto_ids'];

    // Preparar la declaración SQL para eliminar múltiples productos
    $id_placeholders = implode(',', array_fill(0, count($producto_ids), '?'));
    $stmt = $conn->prepare("DELETE FROM productos WHERE id IN ($id_placeholders)");

    // Vincular los parámetros
    $stmt->bind_param(str_repeat('i', count($producto_ids)), ...$producto_ids);

    if ($stmt->execute()) {
        echo "<p>Productos eliminados con éxito.</p>";
    } else {
        echo "<p>Error al eliminar los productos. Por favor, inténtalo más tarde.</p>";
    }

    $stmt->close();
}

$conn->close();
?>
