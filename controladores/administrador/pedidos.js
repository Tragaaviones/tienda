// Constante para completar la ruta de la API.
const PEDIDOS_API = 'servicios/administrador/pedido.php';
const DETALLE_PEDIDO_API = 'servicios/administrador/detalle_pedido.php';
// Constante para establecer el formulario de buscar.
const SEARCH_FORM = document.getElementById('searchForm');
// Constantes para establecer los elementos de la tabla.
const TABLE_BODY = document.getElementById('tableBody'),
    ROWS_FOUND = document.getElementById('rowsFound');
// Constantes para establecer los elementos del componente Modal.
const SAVE_MODAL = new bootstrap.Modal('#saveModal'),
    MODAL_TITLE = document.getElementById('modalTitle');
const DETAIL_MODAL = new bootstrap.Modal('#detailModal'),
    MODAL_TITLE_DETAIL = document.getElementById('exampleModalLabel');
// Modal del reporte
const REPORT_MODAL = new bootstrap.Modal('#reportModal'),
    REPORT_MODAL_TITLE = document.getElementById('reportModalTitle');
// Método del evento para cuando el documento ha cargado.
document.addEventListener('DOMContentLoaded', () => {
    // Llamada a la función para mostrar el encabezado y pie del documento.
    loadTemplate();
    // Se establece el título del contenido principal.
    MAIN_TITLE.textContent = 'Gestionar pedidos';
    // Llamada a la función para llenar la tabla con los registros existentes.
    fillTable();
});

// Método del evento para cuando se envía el formulario de buscar.
SEARCH_FORM.addEventListener('submit', (event) => {
    // Se evita recargar la página web después de enviar el formulario.
    event.preventDefault();
    // Constante tipo objeto con los datos del formulario.
    const FORM = new FormData(SEARCH_FORM);
    // Llamada a la función para llenar la tabla con los resultados de la búsqueda.
    fillTable(FORM);
});



/*
*   Función asíncrona para llenar la tabla con los registros disponibles.
*   Parámetros: form (objeto opcional con los datos de búsqueda).
*   Retorno: ninguno.
*/
const fillTable = async (form = null) => {
    // Se inicializa el contenido de la tabla.
    ROWS_FOUND.textContent = '';
    TABLE_BODY.innerHTML = '';
    // Se verifica la acción a realizar.
    (form) ? action = 'searchRows' : action = 'readAll';
    // Petición para obtener los registros disponibles.
    const DATA = await fetchData(PEDIDOS_API, action, form);
    // Se comprueba si la respuesta es satisfactoria, de lo contrario se muestra un mensaje con la excepción.
    if (DATA.status) {
        // Se recorre el conjunto de registros fila por fila.
        DATA.dataset.forEach(row => {
            // Se crean y concatenan las filas de la tabla con los datos de cada registro.
            TABLE_BODY.innerHTML += `
                <tr>
                    <td>${row.FECHA}</td>
                    <td>${row.DIRECCION}</td>
                    <td>${row.ESTADO}</td>
                    <td>
                    <button type="button" class="btn btn-outline-info" onclick="openDetail(${row.ID})">
                    <i class="bi bi-card-list"></i>
                    </button>
                    <button type="button" class="btn btn-outline-primary" onclick="openState(${row.ID})">
                    <i class="bi bi-exclamation-octagon"></i>
                    </button>
                    </td>
                </tr>
            `;
        });
        // Se muestra un mensaje de acuerdo con el resultado.
        ROWS_FOUND.textContent = DATA.message;
    } else {
        sweetAlert(4, DATA.error, true);
    }
}


/*
*   Función asíncrona para llenar la tabla con los registros disponibles.
*   Parámetros: form (objeto opcional con los datos de búsqueda).
*   Retorno: ninguno.
*/
const fillDetail = async (form = null) => {
    const cargarTabla = document.getElementById('tabla_detalle');
    cargarTabla.innerHTML = '';
    // Petición para obtener los registros disponibles.
    const DATA = await fetchData(DETALLE_PEDIDO_API, 'readOne', form);
    if (DATA.status) {
        // Mostrar elementos obtenidos de la API
        DATA.dataset.forEach(row => {
            const tablaHtml = `
            <tr>
                    <td>${row.FECHA}</td>
                    <td>${row.PRECIO}</td>
                    <td>${row.DIRECCION}</td>
                    <td>${row.CANTIDAD}</td>
            </tr>
                `;
            cargarTabla.innerHTML += tablaHtml;
        });
    } else {
        sweetAlert(4, DATA.error, true);
    }
}

