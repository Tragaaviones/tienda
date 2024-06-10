// Constante para establecer el formulario de buscar.
const SEARCH_FORM = document.getElementById('searchForm');
const MAIN_TITLE = document.getElementById('mainTitle');
// Método del evento para cuando el documento ha cargado.
document.addEventListener('DOMContentLoaded', async () => {
loadTemplate();
cargarProductos();
    // Se establece el título del contenido principal.
    MAIN_TITLE.textContent = 'Bienvienidos/as a tienda Niki´s';
    read()
});

const PRODUCTOS_API = "servicios/publico/producto.php";
const CATEGORIAS_API = "servicios/publico/categoria.php";

// Método del evento para cuando se envía el formulario de buscar.
SEARCH_FORM.addEventListener('submit', (event) => {
    // Se evita recargar la página web después de enviar el formulario.
    event.preventDefault();
    // Constante tipo objeto con los datos del formulario.
    const FORM = new FormData(SEARCH_FORM);
    read(FORM)
});

/*
*   Función asíncrona para llenar la tabla con los registros disponibles.
*   Parámetros: form (objeto opcional con los datos de búsqueda).
*   Retorno: ninguno.
*/
const read = async (form = null) => {
    // Se verifica la acción a realizar.
    (form) ? action = 'searchRowsPublic' : action = 'readAll';
    // Petición para obtener los registros disponibles.
    const DATA = await fetchData(PRODUCTOS_API, action, form);
}


// Método del evento para cuando se envía el formulario de buscar.
SEARCH_FORM.addEventListener('submit', (event) => {
    // Se evita recargar la página web después de enviar el formulario.
    event.preventDefault();
    // Constante tipo objeto con los datos del formulario.
    const FORM = new FormData(SEARCH_FORM);
    // Llamada a la función para llenar la tabla con los resultados de la búsqueda.
    buscarProductos(FORM);
});


async function buscarProductos(FORM = null) {
    try {
        // Petición para obtener las categorías
        const categories = await fetchData(CATEGORIAS_API, 'readAll');
        const dynamicCarousels = document.getElementById('dynamicCarousels');
        const container = document.createElement('div');
        container.className = 'container';

        dynamicCarousels.innerHTML = '';
        for (const category of categories.dataset) {
            // Petición para obtener los productos de la categoría
            FORM.append('idCategoria', category.id_categoria);
            const productsResponse = await fetchData(PRODUCTOS_API, 'searchRowsPublic', FORM);
            const products = productsResponse.dataset;

            // Se crea un carrusel por categoría
            const carouselId = `carousel-${category.id_categoria}`;
            const carouselContainer = document.createElement('div');
            carouselContainer.className = 'col m-4 justify-content-between d-flex';

            // Crear un div para contener el título de la sección
            const titleContainer = document.createElement('div');
            carouselContainer.className = 'col-12 pb-5';

            // Agregar título de la sección
            const sectionTitle = document.createElement('strong');
            sectionTitle.className = 'text-start mb-3';
            sectionTitle.textContent = `Busca tus ${category.nombre} favoritos aquí`;

            // Agregar el título al contenedor
            titleContainer.appendChild(sectionTitle);
            // Agregar el contenedor del título al contenedor del carrusel
            carouselContainer.appendChild(titleContainer);

            // Añadir espacio entre el título y el carrusel
            const spaceDiv = document.createElement('div');
            spaceDiv.className = 'mt-4'; // Agrega un margen superior de 4 unidades
            carouselContainer.appendChild(spaceDiv);

            // Crear el carrusel
            const carousel = document.createElement('div');
            carousel.className = 'carousel slide';
            carousel.id = carouselId;
            carousel.dataset.bsRide = 'carousel';

            let innerHTML = '';

            if (Array.isArray(products) && products.length > 0) {
                innerHTML = `
                    <div class="carousel-inner">
                `;
                // Agrupar productos en grupos de tres
                for (let i = 0; i < products.length; i += 3) {
                    innerHTML += `
                        <div class="carousel-item ${i === 0 ? 'active' : ''}">
                            <div class="row">
                    `;
                    // Mostrar hasta tres productos en cada grupo
                    for (let j = i; j < i + 3 && j < products.length; j++) {
                        const product = products[j];
                        innerHTML += `
                            <div class="col-4">
                                <div class="card mb-3" style="max-width: 540px;">
                                    <div class="imagen1">
                                        <div class="text-center">
                                            <img src="${SERVER_URL}imagenes/productos/${product.IMAGEN}" class="d-block w-100 img-thumbnail" style="max-width: 200px; max-height: 300px;" alt="${product.nombre_producto}">
                                        </div>
                                        <div class="col-md-15">
                                            <div class="card-body">
                                                <h5 class="card-title">${product.nombre_producto} - ${product.nombre_marca}</h5>
                                                <p class="letra1">${product.descripcion}</p>
                                                <h6 class="card-title">En venta solo en Niki´s</h6>
                                                <p class="letra2">$${product.precio_unitario}</p>
                                                <button class="botines"><a href="detalle_producto.html?id=${product.id_producto}">Comprar producto</a></button>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        `;
                    }
                    innerHTML += `
                            </div>
                        </div>
                    `;
                }
                innerHTML += `
                    </div>
                    <button class="carousel-control-prev bg-dark redondo" type="button" data-bs-target="#${carouselId}" data-bs-slide="prev">
                        <span class="carousel-control-prev-icon" aria-hidden="true"></span>
                        <span class="visually-hidden">Anterior</span>
                    </button>
                    <button class="carousel-control-next bg-dark redondo" type="button" data-bs-target="#${carouselId}" data-bs-slide="next">
                        <span class="carousel-control-next-icon" aria-hidden="true"></span>
                        <span class="visually-hidden">Siguiente</span>
                    </button>
                `;
            } else {
                innerHTML = `<p>No hay productos disponibles para esta categoría.</p>`;
            }

            carousel.innerHTML = innerHTML;
            carouselContainer.appendChild(carousel);
            container.appendChild(carouselContainer);
        }
        dynamicCarousels.appendChild(container);
    } catch (error) {
        console.error('Error en la api:', error);
    }
}


