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
    die("Conexión fallida: " . $conn->connect_error);
}

// Obtener la información del usuario
$identificacion = $_SESSION['identificacion'];
$stmt = $conn->prepare("SELECT nombres, apellidos, correo, telefono, genero FROM usuario WHERE identificacion = ?");
$stmt->bind_param("s", $identificacion);
$stmt->execute();
$stmt->store_result();

if ($stmt->num_rows > 0) {
    $stmt->bind_result($nombres, $apellidos, $correo, $telefono, $genero);
    $stmt->fetch();
} else {
    echo "No se encontraron datos para el usuario.";
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
    <title>Editar Perfil</title>
    <style>
        .perfil-container {
            max-width: 600px;
            margin: 40px auto;
            padding: 30px;
            background-color: white;
            border-radius: 10px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
        }

        .perfil-header {
            text-align: center;
            margin-bottom: 30px;
            color: #333;
        }

        .perfil-header h2 {
            color: #333;
            font-size: 2em;
            margin-bottom: 10px;
        }

        .form-group {
            margin-bottom: 20px;
        }

        .form-group label {
            display: block;
            margin-bottom: 8px;
            color: #555;
            font-weight: 500;
        }

        .form-group input,
        .form-group select {
            width: 100%;
            padding: 12px;
            border: 2px solid #ddd590;
            border-radius: 5px;
            font-size: 16px;
            transition: all 0.3s ease;
        }

        .form-group input:focus,
        .form-group select:focus {
            outline: none;
            border-color: #94cf70;
            box-shadow: 0 0 5px rgba(148, 207, 112, 0.3);
        }

        .btn-actualizar {
            background-color: #ddd590;
            color: #333;
            border: none;
            padding: 15px 30px;
            border-radius: 5px;
            cursor: pointer;
            width: 100%;
            font-size: 16px;
            font-weight: 600;
            transition: all 0.3s ease;
            margin-top: 20px;
        }

        .btn-actualizar:hover {
            background-color: #94cf70;
            transform: translateY(-2px);
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

        

        .notificacion {
            position: fixed;
            bottom: 20px;
            right: 20px;
            background-color: #94cf70;
            color: white;
            padding: 15px 25px;
            border-radius: 5px;
            box-shadow: 0 2px 5px rgba(0,0,0,0.2);
            opacity: 0;
            transform: translateY(20px);
            transition: all 0.3s ease;
            z-index: 1000;
        }

        .notificacion.mostrar {
            opacity: 1;
            transform: translateY(0);
        }
    </style>
</head>
<body>
    <nav class="navegacion-principal">
        <a href="index.html">INICIO</a>
        <a href="cuenta.php">CUENTA</a>
        <a href="cerrar_sesion.php">CERRAR SESIÓN</a>
    </nav>

    <div class="perfil-container">
        <div class="perfil-header">
            <h2>Editar Perfil</h2>
            <p>Actualiza tu información personal</p>
        </div>
        

        <form action="actualizar_perfil.php" method="POST" id="form-perfil">
            <input type="hidden" name="identificacion" value="<?php echo htmlspecialchars($identificacion); ?>">

            <div class="form-group">
                <label for="nombres">Nombres:</label>
                <input type="text" id="nombres" name="nombres" value="<?php echo htmlspecialchars($nombres); ?>" required>
            </div>

            <div class="form-group">
                <label for="apellidos">Apellidos:</label>
                <input type="text" id="apellidos" name="apellidos" value="<?php echo htmlspecialchars($apellidos); ?>" required>
            </div>

            <div class="form-group">
                <label for="correo">Correo Electrónico:</label>
                <input type="email" id="correo" name="correo" value="<?php echo htmlspecialchars($correo); ?>" required>
            </div>

            <div class="form-group">
                <label for="telefono">Teléfono:</label>
                <input type="tel" id="telefono" name="telefono" value="<?php echo htmlspecialchars($telefono); ?>" required>
            </div>

            <div class="form-group">
                <label for="genero">Género:</label>
                <select id="genero" name="genero" required>
                    <option value="Femenino" <?php echo ($genero == 'Femenino') ? 'selected' : ''; ?>>Femenino</option>
                    <option value="Masculino" <?php echo ($genero == 'Masculino') ? 'selected' : ''; ?>>Masculino</option>
                    <option value="Prefiero no decirlo" <?php echo ($genero == 'Prefiero no decirlo') ? 'selected' : ''; ?>>Prefiero no decirlo</option>
                    <option value="Otro" <?php echo ($genero == 'Otro') ? 'selected' : ''; ?>>Otro</option>
                </select>
            </div>

            <button type="submit" class="btn-actualizar">Actualizar Información</button>
        </form>
    </div>

    <div id="notificacion" class="notificacion"></div>

    <script>
        function mostrarNotificacion(mensaje) {
            const notificacion = document.getElementById('notificacion');
            notificacion.textContent = mensaje;
            notificacion.classList.add('mostrar');
            
            setTimeout(() => {
                notificacion.classList.remove('mostrar');
            }, 2000);
        }

        document.getElementById('form-perfil').addEventListener('submit', function(e) {
            e.preventDefault();
            
            // Crear FormData con los datos del formulario
            const formData = new FormData(this);

            // Enviar los datos mediante fetch
            fetch('actualizar_perfil.php', {
                method: 'POST',
                body: formData
            })
            .then(response => response.text())
            .then(data => {
                mostrarNotificacion('Perfil actualizado correctamente');
                setTimeout(() => {
                    window.location.href = 'cuenta.php';
                }, 2000);
            })
            .catch(error => {
                console.error('Error:', error);
                mostrarNotificacion('Error al actualizar el perfil');
            });
        });
    </script>
</body>
</html>
