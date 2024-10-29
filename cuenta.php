<?php
// Habilitar la visualización de errores (solo para desarrollo)
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

// Iniciar sesión
session_start();

// Verifica si el usuario ha iniciado sesión
if (!isset($_SESSION['identificacion'])) {
    header("Location: sesion.html"); // Redirige a la página de inicio de sesión si no está autenticado
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
    die("Conexión faloida: " . $conn->connect_error);
}

// Obtener la información del usuario
$identificacion = $_SESSION['identificacion'];
$stmt = $conn->prepare("SELECT nombres, apellidos, correo, telefono FROM usuario WHERE identificacion = ?");
$stmt->bind_param("s", $identificacion);
$stmt->execute();
$stmt->store_result();

if ($stmt->num_rows > 0) {
    $stmt->bind_result($nombres, $apellidos, $correo, $telefono);
    $stmt->fetch();
} else {
    echo "No se encontraron datos para el usuario.";
    exit();
}

$stmt->close();
$conn->close();
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="styles.css">
    <link rel="icon" href="imagenes/icono app2.jpg" type="image/x-icon">
    <title>Cuenta</title>
</head>
<body>
    <header class="site-header">
        <div class="contenedor contenido-header">
            <div class="barra">
                <a href="index.html">
                </a>
            </div>
            <nav class="navegacion-principal contenedor">
                <a href="index.html">INICIO</a>
                <a href="cerrar_sesion.php">Cerrar sesión</a>
            </nav>
        </div>
    </header>

    <div>
        <h2><?php echo htmlspecialchars($nombres . " " . $apellidos); ?></h2>
        <h3>Email</h3>
        <p><?php echo htmlspecialchars($correo); ?></p>
        <h3>Teléfono</h3>
        <p><?php echo htmlspecialchars($telefono); ?></p>
        <button type="button" onclick="location.href='editar_perfil.php'">Editar Perfil</button>
    </div>
</body>
</html>
