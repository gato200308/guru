// Función para cargar productos desde el archivo PHP
function cargarProductos() {
    fetch('obtener_productos.php')
        .then(response => response.json())
        .then(productos => {
            const contenedor = document.getElementById('productos-container');
            contenedor.innerHTML = ''; // Limpiar contenedor antes de agregar nuevos productos

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
        .catch(error => console.error('Error al cargar los productos:', error));
}

// Cargar productos al cargar la página
document.addEventListener('DOMContentLoaded', cargarProductos);

// Función para filtrar productos
document.getElementById('barra-de-busqueda').addEventListener('input', function() {
    const query = this.value.toLowerCase(); // Obtener el valor de la barra de búsqueda
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
