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
    die("Conexión fallida: " . $conn->connect_error);
}

// Obtener la identificación del usuario
$identificacion = $_SESSION['identificacion'];

// Eliminar la cuenta del usuario
$stmt = $conn->prepare("DELETE FROM usuario WHERE identificacion = ?");
$stmt->bind_param("s", $identificacion);

if ($stmt->execute()) {
    // Cerrar sesión y redirigir a la página de inicio
    session_destroy();
    header("Location: index.html"); // Redirige a la página principal
    exit();
} else {
    echo "Error al eliminar la cuenta: " . $conn->error;
}

$stmt->close();
$conn->close();
?>
