const PRODUCTOS_API = "servicios/publico/producto.php"

// Constante tipo objeto para obtener los parámetros disponibles en la URL.
const PARAMS = new URLSearchParams(location.search);
// Método del evento para cuando el documento ha cargado.
document.addEventListener('DOMContentLoaded', async () => {
    loadTemplate();
    // Constante tipo objeto con los datos del producto seleccionado.
    const FORM = new FormData();
    FORM.append('id_producto', PARAMS.get('id'));
    // Petición para solicitar los datos del producto seleccionado.
    const DATA = await fetchData(PRODUCTOS_API, 'readOne', FORM);
    // Se comprueba si la respuesta es satisfactoria, de lo contrario se muestra un mensaje con la excepción.
    if (DATA.status) {
        // Se colocan los datos en la página web de acuerdo con el producto seleccionado previamente.
        document.getElementById('imagenProducto').src = SERVER_URL.concat('imagenes/productos/', DATA.dataset.imagen);
        document.getElementById('nombreProducto').textContent = DATA.dataset.nombre_producto;
        document.getElementById('descripcionProducto').textContent = DATA.dataset.descripcion;
        document.getElementById('precioProducto').textContent = DATA.dataset.precio_unitario;
        document.getElementById('existenciasProducto').textContent = DATA.dataset.CANTIDAD;
        document.getElementById('idProducto').value = DATA.dataset.id_producto;
        document.getElementById('producto').value = DATA.dataset.id_producto;
    } else {
        sweetAlert(2, DATA.error, null);
    }
});


function validarCantidad(input) {
    // Obtener el valor ingresado como un número entero
    var valor = parseInt(input.value);
    // Verificar si el campo está vacío
    if (input.value === "") {
        // Establecer el valor predeterminado en 1
        input.value = 1;
    }
    // Verificar si el valor es negativo
    if (valor < 1) {
        // Si es negativo, establecer el valor como 1
        input.value = 1;
    }
}