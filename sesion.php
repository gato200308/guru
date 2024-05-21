<?php
// Verificar si el formulario ha sido enviado
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Recibir los datos del formulario
    $identificacion = $_POST["identificacion"];
    $correo = $_POST["correo"];
    $contrasena = $_POST["contrasena"];

    // Validar los datos (aquí deberías implementar tus propias reglas de validación)
    if (empty($identificacion) || empty($correo) || empty($contrasena)) {
        echo "Por favor, complete todos los campos.";
    } else {
        // Conectar a la base de datos (reemplaza estos datos con los de tu base de datos)
        $servername = "localhost";
        $username = "root";
        $password = "";
        $dbname = "guru";

        // Crear conexión
        $conn = new mysqli($servername, $username, $password, $dbname);

        // Verificar la conexión
        if ($conn->connect_error) {
            die("La conexión falló: " . $conn->connect_error);
        }

        // Consulta SQL para verificar si el usuario existe
        $sql = "SELECT * FROM usuario WHERE identificacion = '$identificacion' AND correo = '$correo' AND contrasena = '$contrasena'";
        $result = $conn->query($sql);

        if ($result->num_rows > 0) {
            // Usuario autenticado correctamente
            session_start();
            $_SESSION["identificacion"] = $identificacion;
            $_SESSION["correo"] = $correo;
            // Redirigir a la página de inicio de sesión exitosa
            header("Location: index.html");
        } else {
            // Usuario no encontrado o credenciales incorrectas
            echo "Identificación, correo o contraseña incorrectos.";
        }
        $conn->close();
    }
}
?>
