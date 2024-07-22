const DETALLE_PEDIDO_API = 'servicios/administrador/detalle_pedido.php',
    PRODUCTO_API = 'servicios/administrador/producto.php';
// const REPORT_MODAL = new bootstrap.Modal('#reportModal'),
//     REPORT_MODAL_TITLE = document.getElementById('reportModalTitle');

document.addEventListener('DOMContentLoaded', () => {
    // Constante para obtener el número de horas.
    const HOUR = new Date().getHours();
    // Se define una variable para guardar un saludo.
    let greeting = '';
    // Dependiendo del número de horas transcurridas en el día, se asigna un saludo para el usuario.
    if (HOUR < 12) {
        greeting = 'Buenos días';
    } else if (HOUR < 19) {
        greeting = 'Buenas tardes';
    } else if (HOUR <= 23) {
        greeting = 'Buenas noches';
    }
    // Llamada a la función para mostrar el encabezado y pie del documento.
    loadTemplate();
    // Se establece el título del contenido principal.
    MAIN_TITLE.textContent = `${greeting}, bienvenido administrador`;
    graficoDetalle();
    graficoBarrasCategorias();
});

// Función para abrir el Modal
// async function openModalGraphic() {
//     // Se muestra la caja de diálogo con su título.
//     REPORT_MODAL.show();
//     REPORT_MODAL_TITLE.textContent = 'Gráfica de dona de pedidos por estado';
//     try {
//         graficoDetalle();
//     } catch (error) {
//         console.log(error);
//     }
// }

const graficoDetalle = async () => {
    try {
        // Petición para obtener los datos del gráfico.
        const DATA = await fetchData(DETALLE_PEDIDO_API, 'graficosDetalle');
        // Se comprueba si la respuesta es satisfactoria, de lo contrario se remueve la etiqueta canvas.
        if (DATA.status) {
            // Se declaran los arreglos para guardar los datos a gráficar.
            let nombre = [];
            let cantidad = [];
            // Se recorre el conjunto de registros fila por fila a través del objeto row.
            DATA.dataset.forEach(row => {
                // Se agregan los datos a los arreglos.
                nombre.push(row.NOMBRE)
                cantidad.push(row.CANTIDAD);
            });
            // Llamada a la función para generar y mostrar un gráfico de pastel. Se encuentra en el archivo components.js
            pieGraph('chart2', nombre, cantidad, 'Productos más vendidos');
        } else {
            document.getElementById('chart2').remove();
            console.log(DATA.error);
        }
    } catch {
        console.log('error')
        sweetAlert(4, DATA.error, true);
    }
}

/*
*   Función asíncrona para mostrar un gráfico de barras con la cantidad de productos por categoría.
*   Parámetros: ninguno.
*   Retorno: ninguno.
*/
const graficoBarrasCategorias = async () => {
    // Petición para obtener los datos del gráfico.
    const DATA = await fetchData(PRODUCTO_API, 'cantidadProductosCategoria');
    // Se comprueba si la respuesta es satisfactoria, de lo contrario se remueve la etiqueta canvas.
    if (DATA.status) {
        // Se declaran los arreglos para guardar los datos a graficar.
        let categorias = [];
        let cantidades = [];
        // Se recorre el conjunto de registros fila por fila a través del objeto row.
        DATA.dataset.forEach(row => {
            // Se agregan los datos a los arreglos.
            categorias.push(row.NOMBRE);
            cantidades.push(row.CANTIDAD);
        });
        // Llamada a la función para generar y mostrar un gráfico de barras. Se encuentra en el archivo components.js
        barGraph('chart1', categorias, cantidades, 'Cantidad de productos', 'Cantidad de productos por categoría');
    } else {
        document.getElementById('chart1').remove();
        console.log(DATA.error);
    }
}




