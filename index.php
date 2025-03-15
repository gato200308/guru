<?php
session_start(); // Asegúrate de iniciar la sesión al principio del archivo

// Si el carrito no existe en la sesión, lo creamos como un arreglo vacío
if (!isset($_SESSION['carrito'])) {
    $_SESSION['carrito'] = [];
}

// Función para agregar producto al carrito
if (isset($_POST['add_to_cart'])) {
    $producto = $_POST['producto'];
    $precio = $_POST['precio'];

    // Agregar el producto al carrito
    $_SESSION['carrito'][] = ['producto' => $producto, 'precio' => $precio];
}
// Conexión a la base de datos
$conexion = new mysqli('localhost', 'root', '', 'guru');
if ($conexion->connect_error) {
    die('Error de conexión: ' . $conexion->connect_error);
}

// Consulta para obtener los productos
$productos = [];
$resultado = $conexion->query("SELECT vendedor_id, nombre, imagen_url, precio FROM productos");
if ($resultado && $resultado->num_rows > 0) {
    while ($row = $resultado->fetch_assoc()) {
        $productos[] = $row;
    }
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>PRODUCTOS</title>
    <link rel="stylesheet" href="styles.css">
    <link rel="icon" href="imagenes/icono app2.jpg" type="image/x-icon">
    <style>
        /* Estilos del loader */
        #loader {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background-color: #ddd590;
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            z-index: 1000;
        }
        #loader img.logo {
            max-width: 300px;
            margin: 20px;
        }
        #loader img.gif {
            max-width: 300px;
            margin: 20px;
        }
        .productos {
            display: grid;
            grid-template-columns: repeat(3, 1fr);
            gap: 20px;
            margin-top: 20px;
        }
        .producto {
            border-radius: 5px;
            padding: 10px;
            text-align: center;
        }
        .imagen-uniforme {
            max-width: 100%;
            height: auto;
        }
        h1 {
            opacity: 0;
            transition: opacity 2s ease;
        }
        .notificacion {
            position: fixed;
            bottom: 20px;
            right: 20px;
            background-color: #94cf70;
            color: white;
            padding: 15px 25px;
            border-radius: 5px;
            box-shadow: 0 2px 5px rgba(0,0,0,0.2);
            opacity: 0;
            transform: translateY(20px);
            transition: all 0.3s ease;
            z-index: 1000;
        }

        .notificacion.mostrar {
            opacity: 1;
            transform: translateY(0);
        }
    </style>
