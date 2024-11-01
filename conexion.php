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
    $identificacion = trim($_POST["identificacion"]);
    $nombre = trim($_POST['nombres']); // Cambiado de 'nombre' a 'nombres'
    $apellido = trim($_POST['apellidos']); // Cambiado de 'apellido' a 'apellidos'
    $fechaNacimiento = trim($_POST['fecha_nacimiento']); // Cambiado de 'fechaNacimiento' a 'fecha_nacimiento'
    $telefono = trim($_POST['telefono']);
    $genero = trim($_POST['genero']);
    $rol = trim($_POST['rol']); // Añadir el rol
    $correo = trim($_POST['correo']);
    $contrasena = trim($_POST['contrasena']);

    // Validar que todos los campos requeridos no estén vacíos
    if (empty($identificacion) || empty($nombre) || empty($apellido) || empty($correo) || empty($contrasena)) {
        echo "Por favor, complete todos los campos requeridos.";
        exit();
    }

    // Prepara la consulta SQL para verificar si el usuario ya existe
    $checkUserStmt = $conn->prepare("SELECT identificacion FROM usuario WHERE identificacion = ? OR correo = ?");
    $checkUserStmt->bind_param("ss", $identificacion, $correo);
    $checkUserStmt->execute();
    $checkUserStmt->store_result();

    // Verifica si el usuario ya existe
    if ($checkUserStmt->num_rows > 0) {
        echo "Error: El usuario ya existe.";
    } else {
        // Encriptar la contraseña
        $hashedContrasena = password_hash($contrasena, PASSWORD_DEFAULT);
        
        // Prepara la consulta SQL para insertar los datos en la tabla
        $stmt = $conn->prepare("INSERT INTO usuario (identificacion, nombres, apellidos, fecha_nacimiento, telefono, genero, rol, correo, contrasena) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)");
        $stmt->bind_param("ssssssiss", $identificacion, $nombre, $apellido, $fechaNacimiento, $telefono, $genero, $rol, $correo, $hashedContrasena);
        
        // Ejecuta la consulta y verifica el resultado
        if ($stmt->execute()) {
            // Redirige a la página de inicio de sesión
            header("Location: sesion.html");
            exit();
        } else { 
            echo "Error al registrar: " . $stmt->error;
        }
        
        // Cierra la declaración
        $stmt->close();
    }

    // Cierra la consulta de verificación
    $checkUserStmt->close();
    // Cierra la conexión a la base de datos
    $conn->close();
} else {
    // Si no se reciben datos por POST, redirige a la página de registro
    header("Location: registro.html");
    exit();
}
?>
