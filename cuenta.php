<?php
// Habilitar la visualización de errores (solo para desarrollo)
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

// Iniciar sesión
session_start();

// Verifica si el usuario ha iniciado sesión
if (!isset($_SESSION['identificacion'])) {
    header("Location: sesion.html"); // Redirige a la página de inicio de sesión si no está autenticado
    exit();
}

// Configuración de la conexión a la base de datos
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "guru";

// Crea una conexión a la base de datos
$conn = new mysqli($servername, $username, $password, $dbname);

// Verifica la conexión
if ($conn->connect_error) {
    echo "<p>Error al conectar con la base de datos. Por favor, inténtalo más tarde.</p>";
    exit(); // Detiene la ejecución si hay un error de conexión
}

// Obtener la identificación del usuario
$identificacion = $_SESSION['identificacion'];

// Manejar la solicitud de eliminación de cuenta
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['eliminar_cuenta'])) {
    // Eliminar la cuenta del usuario
    $stmt = $conn->prepare("DELETE FROM usuario WHERE identificacion = ?");
    $stmt->bind_param("s", $identificacion);

    if ($stmt->execute()) {
        // Cerrar sesión y redirigir a la página de inicio
        session_destroy();
        header("Location: index.html"); // Redirige a la página principal
        exit();
    } else {
        echo "<p>Error al eliminar la cuenta. Por favor, inténtalo más tarde.</p>";
    }

    $stmt->close();
}

// Obtener la información del usuario
$stmt = $conn->prepare("SELECT nombres, apellidos, correo, telefono, rol FROM usuario WHERE identificacion = ?");
$stmt->bind_param("s", $identificacion);
$stmt->execute();
$stmt->store_result();

if ($stmt->num_rows > 0) {
    $stmt->bind_result($nombres, $apellidos, $correo, $telefono, $rol);
    $stmt->fetch();
} else {
    echo "<p>No se encontraron datos para el usuario.</p>";
    exit();
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
    <link rel="icon" href="imagenes/icono app2.jpg" type="image/x-icon">
    <title>Cuenta</title>
</head>
<body>
    <header class="site-header">
        <div class="contenedor contenido-header">
            <div class="barra">
                <a href="index.html"></a>
            </div>
            <nav class="navegacion-principal contenedor">
                <a href="index.html">INICIO</a>
                <a href="cerrar_sesion.php">CERRAR SESIÓN</a>
            </nav>
        </div>
    </header>

    <div>
        <h2><?php echo htmlspecialchars($nombres . " " . $apellidos); ?></h2>
        <h3>Email</h3>
        <p><?php echo htmlspecialchars($correo); ?></p>
        <h3>Teléfono</h3>
        <p><?php echo htmlspecialchars($telefono); ?></p>

        <!-- Mostrar el botón de subir producto solo si el rol es vendedor -->
        <?php if ($rol == 3): ?>
            <button type="button" onclick="window.open('subir_producto_form.php', 'popup', 'width=400,height=600');">Subir producto</button>
        <?php endif; ?>
        <?php if ($rol == 3) {
    echo '<button type="button" onclick="window.open(\'eliminar_producto_form.php\', \'popup\', \'width=600,height=400\');">Eliminar Producto</button>';}
?>

<button onclick="exportToExcel()">Exportar a Excel</button>

    <script>
        function exportToExcel() {
            window.location.href = 'export.php'; // Asegúrate de que export.php esté en el mismo directorio
        }
    </script>
        <button type="button" onclick="location.href='editar_perfil.php'">Editar Perfil</button>
        
        <!-- Botón para eliminar cuenta -->
        <form method="POST" style="display: inline;">
            <button type="submit" name="eliminar_cuenta" style="color: white;" onclick="return confirm('¿Estás seguro de que deseas eliminar tu cuenta? Esta acción no se puede deshacer.');">Eliminar Cuenta</button>
        </form>
    </div>
</body>
</html>
