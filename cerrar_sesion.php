<?php
// Iniciar la sesión
session_start();

// Destruir la sesión
session_unset();
session_destroy();

// Redirigir al usuario a la página de inicio de sesión
header("Location: sesion.html");
exit();
?>
