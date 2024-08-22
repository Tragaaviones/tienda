const PRODUCTOS_API = "servicios/publico/producto.php"
const PEDIDOS_API = "servicios/publico/pedido.php"
const COMENTARIOS_API = "servicios/publico/comentarios.php"
// Constante tipo objeto para obtener los parámetros disponibles en la URL.
const PARAMS = new URLSearchParams(location.search);

// Constante para establecer el formulario de agregar un producto al carrito de compras.
const SHOPPING_FORM = document.getElementById('shoppingForm'),
    COMENTARIO_FORM = document.getElementById('saveForm'),
    ID_COMENTARIO = document.getElementById('idComentario'),
    COMENTARIO = document.getElementById('comentario'),
    VALORACION = document.getElementById('valoracion');


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
        document.getElementById('imagen_producto').src = SERVER_URL.concat('imagenes/productos/', DATA.dataset.imagen);
        document.getElementById('nombre_producto').textContent = DATA.dataset.nombre_producto;
        document.getElementById('descripcion_producto').textContent = DATA.dataset.descripcion;
        document.getElementById('precio_producto').textContent = DATA.dataset.precio_unitario;
        document.getElementById('existenciasProducto').textContent = DATA.dataset.stock_producto;
        document.getElementById('id_producto').value = DATA.dataset.id_producto;
        document.getElementById('producto').value = DATA.dataset.id_producto;
        document.getElementById('idProducto').value = DATA.dataset.id_producto;
    } else {
        sweetAlert(2, DATA.error, null);
    }
    console.log(document.getElementById('idProducto').value);
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
    if (valor <= 1) {
        // Si es negativo, establecer el valor como 1
        input.value = 1;
    }
}


// Método del evento para cuando se envía el formulario de agregar un producto al carrito.
SHOPPING_FORM.addEventListener('submit', async (event) => {
    // Se evita recargar la página web después de enviar el formulario.
    event.preventDefault();
    // Constante tipo objeto con los datos del formulario.
    const FORM = new FormData(SHOPPING_FORM);
    // Petición para guardar los datos del formulario.
    const DATA = await fetchData(PEDIDOS_API, 'manipulateDetail', FORM);
    // Se comprueba si la respuesta es satisfactoria, de lo contrario se constata si el cliente ha iniciado sesión.
    if (DATA.status) {
        sweetAlert(1, DATA.message, false, 'carrito_compra.html');
    } else if (DATA.session) {
        sweetAlert(2, DATA.error, false);
    } else {
        sweetAlert(3, DATA.error, true, 'login.html');
    }
});

// Método del evento para cuando se envía el formulario de agregar un producto al carrito.
COMENTARIO_FORM.addEventListener('submit', async (event) => {
    // Se evita recargar la página web después de enviar el formulario.
    event.preventDefault();

    // Constante tipo objeto con los datos del formulario.
    const FORM = new FormData(COMENTARIO_FORM);

    // Capturar la calificación seleccionada
    const VALORACION = document.querySelector('input[name="star"]:checked');
    if (VALORACION) {
        // Agregar la calificación al objeto FormData
        FORM.append('VALORACION', VALORACION.id.replace('star', ''));
    } else {
        // Si no se seleccionó ninguna calificación, puedes manejarlo aquí
        sweetAlert(3, "Por favor selecciona una calificación.", true);
        return;
    }

    console.log(ID_COMENTARIO.value);
    
    // Se verifica la acción a realizar.
    (ID_COMENTARIO.value) ? action = 'updateRow' : action = 'createRow';
    
    // Petición para guardar los datos del formulario.
    const DATA = await fetchData(COMENTARIOS_API, action, FORM);
    try {
        // Se comprueba si la respuesta es satisfactoria, de lo contrario se constata si el cliente ha iniciado sesión.
        if (DATA.status) {
            sweetAlert(1, DATA.message, false);
            COMENTARIO_FORM.reset();
        } else if (!DATA.exception) {
            sweetAlert(3, DATA.error, true);
        } else {
            sweetAlert(3, DATA.exception, true);
        }
    } catch (error) {
        sweetAlert(3, "Debe iniciar sesión para hacer un comentario al producto", true);
    }
});

