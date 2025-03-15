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

        .modal-confirmacion {
            display: none;
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background-color: rgba(0, 0, 0, 0.5);
            z-index: 1000;
            justify-content: center;
            align-items: center;
        }

        .modal-contenido {
            background-color: white;
            padding: 30px;
            border-radius: 10px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.2);
            max-width: 400px;
            width: 90%;
            text-align: center;
            transform: translateY(-20px);
            opacity: 0;
            transition: all 0.3s ease;
        }

        .modal-confirmacion.mostrar .modal-contenido {
            transform: translateY(0);
            opacity: 1;
        }

        .modal-confirmacion h3 {
            color: #333;
            margin-bottom: 20px;
        }

        .modal-confirmacion p {
            color: #666;
            margin-bottom: 25px;
        }

        .botones-confirmacion {
            display: flex;
            justify-content: center;
            gap: 15px;
        }

        .btn-confirmar, .btn-cancelar {
            padding: 12px 25px;
            border-radius: 5px;
            cursor: pointer;
            border: none;
            font-weight: 500;
            transition: all 0.3s ease;
        }

        .btn-confirmar {
            background-color: #ff6b6b;
            color: white;
        }

        .btn-confirmar:hover {
            background-color: #ff5252;
            transform: translateY(-2px);
        }

        .btn-cancelar {
            background-color: #ddd590;
            color: #333;
        }

        .btn-cancelar:hover {
            background-color: #94cf70;
            transform: translateY(-2px);
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
            <button class="boton2 eliminar-cuenta" onclick="mostrarConfirmacion()">Eliminar Cuenta</button>
        </div>
    </div>

    <div class="modal-confirmacion" id="modal-eliminar">
        <div class="modal-contenido">
            <h3>¿Estás seguro?</h3>
            <p>Esta acción eliminará permanentemente tu cuenta y todos tus datos. Esta acción no se puede deshacer.</p>
            <div class="botones-confirmacion">
                <button class="btn-cancelar" onclick="cerrarModal()">Cancelar</button>
                <button class="btn-confirmar" onclick="eliminarCuenta()">Eliminar Cuenta</button>
            </div>
        </div>
    </div>

    <script>
        function mostrarConfirmacion() {
            const modal = document.getElementById('modal-eliminar');
            modal.style.display = 'flex';
            setTimeout(() => {
                modal.classList.add('mostrar');
            }, 10);
        }

        function cerrarModal() {
            const modal = document.getElementById('modal-eliminar');
            modal.classList.remove('mostrar');
            setTimeout(() => {
                modal.style.display = 'none';
            }, 300);
        }

        function eliminarCuenta() {
            window.location.href = 'eliminar_cuenta.php';
        }

        // Cerrar modal si se hace clic fuera de él
        window.onclick = function(event) {
            const modal = document.getElementById('modal-eliminar');
            if (event.target == modal) {
                cerrarModal();
            }
        }
    </script>

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
