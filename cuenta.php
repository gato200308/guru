<?php
// Habilitar la visualización de errores (solo para desarrollo)
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

// Iniciar sesión
session_start();

// Verifica si el usuario ha iniciado sesión
if (!isset($_SESSION['identificacion'])) {
    header("Location: sesion.html");
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
    exit();
}

// Obtener la identificación del usuario
$identificacion = $_SESSION['identificacion'];

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
    <style>
        .contenido-cuenta {
            max-width: 800px;
            margin: 20px auto;
            padding: 30px;
            background-color: white;
            border-radius: 10px;
            box-shadow: 0 0 10px rgba(0,0,0,0.1);
        }

        .navegacion-principal {
            background-color: #ddd590;
            padding: 15px;
            margin-bottom: 30px;
            border-radius: 5px;
            display: flex;
            justify-content: end;
            gap: 20px;
        }
        .contenido-cuenta h2 {
            color: #333;
            margin-bottom: 25px;
            text-align: center;
        }

        .contenido-cuenta h3 {
            color: #666;
            margin-top: 15px;
            margin-bottom: 5px;
        }

        .contenido-cuenta p {
            color: #333;
            margin-bottom: 20px;
            padding: 10px;
            background-color: #f9f9f9;
            border-radius: 5px;
        }

        .botones-container {
            display: flex;
            flex-wrap: wrap;
            gap: 15px;
            justify-content: center;
            margin-top: 30px;
        }

        .boton2 {
            margin: 0;
            flex: 0 1 auto;
        }

        .boton2 button {
            padding: 12px 25px;
            background-color: #ddd590;
            color: #333;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            transition: all 0.3s ease;
            font-size: 1em;
            white-space: nowrap;
        }

        .boton2 button:hover {
            background-color: #94cf70;
            transform: translateY(-2px);
            box-shadow: 0 2px 5px rgba(0,0,0,0.2);
        }

        .eliminar-cuenta button {
            background-color: #ff4444;
            color: white;
        }

        .eliminar-cuenta button:hover {
            background-color: #cc0000;
        }
    </style>
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

    <div class="contenido-cuenta">
        <h2><?php echo htmlspecialchars($nombres . " " . $apellidos); ?></h2>
        <h3>Email</h3>
        <p><?php echo htmlspecialchars($correo); ?></p>
        <h3>Teléfono</h3>
        <p><?php echo htmlspecialchars($telefono); ?></p>

        <div class="botones-container">
            <!-- Subir producto (solo si el rol es 3) -->
            <?php if ($rol == 3): ?>
                <form class="boton2" action="subir_producto_form.php" method="get">
                    <button type="submit">Subir producto</button>
                </form>
            <?php endif; ?>

            <!-- Eliminar producto (solo si el rol es 3) -->
            <?php if ($rol == 3): ?>
                <form class="boton2" action="eliminar_producto_form.php" method="get">
                    <button type="submit">Eliminar Producto</button>
                </form>
            <?php endif; ?>

            <!-- Exportar a Excel (solo si el rol es 1) -->
            <?php if ($rol == 1): ?>
                <form class="boton2" action="export.php" method="get">
                    <button type="submit">Exportar a Excel</button>
                </form>
            <?php endif; ?>

            <!-- Editar perfil -->
            <form class="boton2" action="editar_perfil.php" method="get">
                <button type="submit">Editar Perfil</button>
            </form>

            <!-- Eliminar cuenta -->
            <form class="boton2 eliminar-cuenta" method="POST" style="display: inline;" onsubmit="return confirm('¿Estás seguro de que deseas eliminar tu cuenta? Esta acción no se puede deshacer.');">
                <button type="submit" name="eliminar_cuenta">Eliminar Cuenta</button>
            </form>
        </div>
    </div>

    <!-- JavaScript para mostrar el alert -->
    <script>
        window.onload = function() {
            // Obtener el parámetro 'mensaje' de la URL
            const urlParams = new URLSearchParams(window.location.search);
            const mensaje = urlParams.get('mensaje');
            
            // Si hay un mensaje, mostrarlo en una alerta
            if (mensaje) {
                alert(mensaje); // Muestra el mensaje en un alert
            }
        };
    </script>
</body>
</html>
