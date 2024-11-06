<?php
// Iniciar sesión
session_start();

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

// Consulta para obtener los productos
$sql = "SELECT id, nombre, precio, imagen FROM productos"; // Asegúrate de que 'productos' es el nombre de tu tabla
$result = $conn->query($sql);

// Verifica si hay productos
if ($result->num_rows > 0) {
    echo '<table>'; // Asegúrate de envolver los productos en una tabla o un contenedor adecuado
    while ($row = $result->fetch_assoc()) {
        // Formatear el precio sin decimales
        $precioFormateado = number_format($row["precio"], 0, '.', ''); // Esto elimina los decimales
        // Imprimir cada producto
        echo '<td>';
        echo '<img class="imagen-uniforme" src="imagenes/productos/' . htmlspecialchars($row["imagen"]) . '" alt="' . htmlspecialchars($row["nombre"]) . '">';
        echo '<h3>' . htmlspecialchars($row["nombre"]) . '</h3>';
        echo '<p>Precio: $' . $precioFormateado . '</p>'; // Usar el precio formateado aquí
        echo '<div class="button1"><button>Añadir al carrito</button></div>';
        echo '</td>';
    }
    echo '</table>'; // Cerrar la tabla
} else {
    echo "<p>No hay productos disponibles.</p>";
}

// Cierra la conexión
$conn->close();
?>