async function cargarProductos() {
    try {
        // Petición para obtener las categorías
        const categories = await fetchData(CATEGORIAS_API, 'readAll');
        const dynamicCarousels = document.getElementById('dynamicCarousels');
        const container = document.createElement('div');
        container.className = 'container';

        dynamicCarousels.innerHTML = '';
        for (const category of categories.dataset) {
            // Petición para obtener los productos de la categoría
            const form = new FormData();
            form.append('idCategoria', category.id_categoria);
            const productsResponse = await fetchData(PRODUCTOS_API, 'readProductosCategoria', form);
            const products = productsResponse.dataset;

            // Se crea un carrusel por categoría
            const carouselId = `carousel-${category.id_categoria}`;
            const carouselContainer = document.createElement('div');
            carouselContainer.className = 'col m-4 justify-content-between d-flex';

            // Crear un div para contener el título de la sección
            const titleContainer = document.createElement('div');
            carouselContainer.className = 'col-12 pb-5';

            // Agregar título de la sección
            const sectionTitle = document.createElement('strong');
            sectionTitle.className = 'text-start mb-3';
            sectionTitle.textContent = `Busca tus ${category.nombre} favoritos aquí`;

            // Agregar el título al contenedor
            titleContainer.appendChild(sectionTitle);
            // Agregar el contenedor del título al contenedor del carrusel
            carouselContainer.appendChild(titleContainer);

            // Añadir espacio entre el título y el carrusel
            const spaceDiv = document.createElement('div');
            spaceDiv.className = 'mt-4'; // Agrega un margen superior de 4 unidades
            carouselContainer.appendChild(spaceDiv);

            // Crear el carrusel
            const carousel = document.createElement('div');
            carousel.className = 'carousel slide';
            carousel.id = carouselId;
            carousel.dataset.bsRide = 'carousel';

            let innerHTML = '';

            if (Array.isArray(products) && products.length > 0) {
                innerHTML = `
                    <div class="carousel-inner">
                `;
                // Agrupar productos en grupos de tres
                for (let i = 0; i < products.length; i += 3) {
                    innerHTML += `
                        <div class="carousel-item ${i === 0 ? 'active' : ''}">
                            <div class="row">
                    `;
                    // Mostrar hasta tres productos en cada grupo
                    for (let j = i; j < i + 3 && j < products.length; j++) {
                        const product = products[j];
                        innerHTML += `
                            <div class="col-4">
                                <div class="card mb-3" style="max-width: 540px;">
                                    <div class="imagen1">
                                        <div class="text-center">
                                            <img src="${SERVER_URL}imagenes/productos/${product.IMAGEN}" class="d-block w-100 img-thumbnail" style="max-width: 200px; max-height: 300px;" alt="${product.nombre_producto}">
                                        </div>
                                        <div class="col-md-15">
                                            <div class="card-body">
                                                <h5 class="card-title">${product.nombre_producto} - ${product.nombre_marca}</h5>
                                                <p class="letra1">${product.descripcion}</p>
                                                <h6 class="card-title">En venta solo en Niki´s</h6>
                                                <p class="letra2">$${product.precio_unitario}</p>
                                                <button class="botines"><a href="detalle_producto.html?id=${product.id_producto}">Comprar producto</a></button>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        `;
                    }
                    innerHTML += `
                            </div>
                        </div>
                    `;
                }
                innerHTML += `
                    </div>
                    <button class="carousel-control-prev bg-dark redondo" type="button" data-bs-target="#${carouselId}" data-bs-slide="prev">
                        <span class="carousel-control-prev-icon" aria-hidden="true"></span>
                        <span class="visually-hidden">Anterior</span>
                    </button>
                    <button class="carousel-control-next bg-dark redondo" type="button" data-bs-target="#${carouselId}" data-bs-slide="next">
                        <span class="carousel-control-next-icon" aria-hidden="true"></span>
                        <span class="visually-hidden">Siguiente</span>
                    </button>
                `;
            } else {
                innerHTML = `<p>No hay productos disponibles para esta categoría.</p>`;
            }

            carousel.innerHTML = innerHTML;
            carouselContainer.appendChild(carousel);
            container.appendChild(carouselContainer);
        }
        dynamicCarousels.appendChild(container);
    } catch (error) {
        console.error('Error en la api:', error);
    }
}
