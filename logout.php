<?php
session_start();
session_destroy(); // Destruye todas las sesiones
header("Location: conexion.php"); // Redirige a la página de inicio de sesión
exit();
?>
