<?php
// Habilitar la visualización de errores
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

session_start(); // Inicia la sesión

// Verifica si el usuario ha iniciado sesión
if (!isset($_SESSION['identificacion'])) {
    header("Location: sesion.php"); // Redirige si no ha iniciado sesión
    exit();
}

// Configuración de la conexión a la base de datos
$servername = "localhost"; // Cambia si es necesario
$username = "root";
$password = "";
$dbname = "guru";

// Crea una conexión a la base de datos
$conn = new mysqli($servername, $username, $password, $dbname);

// Verifica la conexión
if ($conn->connect_error) {
    die("Conexión fallida: " . $conn->connect_error); // Mensaje de error si la conexión falla
}

// Recupera la información del usuario desde la base de datos
$identificacion = $_SESSION['identificacion'];
$stmt = $conn->prepare("SELECT nombres, apellidos, correo, telefono FROM usuario WHERE identificacion = ?");
$stmt->bind_param("s", $identificacion);
$stmt->execute();
$stmt->store_result();

var_dump($stmt->num_rows); // Para depuración: cuántas filas se devuelven

if ($stmt->num_rows > 0) {
    // Si se encuentra el usuario, se obtienen los datos
    $stmt->bind_result($nombres, $apellidos, $correo, $telefono);
    $stmt->fetch();
} else {
    // Si no se encuentra el usuario, muestra un mensaje
    echo "No se encontraron datos para el usuario.";
    exit(); // Salir si no hay datos
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
    <title>Cuenta</title>
</head>
<body>
    <header>
        <div class="banner-content">
            <div class="logo">
                <img src="imagenes/logotipo.png" alt="Logo">
            </div>
            <nav class="navegacion-principal contenedor">
                <a href="index.html">INICIO</a>
                <a href="sesion.html">SESION</a>
            </nav>
        </div>
    </header>

    <div>
        <h2><?php echo htmlspecialchars($nombres . " " . $apellidos); ?></h2> <!-- Nombre encima del h3 -->
        <h3>Email</h3>
        <p><?php echo htmlspecialchars($correo); ?></p>
        <h3>Teléfono</h3>
        <p><?php echo htmlspecialchars($telefono); ?></p>
        <button type="button" onclick="location.href='editar_perfil.php'">Editar Perfil</button>
    </div>
</body>
</html>





