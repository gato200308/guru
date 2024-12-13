<?php
function obtenerProductoPorId($id) {
    // Conexión a la base de datos (ajusta los datos de tu base de datos)
    $conexion = new mysqli("localhost", "usuario", "contraseña", "nombre_base_datos");

    // Verificar la conexión
    if ($conexion->connect_error) {
        die("Conexión fallida: " . $conexion->connect_error);
    }

    // Preparar la consulta SQL
    $sql = "SELECT * FROM productos WHERE id = ?";
    
    // Preparar el statement
    if ($stmt = $conexion->prepare($sql)) {
        $stmt->bind_param("i", $id); // "i" indica que el parámetro es un entero
        $stmt->execute();
        
        // Obtener el resultado
        $resultado = $stmt->get_result();
        
        // Si se encuentra el producto, devolverlo
        if ($producto = $resultado->fetch_assoc()) {
            return $producto;
        } else {
            return null; // Si no se encuentra el producto, devolver null
        }
        
        // Cerrar el statement
        $stmt->close();
    } else {
        echo "Error en la consulta: " . $conexion->error;
    }

    // Cerrar la conexión
    $conexion->close();
}
?>
