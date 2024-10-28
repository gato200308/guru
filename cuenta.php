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
$usuario_id = $_SESSION['usuario_id'];
$sql = "SELECT identificacion, nombres, apellidos, fecha_nacimiento, telefono, correo FROM usuario WHERE identificacion = ?";
$stmt = $conn->prepare($sql);

if (!$stmt) {
    die("Error en la preparación de la consulta: " . $conn->error);
}

$stmt->bind_param("i", $usuario_id);
$stmt->execute();
$result = $stmt->get_result();
$stmt->close();
$conn->close();

// Mostrar la información del usuario en formato HTML
if ($result->num_rows > 0) {
    $row = $result->fetch_assoc();
    echo "<h2>" . htmlspecialchars($row['nombres']) . " " . htmlspecialchars($row['apellidos']) . "</h2>";
    echo "<div class='gato'>";
    echo "<h3>Email</h3>";
    echo "<p>" . htmlspecialchars($row['correo']) . "</p>";
    echo "<h3>Teléfono</h3>";
    echo "<p>" . htmlspecialchars($row['telefono']) . "</p>";
    echo "<button type='submit'>Editar Perfil</button>";
    echo "</div>";
} else {
    echo "<p style='color: white;'>No se encontró información del usuario.</p>";
}
?>
