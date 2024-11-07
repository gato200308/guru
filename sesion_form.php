<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="styles.css">
    <link rel="icon" href="imagenes/icono app2.jpg" type="image/x-icon">
    <title>INICIO DE SESIÓN</title>
</head>

<body style="background-color: #ffffff;">

    <header>
        <div class="banner-content">
            <div class="logo">
                <img src="imagenes/logotipo.png" alt="Logo">
            </div>
        </div>

        <nav class="navegacion-principal contenedor">
            <a href="index.html">PRODUCTO</a>
            <a href="registro.html">REGÍSTRAME</a>
            <a href="cuenta.php">CUENTA</a>
        </nav>
    </header>

    <div class="left-column">
        <div class="agenda">
            <h1>Formulario de inicio de sesión</h1>
            
            <!-- Mostrar el mensaje de error si existe -->
            <?php if (!empty($error)): ?>
                <div style="color: black; margin-bottom: 10px;"><?= $error; ?></div>
            <?php endif; ?>
            
            <form action="sesion.php" method="post">
                <label for="identificacion">Identificación:</label>
                <input type="text" id="identificacion" name="identificacion" required>
                
                <label for="correo">Correo Electrónico:</label>
                <input type="email" id="correo" name="correo" required>
        
                <label for="contrasena">Contraseña:</label>
                <input type="password" id="contrasena" name="contrasena" required>
                
                <button type="submit">Iniciar sesión</button>
            </form>
        </div>
    </div>

    <footer>
        <p>&copy; 2024 Guru Sales</p>
    </footer>
    
</body>
</html>
