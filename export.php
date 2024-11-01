<?php
session_start(); // Inicia la sesión para acceder a las variables de sesión

// Verifica si el usuario ha iniciado sesión
if (!isset($_SESSION['identificacion'])) {
    echo "<script>alert('Error: No has iniciado sesión.'); window.location.href = 'index.html';</script>";
    exit();
}

// Conectar a la base de datos
$host = 'localhost';
$user = 'root'; // Cambia a tu usuario de MySQL
$pass = ''; // Cambia a tu contraseña de MySQL
$dbname = 'guru'; // Nombre de la base de datos

$conn = new mysqli($host, $user, $pass, $dbname);
if ($conn->connect_error) {
    die("Conexión fallida: " . $conn->connect_error);
}

// Obtener el rol del usuario desde la base de datos
$identificacion = $_SESSION['identificacion'];
$sql_rol = "SELECT Roles.nombre FROM Roles 
            INNER JOIN usuario ON usuario.rol = Roles.id 
            WHERE usuario.identificacion = '$identificacion'";
$result_rol = $conn->query($sql_rol);

if ($result_rol->num_rows > 0) {
    $row = $result_rol->fetch_assoc();
    $nombre_rol = $row['nombre'];

    // Verificar si el rol es 'Admin'
    if ($nombre_rol !== 'Admin') {
        echo "<script>alert('Acceso denegado: necesitas permisos de administrador para descargar este archivo.'); window.location.href = 'index.html';</script>";
        exit();
    }
} else {
    echo "<script>alert('Error: No se pudo obtener el rol del usuario.'); window.location.href = 'index.html';</script>";
    exit();
}

// Consulta para obtener los datos
$sql = "SELECT * FROM  usuario"; // Cambia 'tu_tabla' por el nombre de tu tabla
$result = $conn->query($sql);

// Configurar el archivo Excel para la descarga
header("Content-Type: application/vnd.ms-excel");
header("Content-Disposition: attachment; filename=datos.xls");
header("Pragma: no-cache");
header("Expires: 0");

// Imprimir los encabezados de las columnas
if ($result->num_rows > 0) {
    // Encabezados de columnas
    $column_names = array();
    while ($field_info = $result->fetch_field()) {
        $column_names[] = $field_info->name;
    }
    echo implode("\t", $column_names) . "\n";

    // Imprimir los datos
    while ($row = $result->fetch_assoc()) {
        echo implode("\t", $row) . "\n";
    }
} else {
    echo "0 resultados";
}

$conn->close();
?>
