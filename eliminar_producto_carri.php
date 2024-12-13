<?php
session_start();

// Verificar si el parámetro 'producto' está presente en la URL
if (isset($_GET['producto'])) {
    $producto_a_eliminar = $_GET['producto'];

    // Verificar si el carrito está vacío
    if (isset($_SESSION['carrito']) && !empty($_SESSION['carrito'])) {
        // Buscar y eliminar el producto en el carrito
        foreach ($_SESSION['carrito'] as $key => $item) {
            if ($item['producto'] == $producto_a_eliminar) {
                // Eliminar el producto del carrito
                unset($_SESSION['carrito'][$key]);
                // Reindexar el array del carrito para evitar índices faltantes
                $_SESSION['carrito'] = array_values($_SESSION['carrito']);
                break;
            }
        }
    }
}

// Redirigir de nuevo al carrito
header('Location: carrito.php'); // Asegúrate de que esta sea la página donde muestras el carrito
exit();
?>
