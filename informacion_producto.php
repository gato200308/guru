<?php
session_start();

// Verificar si se ha recibido un ID de producto
if (isset($_GET['id'])) {
    $producto_id = $_GET['id'];

    // Obtener el producto desde la base de datos (deberías definir esta función)
    $producto = obtenerProductoPorId($producto_id); // Asegúrate de crear esta función para obtener el producto

    if (!$producto) {
        echo "Producto no encontrado.";
        exit();
    }
} else {
    echo "ID de producto no válido.";
    exit();
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Detalle del Producto</title>
    <link rel="stylesheet" href="styles.css">
    <style>
        /* Estilos básicos para el modal */
        .modal {
            display: none;
            position: fixed;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
            background-color: white;
            padding: 20px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2);
            z-index: 1000;
            max-width: 500px;
            width: 90%;
        }
        .modal.active {
            display: block;
        }
        .modal-overlay {
            display: none;
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: rgba(0, 0, 0, 0.5);
            z-index: 999;
        }
        .modal-overlay.active {
            display: block;
        }
    </style>
</head>
<body>
    <header>
        <h1>Detalles del Producto</h1>
        <nav>
            <a href="index.php">Productos</a>
            <a href="carrito.php">Ver Carrito</a>
        </nav>
    </header>

    <main>
        <div class="producto-detalle">
            <img src="<?php echo $producto['imagen_url']; ?>" alt="<?php echo $producto['nombre']; ?>" class="imagen-detalle">
            <h2><?php echo $producto['nombre']; ?></h2>
            <p><?php echo $producto['descripcion']; ?></p>
            <p>Precio: $<?php echo $producto['precio']; ?></p>
            <a href="carrito.php?id=<?php echo $producto['id']; ?>">Añadir al carrito</a>
            <!-- Botón para abrir el modal -->
            <button id="verDetalles">Ver Detalles</button>
        </div>

        <!-- Modal para mostrar los detalles -->
        <div class="modal-overlay" id="modalOverlay"></div>
        <div class="modal" id="modal">
            <h2 id="modalTitulo"></h2>
            <img id="modalImagen" src="" alt="" class="imagen-detalle">
            <p id="modalDescripcion"></p>
            <p id="modalPrecio"></p>
            <button id="cerrarModal">Cerrar</button>
        </div>
    </main>

    <script>
        // Variables de los elementos
        const modal = document.getElementById('modal');
        const modalOverlay = document.getElementById('modalOverlay');
        const verDetalles = document.getElementById('verDetalles');
        const cerrarModal = document.getElementById('cerrarModal');

        // Detalles dinámicos del modal
        const modalTitulo = document.getElementById('modalTitulo');
        const modalImagen = document.getElementById('modalImagen');
        const modalDescripcion = document.getElementById('modalDescripcion');
        const modalPrecio = document.getElementById('modalPrecio');

        // Evento para abrir el modal
        verDetalles.addEventListener('click', () => {
            modalTitulo.textContent = "<?php echo $producto['nombre']; ?>";
            modalImagen.src = "<?php echo $producto['imagen_url']; ?>";
            modalDescripcion.textContent = "<?php echo $producto['descripcion']; ?>";
            modalPrecio.textContent = "Precio: $<?php echo $producto['precio']; ?>";

            modal.classList.add('active');
            modalOverlay.classList.add('active');
        });

        // Evento para cerrar el modal
        cerrarModal.addEventListener('click', () => {
            modal.classList.remove('active');
            modalOverlay.classList.remove('active');
        });

        // Cerrar el modal al hacer clic fuera de él
        modalOverlay.addEventListener('click', () => {
            modal.classList.remove('active');
            modalOverlay.classList.remove('active');
        });
    </script>
</body>
</html>
