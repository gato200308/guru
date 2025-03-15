
<?php
session_start();

// Conectar a la base de datos
$conexion = new mysqli('localhost', 'root', '', 'guru');

// Verificar la conexión
if ($conexion->connect_error) {
    die("Conexión fallida: " . $conexion->connect_error);
}

// Crear tabla de historial si no existe
$sql = "CREATE TABLE IF NOT EXISTS historial_compras (
    id INT AUTO_INCREMENT PRIMARY KEY,
    fecha DATETIME,
    total DECIMAL(10,2),
    productos TEXT
)";
$conexion->query($sql);

if (isset($_SESSION['carrito']) && !empty($_SESSION['carrito'])) {
    // Calcular el total y preparar la lista de productos
    $totalCarrito = 0;
    $listaProductos = array();
    
    foreach ($_SESSION['carrito'] as $item) {
        $nombre_producto = $item['producto'];
        $query = "SELECT precio FROM productos WHERE nombre = '$nombre_producto'";
        $result = $conexion->query($query);
        
        if ($result->num_rows > 0) {
            $producto = $result->fetch_assoc();
            $totalCarrito += $producto['precio'];
            $listaProductos[] = $nombre_producto;
        }
    }
    
    // Guardar la compra en el historial
    $productosJson = json_encode($listaProductos);
    $fecha = date('Y-m-d H:i:s');
    $sql = "INSERT INTO historial_compras (fecha, total, productos) VALUES (?, ?, ?)";
    
    $stmt = $conexion->prepare($sql);
    $stmt->bind_param("sds", $fecha, $totalCarrito, $productosJson);
    $stmt->execute();
    
    // Limpiar el carrito
    unset($_SESSION['carrito']);
    
    // Redirigir a página de éxito
    header("Location: compra_exitosa.php");
} else {
    header("Location: carrito.php");
}

$conexion->close();
?>
