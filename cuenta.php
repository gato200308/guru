<?php
session_start();

// Verifica si el usuario ha iniciado sesión
if (!isset($_SESSION['usuario_id'])) {
    header("Location: sesion.html"); // Redirige a la página de inicio de sesión si no está autenticado
    exit();
}

// Conexión a la base de datos
$servername = "localhost"; // Cambiar si es necesario
$username = "root";
$password = "";
$dbname = "guru";

$conn = new mysqli($servername, $username, $password, $dbname);

// Verifica la conexión
if ($conn->connect_error) {
    die("Conexión fallida: " . $conn->connect_error);
}

// Obtener la información del usuario
$usuario_id = $_SESSION['usuario_id']; // Cambia esto según cómo almacenes la ID del usuario en la sesión
$sql = "SELECT identificacion, nombres, apellidos, fecha_nacimiento, telefono, correo FROM usuario WHERE id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $usuario_id);
$stmt->execute();
$result = $stmt->get_result();
$row = $result->fetch_assoc(); // Obtén la información del usuario

$stmt->close();
$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="styles.css">
    <link rel="icon" href="imagenes/icono app2.jpg" type="image/x-icon">
    <title>Cuenta</title>
</head>
<body>
    <header>
        <div class="banner-content">
            <div class="logo">
                <img src="imagenes/logotipo.png" alt="Logo">
            </div>
        </div>
        <nav class="navegacion-principal contenedor">
            <a href="index.html">Producto</a>
            <a href="registro.html">Registrarme</a>
        </nav>
    </header>
    
    <img class="cuentas" src="imagenes/sinfoto.jpg" alt="sinfoto">
    
    <?php if ($row): ?>
        <h2><?php echo htmlspecialchars($row['nombres']) . ' ' . htmlspecialchars($row['apellidos']); ?></h2>
        <div class="gato">
            <h3>Email</h3>
            <p><?php echo htmlspecialchars($row['correo']); ?></p>
            <h3>Teléfono</h3>
            <p><?php echo htmlspecialchars($row['telefono']); ?></p>
            <button type="submit">Editar Perfil</button>
        </div>
    <?php else: ?>
        <h2>No se encontró información del usuario.</h2>
    <?php endif; ?>
    
</body>
</html>
