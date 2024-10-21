<?php
// Habilitar la visualización de errores (solo para desarrollo, no en producción)
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
session_start(); // Inicia la sesión

// Configuración de la conexión a la base de datos
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "guru";

// Crear la conexión a la base de datos
$conn = new mysqli($servername, $username, $password, $dbname);

// Verificar la conexión
if ($conn->connect_error) {
    die("Conexión fallida: " . $conn->connect_error);
}

// Verificar si se recibieron los datos del formulario
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $identificacion = trim($_POST['identificacion'] ?? '');
    $correo = trim($_POST['correo'] ?? '');
    $contrasena = trim($_POST['contrasena'] ?? '');

    // Validar que no estén vacíos
    if (empty($identificacion) || empty($correo) || empty($contrasena)) {
        echo "Por favor complete todos los campos.";
        exit();
    }

    // Preparar la consulta para obtener la contraseña almacenada
    $stmt = $conn->prepare("SELECT contrasena, nombres, apellidos FROM usuario WHERE identificacion = ? AND correo = ?");
    $stmt->bind_param("ss", $identificacion, $correo);
    $stmt->execute();
    $stmt->store_result();

    // Verificar si el usuario existe
    if ($stmt->num_rows > 0) {
        $stmt->bind_result($stored_hash, $nombres, $apellidos);
        $stmt->fetch();

        // Verifica la contraseña ingresada contra la hasheada
        if (password_verify($contrasena, $stored_hash)) {
            // Si las credenciales son correctas, guardar los datos del usuario en la sesión
            $_SESSION['identificacion'] = $identificacion;
            $_SESSION['nombres'] = $nombres;
            $_SESSION['apellidos'] = $apellidos;

            // Redirigir a la página principal (index.html)
            header("Location: index.html");
            exit();
        } else {
            // Si la contraseña es incorrecta
            echo "Contraseña incorrecta. Por favor, intente de nuevo.";
        }
    } else {
        // Si no se encuentra el usuario, verificar por partes
        // Verificar si la identificación existe
        $stmt->close(); // Cerrar el primer statement
        $stmt = $conn->prepare("SELECT contrasena FROM usuario WHERE identificacion = ?");
        $stmt->bind_param("s", $identificacion);
        $stmt->execute();
        $stmt->store_result();

        if ($stmt->num_rows > 0) {
            // La identificación es correcta, pero el correo o la contraseña son incorrectos
            echo "Correo incorrecto. Por favor, intente de nuevo.";
        } else {
            // La identificación no existe
            echo "Identificación no encontrada. Por favor, intente de nuevo.";
        }
    }

    // Cerrar la consulta
    $stmt->close();
}

// Cerrar la conexión a la base de datos
$conn->close();
?>
