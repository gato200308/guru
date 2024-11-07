<?php
session_start(); // Esto debe estar al principio
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
        /* Estilo para el contenedor de productos */
        .productos {
            display: grid;
            grid-template-columns: repeat(3, 1fr); /* 3 columnas */
            gap: 20px; /* Espacio entre los productos */
            margin-top: 20px; /* Espacio superior */
        }

        .producto {
            border-radius: 5px; /* Bordes redondeados */
            padding: 10px; /* Espaciado interno */
            text-align: center; /* Centrar texto */
        }

        .imagen-uniforme {
            max-width: 100%; /* Imagen se ajusta al contenedor */
            height: auto; /* Mantiene proporciones */
        }

        /* Estilos para la animación del título */
        h1 {
            opacity: 0; /* Inicialmente invisible */
            transition: opacity 1s ease; /* Transición para desvanecer */
        }
    </style>
</head>
<body>
    <header>
        <h1 id="titulo">Bienvenidos a Guru</h1>
        <nav class="navegacion-principal contenedor">
            <a href="portafolio.html">PORTAFOLIO</a>
            <a href="sesion.html">SESION</a>
            <a href="index.html">PRODUCTOS</a>
            <a href="contacto.html">CONTACTO</a>
            <a href="cuenta.php">Cuenta</a>
        </nav>
    </header>
    <main>
        <div class="producto">
            <h3>PRODUCTOS DESTACADOS</h3>
        </div>
        <form id="buscador-form">
            <div class="contenedor-busqueda"></div>
            <input type="text" id="barra-de-busqueda" placeholder="Buscar productos" required>
            <button type="submit" id="btn-buscar">BUSCAR</button>
        </form>
        <div class="productos" id="productos-container"></div>
    </main>
    <script>
        // Función para animar el título
        function animarTitulo() {
            const texto = "Bienvenidos a Guru";
            const tituloElement = document.getElementById('titulo');
            tituloElement.textContent = ''; // Limpiar el texto inicial
            let index = 0;

            const interval = setInterval(() => {
                if (index < texto.length) {
                    tituloElement.textContent += texto[index]; // Agregar letra
                    tituloElement.style.opacity = '1'; // Hacer visible
                    index++;
                } else {
                    clearInterval(interval);
                    // Dejar el texto visible al finalizar
                    tituloElement.style.opacity = '1'; // Mantener visible
                }
            }, 500); // Tiempo entre letras
        }

        // Cargar productos desde el archivo PHP
        function cargarProductos() {
            fetch('obtener_productos.php')
                .then(response => {
                    if (!response.ok) {
                        throw new Error('Error en la respuesta del servidor');
                    }
                    return response.json();
                })
                .then(productos => {
                    const contenedor = document.getElementById('productos-container');
                    contenedor.innerHTML = ''; // Limpiar contenedor antes de agregar nuevos productos

                    if (productos.error) {
                        console.error('Error al cargar los productos:', productos.error);
                        contenedor.innerHTML = '<p>No se pudieron cargar los productos.</p>'; // Mensaje de error
                        return;
                    }

                    productos.forEach(producto => {
                        const divProducto = document.createElement('div');
                        divProducto.className = 'producto'; // Asignar clase para estilos
                        divProducto.innerHTML = `
                            <img class='imagen-uniforme' src='${producto.imagen_url}' alt='${producto.nombre}'>
                            <h3>${producto.nombre}</h3>
                            <p>Precio: $${producto.precio}</p>
                            <div class='button1'><button>Añadir al carrito</button></div>
                        `;
                        contenedor.appendChild(divProducto);
                    });
                })
                .catch(error => {
                    console.error('Error al cargar los productos:', error);
                });
        }

        // Iniciar la animación del título al cargar la página
        document.addEventListener('DOMContentLoaded', () => {
            animarTitulo(); // Animar título
            cargarProductos(); // Cargar productos
        });

        // Función para filtrar productos
        document.getElementById('buscador-form').addEventListener('submit', function(e) {
            e.preventDefault(); // Prevenir el envío del formulario

            const query = document.getElementById('barra-de-busqueda').value.toLowerCase();
            const productos = document.querySelectorAll('.producto');

            productos.forEach((producto) => {
                const nombre = producto.querySelector('h3') ? producto.querySelector('h3').textContent.toLowerCase() : '';
                if (nombre.includes(query)) {
                    producto.style.display = ''; // Mostrar el producto
                } else {
                    producto.style.display = 'none'; // Ocultar el producto
                }
            });
        });
    </script>
    <footer>
        <p>&copy; 2024 Guru Sales</p>
    </footer>
</body>
</html>
