<?php
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
    $identificacion=$_POST["identificacion"];
    $nombre = $_POST['nombre'];
    $apellido = $_POST['apellido'];
    $fechaNacimiento = $_POST['fechaNacimiento'];
    $telefono = $_POST['telefono'];
    $genero = $_POST['genero'];
    $correo = $_POST['correo'];
    $contrasena = $_POST['contrasena'];

    // Prepara y ejecuta la consulta SQL para insertar los datos en la tabla
    $sql = "INSERT INTO usuario (identificacion,nombres, apellidos, fecha_nacimiento, telefono, genero, correo, contrasena)
    VALUES ('$identificacion','$nombre', '$apellido', '$fechaNacimiento', '$telefono', '$genero', '$correo', '$contrasena')";

    if ($conn->query($sql) === TRUE) {
        echo "Registro exitoso";
    } else {
        echo "Error al registrar: " . $conn->error;
    }

    // Cierra la conexión a la base de datos
    $conn->close();
} else {
    // Si no se reciben datos por POST, redirige a la página de registro
    header("Location: registro.html");
    exit();
}
?>
