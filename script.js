document.addEventListener('DOMContentLoaded', function() {
    const barraBusqueda = document.getElementById('barra-de-busqueda');
    const btnBuscar = document.getElementById('btn-buscar');
    const productos = document.querySelectorAll('.imagen-uniforme'); // Selección de imágenes
  
    btnBuscar.addEventListener('click', () => {
      const terminoBusqueda = barraBusqueda.value.trim().toLowerCase(); // Trim para eliminar espacios en blanco al principio y al final
  
      productos.forEach(producto => {
        const tituloProducto = producto.closest('td').querySelector('h3');
  
        if (tituloProducto) {
          const textoTitulo = tituloProducto.textContent.toLowerCase(); // Obtener texto del título del producto
  
          if (textoTitulo.includes(terminoBusqueda)) {
            producto.classList.remove('oculto'); // Quitar clase "oculto" (si existe)
          } else {
            producto.classList.add('oculto'); // Agregar clase "oculto" para ocultar
          }
        }
      });
    });
  
    // Opcional: Agregar evento "input" a la barra de búsqueda para filtrar en tiempo real
    barraBusqueda.addEventListener('input', () => {
      btnBuscar.click(); // Simular clic en el botón para activar la búsqueda
    });
  });
  
