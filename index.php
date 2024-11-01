<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Productos</title>
    <link rel="stylesheet" href="styles.css">
    <link rel="icon" href="imagenes/icono app2.jpg" type="image/x-icon">
</head>
<body>
    <header>
        <h1>Bienvenidos a Guru</h1>
        <nav class="navegacion-principal contenedor">
            <a href="portafolio.html">PORTAFOLIO</a>
            <a href="sesion.html">SESION</a>
            <a href="index.php">PRODUCTOS</a>
            <a href="contacto.html">CONTACTO</a>
            <a href="cuenta.php">Cuenta</a>
        </nav>
    </header>
    <main>
        <div class="producto">
            <h3>Productos destacados</h3>
        </div>
        <form id="buscador-form">
            <div class="contenedor-busqueda"></div>
            <input type="text" id="barra-de-busqueda" placeholder="Buscar productos" required>
            <button type="submit" id="btn-buscar">Buscar</button>
        </form>
        <div class="productos">
            <table id="tabla-productos">
                <?php
                // Conectar a la base de datos
                $conn = new mysqli('localhost', 'root', '', 'Guru');

                // Comprobar la conexión
                if ($conn->connect_error) {
                    die("Connection failed: " . $conn->connect_error);
                }

                // Consulta para obtener los productos
                $sql = "SELECT * FROM productos";
                $result = $conn->query($sql);

                // Verificar si hay resultados
                if ($result->num_rows > 0) {
                    // Salida de cada fila
                    while($row = $result->fetch_assoc()) {
                        echo "<tr>
                                <td>
                                    <img class='imagen-uniforme' src='{$row['imagen_url']}' alt='{$row['nombre']}'>
                                    <h3>{$row['nombre']}</h3>
                                    <p>Precio: \${$row['precio']}</p>
                                    <div class='button1'><button>Añadir al carrito</button></div>
                                </td>
                            </tr>";
                    }
                } else {
                    echo "<tr><td colspan='3'>No hay productos disponibles.</td></tr>";
                }

                $conn->close();
                ?>
            </table>
        </div>
    </main>
    <script src="script.js"></script>
    <footer>
        <p>&copy; 2024 Guru Sales</p>
    </footer>
</body>
</html>