/*
*   Función asíncrona para cambiar el estado de un registro.
*   Parámetros: id (identificador del registro seleccionado).
*   Retorno: ninguno.
*/
const openState = async (id) => {
    // Llamada a la función para mostrar un mensaje de confirmación, capturando la respuesta en una constante.
    const RESPONSE = await confirmAction('¿Desea cambiar el estado del pedido?');
    try {
        // Se verifica la respuesta del mensaje.
        if (RESPONSE) {
            // Se define una constante tipo objeto con los datos del registro seleccionado.
            const FORM = new FormData();
            FORM.append('idPedido', id);
            // Petición para eliminar el registro seleccionado.
            const DATA = await fetchData(PEDIDOS_API, 'changeState', FORM);
            // Se comprueba si la respuesta es satisfactoria, de lo contrario se muestra un mensaje con la excepción.
            if (DATA.status) {
                // Se muestra un mensaje de éxito.
                await sweetAlert(1, DATA.message, true);
                // Se carga nuevamente la tabla para visualizar los cambios.
                fillTable();
            } else {
                sweetAlert(2, DATA.error, false);
            }
        }
    }
    catch (Error) {
        sweetAlert(2, Error, false);
    }
}

const openDetail = async (id) => {
    // Se define un objeto con los datos del registro seleccionado.
    const FORM = new FormData();
    FORM.append('idPedido', id);
    // Se muestra la caja de diálogo con su título.
    DETAIL_MODAL.show();
    MODAL_TITLE_DETAIL.textContent = 'Detalle del pedido ' + id;
    fillDetail(FORM);
}

// Función para abrir el Modal
async function openModalGraphic() {
    // Se muestra la caja de diálogo con su título.
    REPORT_MODAL.show();
    REPORT_MODAL_TITLE.textContent = 'Gráfica de dona de pedidos por estado';
    try {
        graficoDonaPedidosEstados();
    } catch (error) {
        console.log(error);
    }
}

/*
*   Función asíncrona para mostrar un gráfico de pastel con el porcentaje de productos por categoría.
*   Parámetros: ninguno.
*   Retorno: ninguno.
*/
const graficoDonaPedidosEstados = async () => {
    try {
        // Petición para obtener los datos del gráfico.
        const DATA = await fetchData(PEDIDOS_API, 'graphicStates');
        // Se comprueba si la respuesta es satisfactoria, de lo contrario se remueve la etiqueta canvas.
        if (DATA.status) {
            // Se declaran los arreglos para guardar los datos a gráficar.
            let estado = [];
            let cantidad = [];
            // Se recorre el conjunto de registros fila por fila a través del objeto row.
            DATA.dataset.forEach(row => {
                // Se agregan los datos a los arreglos.
                estado.push(row.ESTADO);
                cantidad.push(row.CANTIDAD);
            });
            // Llamada a la función para generar y mostrar un gráfico de pastel. Se encuentra en el archivo components.js
            DoughnutGraph('chart2', estado, cantidad, 'Pedidos por estado');
        } else {
            document.getElementById('chart2').remove();
            console.log(DATA.error);
        }
    } catch {
        console.log('error')
    }

}



// ESTA FUNCIÓN NO SE UTILIZA (es un ejemplo de uso de gráfica de barras o lineal)

/*
*   Función asíncrona para mostrar un gráfico de barras con la cantidad de productos por categoría.
*   Parámetros: ninguno.
*   Retorno: ninguno.
*/
const graficoBarrasCategorias = async () => {
    /*
*   Lista de datos de ejemplo en caso de error al obtener los datos reales.
*/
    try {
        // Petición para obtener los datos del gráfico.
        let DATA = await fetchData(PRODUCTO_API, '');
        // Se comprueba si la respuesta es satisfactoria, de lo contrario se remueve la etiqueta canvas.
        if (DATA.status) {
            // Se declaran los arreglos para guardar los datos a graficar.
            let categorias = [];
            let cantidades = [];
            // Se recorre el conjunto de registros fila por fila a través del objeto row.
            DATA.dataset.forEach(row => {
                // Se agregan los datos a los arreglos.
                categorias.push(row.nombre_categoria);
                cantidades.push(row.cantidad);
            });
            // Llamada a la función para generar y mostrar un gráfico de barras. Se encuentra en el archivo components.js
            barGraph('chart1', categorias, cantidades, 'Cantidad de productos', 'Cantidad de productos por categoría');
        } else {
            document.getElementById('chart1').remove();
            console.log(DATA.error);
        }
    } catch (error) {
    }
}