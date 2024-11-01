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
    while ($row = $result->fetch_assoc()) {
        // Imprimir cada producto
        echo '<td>';
        echo '<img class="imagen-uniforme" src="imagenes/productos/' . htmlspecialchars($row["imagen"]) . '" alt="' . htmlspecialchars($row["nombre"]) . '">';
        echo '<h3>' . htmlspecialchars($row["nombre"]) . '</h3>';
        echo '<p>Precio: $' . htmlspecialchars($row["precio"]) . '</p>';
        echo '<div class="button1"><button>Añadir al carrito</button></div>';
        echo '</td>';
    }
} else {
    echo "<p>No hay productos disponibles.</p>";
}

// Cierra la conexión
$conn->close();
?>
