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

// Recibe los datos del formulario
$identificacion = trim($_POST["identificacion"]);
$nombres = trim($_POST["nombres"]);
$apellidos = trim($_POST["apellidos"]);
$correo = trim($_POST["correo"]);
$telefono = trim($_POST["telefono"]);
$genero = trim($_POST["genero"]);

// Prepara la consulta SQL para actualizar los datos
$stmt = $conn->prepare("UPDATE usuario SET nombres = ?, apellidos = ?, correo = ?, telefono = ?, genero = ? WHERE identificacion = ?");
$stmt->bind_param("ssssss", $nombres, $apellidos, $correo, $telefono, $genero, $identificacion);

// Ejecuta la consulta y verifica el resultado
if ($stmt->execute()) {
    echo "Información actualizada correctamente.";
    header("Location: cuenta.php"); // Redirige a la página de cuenta
    exit();
} else {
    echo "Error al actualizar: " . $stmt->error;
}

// Cierra la declaración y la conexión
$stmt->close();
$conn->close();
?>
