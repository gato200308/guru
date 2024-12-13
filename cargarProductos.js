productos.forEach(producto => {
    const divProducto = document.createElement('div');
    divProducto.className = 'producto';
    divProducto.innerHTML = `
        <a href="detalle_producto.php?id=${producto.id}">
            <img class='imagen-uniforme' src='${producto.imagen_url}' alt='${producto.nombre}'>
        </a>
        <h3>${producto.nombre}</h3>
        <p>Precio: $${producto.precio}</p>
        <form method="POST" action="">
            <input type="hidden" name="producto" value="${producto.nombre}">
            <input type="hidden" name="precio" value="${producto.precio}">
            <button type="submit" name="add_to_cart">Añadir al carrito</button>
        </form>
    `;
    contenedor.appendChild(divProducto);
});
