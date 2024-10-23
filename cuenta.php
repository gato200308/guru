<?php
session_start();

// Verifica si el usuario ha iniciado sesión
if (!isset($_SESSION['usuario_id'])) {
    echo "<p style='color: white;'>No ha iniciado sesión.</p>";
    exit();
}

// Conexión a la base de datos
$servername = "localhost";
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

$sql = "SELECT identificacion, nombres, apellidos, fecha_nacimiento, telefono, correo FROM usuario WHERE identificacion = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("s", $usuario_id);
$stmt->execute();
$result = $stmt->get_result();
$row = $result->fetch_assoc();

$stmt->close();
$conn->close();

// Mostrar la información del usuario en formato HTML
if ($row) {
    echo "<h2>" . htmlspecialchars($row['nombres']) . " " . htmlspecialchars($row['apellidos']) . "</h2>";
    echo "<div class='gato'>";
    echo "<h3>Email</h3>";
    echo "<p>" . htmlspecialchars($row['correo']) . "</p>";
    echo "<h3>Teléfono</h3>";
    echo "<p>" . htmlspecialchars($row['telefono']) . "</p>";
    echo "<button type='submit'>Editar Perfil</button>";
    echo "</div>";
} else {
    echo "No se encontró información del usuario.";
}
?>
