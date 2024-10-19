<?php
// Este PHP es la conexión de sesión

// Verifica si se han enviado datos por POST
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Configuración de la conexión a la base de datos
    $servername = "localhost"; // Cambiar si es necesario
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
    $identificacion = $_POST["identificacion"];
    $nombre = $_POST['nombre'];
    $apellido = $_POST['apellido'];
    $fechaNacimiento = $_POST['fechaNacimiento'];
    $telefono = $_POST['telefono'];
    $genero = $_POST['genero'];
    $correo = $_POST['correo'];
    $contrasena = $_POST['contrasena'];

    // Encriptar la contraseña
    $hashedContrasena = password_hash($contrasena, PASSWORD_DEFAULT);

    // Prepara la consulta SQL para verificar si el usuario ya existe
    $checkUserStmt = $conn->prepare("SELECT identificacion FROM usuario WHERE identificacion = ? OR correo = ?");
    $checkUserStmt->bind_param("ss", $identificacion, $correo);
    $checkUserStmt->execute();
    $checkUserStmt->store_result();

    if ($checkUserStmt->num_rows > 0) {
        echo "Error: El usuario ya existe.";
    } else {
        // Prepara la consulta SQL para insertar los datos en la tabla
        $stmt = $conn->prepare("INSERT INTO usuario (identificacion, nombres, apellidos, fecha_nacimiento, telefono, genero, correo, contrasena) VALUES (?, ?, ?, ?, ?, ?, ?, ?)");
        $stmt->bind_param("ssssssss", $identificacion, $nombre, $apellido, $fechaNacimiento, $telefono, $genero, $correo, $hashedContrasena);

        // Ejecuta la consulta y verifica el resultado
        if ($stmt->execute()) {
            // Cierra la conexión a la base de datos
            $stmt->close();
            $conn->close();
            // Redirige a la página de inicio de sesión
            header("Location: sesion.html");
            exit();
        } else { 
            echo "Error al registrar: " . $stmt->error;
        }
    }

    // Cierra la consulta de verificación
    $checkUserStmt->close();
    $conn->close();
} else {
    // Si no se reciben datos por POST, redirige a la página de registro
    header("Location: sesion.html");
    exit();
}
?>

