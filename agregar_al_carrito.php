<?php
session_start();

// Si el carrito no existe en la sesión, lo creamos
if (!isset($_SESSION['carrito'])) {
    $_SESSION['carrito'] = [];
}

// Verificar si se recibieron los datos del producto
if (isset($_POST['producto']) && isset($_POST['precio'])) {
    $producto = $_POST['producto'];
    $precio = $_POST['precio'];

    // Agregar el producto al carrito
    $_SESSION['carrito'][] = [
        'producto' => $producto,
        'precio' => $precio
    ];

    // Devolver una respuesta exitosa
    echo "success";
} else {
    // Si no se recibieron los datos necesarios, devolver error
    http_response_code(400);
    echo "error";
}
?>
