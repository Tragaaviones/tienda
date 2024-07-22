// Constante para completar la ruta de la API.
const CLIENTES_API = 'servicios/administrador/cliente.php';
// Constante para establecer el formulario de buscar.
const SEARCH_FORM = document.getElementById('searchForm');
// Constantes para establecer los elementos de la tabla.
const TABLE_BODY = document.getElementById('tableBody'),
    ROWS_FOUND = document.getElementById('rowsFound');
// Constantes para establecer los elementos del componente Modal.
const SAVE_MODAL = new bootstrap.Modal('#saveModal'),
    MODAL_TITLE = document.getElementById('modalTitle');
//Modal de reporte
const REPORT_MODAL = new bootstrap.Modal('#reportModal'),
    REPORT_MODAL_TITLE = document.getElementById('reportModalTitle');
// Método del evento para cuando el documento ha cargado.
document.addEventListener('DOMContentLoaded', () => {
    // Llamada a la función para mostrar el encabezado y pie del documento.
    loadTemplate();
    // Se establece el título del contenido principal.
    MAIN_TITLE.textContent = 'Gestionar clientes';
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
    const DATA = await fetchData(CLIENTES_API, action, form);
    // Se comprueba si la respuesta es satisfactoria, de lo contrario se muestra un mensaje con la excepción.
    if (DATA.status) {
        // Se recorre el conjunto de registros fila por fila.
        DATA.dataset.forEach(row => {
            // Se crean y concatenan las filas de la tabla con los datos de cada registro.
            TABLE_BODY.innerHTML += `
                <tr>
                    <td>${row.NOMBRE}</td>
                    <td>${row.APELLIDO}</td>
                    <td>${row.TELEFONO}</td>
                    <td>${row.CORREO}</td>
                    <td>${row.ESTADO}</td>
                    <td>
                        <button type="button" class="btn btn-outline-primary" onclick="openState(${row.ID})">
                        <i class="bi bi-exclamation-octagon"></i>
                        </button>
                    </td>
                    <td>
                        <button type="button" class="btn btn-warning" onclick="openReport(${row.ID})">
                        <i class="bi bi-filetype-pdf"></i>
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
*   Función asíncrona para cambiar el estado de un registro.
*   Parámetros: id (identificador del registro seleccionado).
*   Retorno: ninguno.
*/
const openState = async (id) => {
    // Llamada a la función para mostrar un mensaje de confirmación, capturando la respuesta en una constante.
    const RESPONSE = await confirmAction('¿Desea cambiar el estado del cliente?');
    try {
        // Se verifica la respuesta del mensaje.
        if (RESPONSE) {
            // Se define una constante tipo objeto con los datos del registro seleccionado.
            const FORM = new FormData();
            FORM.append('idCliente', id);
            // Petición para eliminar el registro seleccionado.
            const DATA = await fetchData(CLIENTES_API, 'changeState', FORM);
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

/*
*   Función para abrir un reporte parametrizado de productos de una categoría.
*   Parámetros: id (identificador del registro seleccionado).
*   Retorno: ninguno.
*/
const openReport = (id) => {
    // Se declara una constante tipo objeto con la ruta específica del reporte en el servidor.
    const PATH = new URL(`${SERVER_URL}reportes/administrador/cliente_pedidos.php`);
    // Se agrega un parámetro a la ruta con el valor del registro seleccionado.
    PATH.searchParams.append('ID', id);
    // Se abre el reporte en una nueva pestaña.
    window.open(PATH.href);
}

async function openModalGraphic() {
    // Se muestra la caja de diálogo con su título.
    REPORT_MODAL.show();
    REPORT_MODAL_TITLE.textContent = 'Gráfica de dona de clientes por estado';
    try {
        graficoDonaClientesEstados();
    } catch (error) {
        console.log(error);
    }
}

const graficoDonaClientesEstados = async () => {
    try {
        // Petición para obtener los datos del gráfico.
        const DATA = await fetchData(CLIENTES_API, 'graficoState');
        // Se comprueba si la respuesta es satisfactoria, de lo contrario se remueve la etiqueta canvas.
        if (DATA.status) {
            // Se declaran los arreglos para guardar los datos a gráficar.
            let estado = [];
            let cantidad = [];
            // Se recorre el conjunto de registros fila por fila a través del objeto row.
            DATA.dataset.forEach(row => {
                // Convertir estado booleano a texto legible.
                let estadoTexto = row.ESTADO ? 'Activo' : 'Bloqueado';
                // Se agregan los datos a los arreglos.
                estado.push(estadoTexto);
                cantidad.push(row.CANTIDAD);
            });
            // Llamada a la función para generar y mostrar un gráfico de pastel. Se encuentra en el archivo components.js
            DoughnutGraph('chart2', estado, cantidad, 'Clientes por estado');
        } else {
            document.getElementById('chart2').remove();
            console.log(DATA.error);
        }
    } catch (error) {
        console.log('error:', error);
    }
}


