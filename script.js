document.getElementById('buscador-form').addEventListener('submit', function(e) {
    e.preventDefault(); // Prevenir el envío del formulario

    const query = document.getElementById('barra-de-busqueda').value.toLowerCase();
    const productos = document.querySelectorAll('#tabla-productos tr');

    productos.forEach((producto) => {
        const nombre = producto.querySelector('h3') ? producto.querySelector('h3').textContent.toLowerCase() : '';
        if (nombre.includes(query)) {
            producto.style.display = ''; // Mostrar el producto
        } else {
            producto.style.display = 'none'; // Ocultar el producto
        }
    });
});