</head>
<body>
    <!-- Loader -->
    <div id="loader">
        <img src="imagenes/letras Berlin Sans FB.png" alt="Logo de carga" class="logo">
        <img src="imagenes/negrogif.gif" alt="Cargando" class="gif">
    </div>

    <header>
        <h1 id="titulo">Bienvenidos a Guru</h1>
        <nav class="navegacion-principal contenedor">
            <a href="sesion.html">SESION</a>
            <a href="index.php">PRODUCTOS</a>
            <a href="contacto.html">CONTACTO</a>
            <a href="cuenta.php">CUENTA</a>
            <a href="historial_compras.php">HISTORIAL</a>
            <a href="carrito.php" class="carrito-enlace">
                <img src="data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAABoAAAAaCAYAAACpSkzOAAAACXBIWXMAAAsTAAALEwEAmpwYAAABV0lEQVR4nLWVTyuEURTGf0WhNFnIQgk12SmfwG4s2NhIWVPzBSSysGSrrHwAErMQCzUrY2cpFnaz1EiRKfl3dfWot0lzz33feZ86m3PPeX63+77nXoAa4BLxDtwCS3RYtRbQX3wDM+SobmBTsDNy1iDwCbwB/YFap0itKxnM5w1al8F+3qBJGXwAXy1mLhDJujpQCsHqbQycEeSjGgLtqfAy5dFtae0iBJpVof8DhyJBXcC91sohUC/QVPFyJGhB+QegD4NO1XBOnK7Vt2FtKKvBD2/B2FNSzwswYAWNJI5n0dhTVf0OkbpR44GhdkoXsj+B4VjQtkDPQE+g9sh4o/yracOQukT4cZhI+3Q8GSFNYJUMOpTRWhYTi+YEetUw+mHOTbsR3+kRKGaBrQB3ej7agRpZQeNABTgBxgz51Kokdu1NQ/mOgI4N+dTyx+J37M1GDflf/QC6iamAjtlFMgAAAABJRU5ErkJggg==" alt="Carrito" title="Ver carrito">
            </a>
        </nav>
    </header>

    <main>
        <div class="producto">
            <h3>PRODUCTOS DESTACADOS</h3>
        </div>
        <form id="buscador-form">
            <input type="text" id="barra-de-busqueda" placeholder="Buscar productos" oninput="buscarProductos(this.value)" required>
            <button type="submit" id="btn-buscar">BUSCAR</button>
        </form>
        <div class="productos" id="productos-container"></div>
    </main>

    <div id="notificacion" class="notificacion"></div>

    <script>
        // Variable para almacenar todos los productos
        let todosLosProductos = [];

        function mostrarLoader() {
            document.getElementById('loader').style.display = 'flex';
        }

        function ocultarLoader() {
            document.getElementById('loader').style.display = 'none';
        }

        function animarTitulo() {
            const texto = "Bienvenidos a Guru";
            const tituloElement = document.getElementById('titulo');
            tituloElement.textContent = '';
            let index = 0;

            const interval = setInterval(() => {
                if (index < texto.length) {
                    tituloElement.textContent += texto[index];
                    tituloElement.style.opacity = '1';
                    index++;
                } else {
                    clearInterval(interval);
                    tituloElement.style.opacity = '1';
                }
            }, 100);
        }

        function buscarProductos(query) {
            query = query.toLowerCase().trim();
            const productos = document.querySelectorAll('.producto');

            productos.forEach((producto) => {
                const nombre = producto.querySelector('h3') ? producto.querySelector('h3').textContent.toLowerCase() : '';
                if (query === '' || nombre.includes(query)) {
                    producto.style.display = '';
                } else {
                    producto.style.display = 'none';
                }
            });
        }

        function cargarProductos() {
            mostrarLoader();
            fetch('obtener_productos.php')
                .then(response => {
                    if (!response.ok) {
                        throw new Error('Error en la respuesta del servidor');
                    }
                    return response.json();
                })
                .then(productos => {
                    todosLosProductos = productos;
                    const contenedor = document.getElementById('productos-container');
                    contenedor.innerHTML = '';

                    productos.forEach(producto => {
                        const divProducto = document.createElement('div');
                        divProducto.className = 'producto';
                        divProducto.innerHTML = `
                            <img class='imagen-uniforme' src='${producto.imagen_url}' alt='${producto.nombre}'>
                            <h3>${producto.nombre}</h3>
                            <p>Precio: $${producto.precio}</p>
                            <form method="POST" class="add-to-cart-form" onsubmit="return agregarAlCarrito(event)">
                                <input type="hidden" name="producto" value="${producto.nombre}">
                                <input type="hidden" name="precio" value="${producto.precio}">
                                <button type="submit" name="add_to_cart">Añadir al carrito</button>
                            </form>
                        `;
                        contenedor.appendChild(divProducto);
                    });
                    ocultarLoader();
                })
                .catch(error => {
                    console.error('Error al cargar los productos:', error);
                    ocultarLoader();
                });
        }

        function mostrarNotificacion(mensaje) {
            const notificacion = document.getElementById('notificacion');
            notificacion.textContent = mensaje;
            notificacion.classList.add('mostrar');
            
            setTimeout(() => {
                notificacion.classList.remove('mostrar');
            }, 2000);
        }

        function agregarAlCarrito(event) {
            event.preventDefault();
            mostrarLoader();
            
            const form = event.target;
            const formData = new FormData(form);

            fetch('agregar_al_carrito.php', {
                method: 'POST',
                body: formData
            })
            .then(response => response.text())
            .then(data => {
                ocultarLoader();
                mostrarNotificacion('Producto añadido al carrito');
            })
            .catch(error => {
                console.error('Error:', error);
                ocultarLoader();
                mostrarNotificacion('Error al añadir el producto');
            });

            return false;
        }

        document.addEventListener('DOMContentLoaded', () => {
            animarTitulo();
            cargarProductos();
        });

        // Prevenir el envío del formulario
        document.getElementById('buscador-form').addEventListener('submit', function(e) {
            e.preventDefault();
            buscarProductos(document.getElementById('barra-de-busqueda').value);
        });

        // Estilos adicionales
        const style = document.createElement('style');
        style.textContent = `
            .no-resultados {
                text-align: center;
                padding: 20px;
                color: #666;
                font-size: 1.1em;
                background-color: #f9f9f9;
                border-radius: 5px;
                margin: 20px 0;
            }

            #barra-de-busqueda {
                padding: 10px;
                border: 2px solid #ddd590;
                border-radius: 5px;
                width: 300px;
                font-size: 1em;
                transition: all 0.3s ease;
            }

            #barra-de-busqueda:focus {
                outline: none;
                border-color: #94cf70;
                box-shadow: 0 0 5px rgba(148, 207, 112, 0.3);
            }

            #btn-buscar {
                padding: 10px 20px;
                background-color: #ddd590;
                border: none;
                border-radius: 5px;
                color: #333;
                cursor: pointer;
                transition: all 0.3s ease;
            }

            #btn-buscar:hover {
                background-color: #94cf70;
                transform: translateY(-2px);
            }

            .add-to-cart-form button {
                background-color: #ddd590;
                color: #333;
                border: none;
                padding: 10px 20px;
                border-radius: 5px;
                cursor: pointer;
                transition: all 0.3s ease;
            }

            .add-to-cart-form button:hover {
                background-color: #94cf70;
                transform: translateY(-2px);
            }
        `;
        document.head.appendChild(style);
    </script>
    <footer>
        <p>&copy; 2024 Guru Sales
    </footer>
</body>
</html>
