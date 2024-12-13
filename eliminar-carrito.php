<?php
session_start();
unset($_SESSION['carrito']); // Vaciar el carrito
header('Location: index.php'); // Redirigir al carrito vacío
exit();
?>
