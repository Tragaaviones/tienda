/*
* Controlador de uso general en las páginas web del sitio privado.
* Sirve para manejar la plantilla del encabezado y pie del documento.
*/

// Constante para completar la ruta de la API.
const USER_API = 'servicios/administrador/administrador.php';
// Constante para establecer el elemento del contenido principal.
const MAIN = document.querySelector('main');
MAIN.style.paddingTop = '95px';
MAIN.style.paddingBottom = '100px';
MAIN.classList.add('container');
// Se establece el título de la página web.
document.querySelector('title').textContent = 'Niki`s';
// Constante para establecer el elemento del título principal.
const MAIN_TITLE = document.getElementById('mainTitle');
MAIN_TITLE.classList.add('text-center', 'py-3');

/* Función asíncrona para cargar el encabezado y pie del documento.
* Parámetros: ninguno.
* Retorno: ninguno.
*/
const loadTemplate = async () => {
MAIN.insertAdjacentHTML('beforebegin', `
<header>
    <nav class="navbar bg-body-tertiary fixed-top">
        <div class="container-fluid" class="offcanvas offcanvas-start">
            <button class="navbar-toggler" type="button" data-bs-toggle="offcanvas" data-bs-target="#offcanvasNavbar"
                aria-controls="offcanvasNavbar" aria-label="Toggle navigation">
                <a class="navbar-brand">
                    <img src="../../recursos/imagenes/logo.png" class="rounded" alt="Nikis" width="125" height="75">
                </a>
                <span class="navbar-toggler-icon"></span>
                <label for="navbar-toggler"> Menú</label>
            </button>
            <div class="dropdown">
                <button class="btn btn-light dropdown-toggle" type="button" data-bs-toggle="dropdown"
                    aria-expanded="false">
                    Administrador
                    <i class="bi bi-person-circle"></i>
                </button>
                <ul class="dropdown-menu">
                    <li><button class="dropdown-item" type="button">Perfil</button></li>
                </ul>
            </div>
            <div class="offcanvas offcanvas-start rounded" tabindex="-1" id="offcanvasNavbar"
                aria-labelledby="offcanvasNavbarLabel">
                <div class="offcanvas-header">
                    <a class="navbar-brand">Menú</a>
                    <button type="button" class="btn-close" data-bs-dismiss="offcanvas" aria-label="Close"></button>
                </div>
                <div class="offcanvas-body">
                    <ul class="navbar-nav justify-content-end flex-grow-1 pe-3">
                        <li class="nav-item">
                            <a class="nav-link active" aria-current="page" href="/vistas/administrador/producto.html"><i class="bi bi-archive-fill"></i>
                                Productos</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link active" href="/vistas/administrador/usuarios.html"><i class="bi bi-people-fill"></i> Usuarios</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link active" aria-current="page" href="#"><i class="bi bi-bookmark-fill"></i>
                                Marcas y Tallas</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link active" aria-current="page" href="/vistas/administrador/categoria.html"><i class="bi bi-bookmarks-fill"></i>
                                Categorias</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link active" aria-current="page" href="/vistas/administrador/comentarios.html"><i class="bi bi-chat-text-fill"></i>
                                Comentarios</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link active" aria-current="page" href="/vistas/administrador/inventario.html"><i class="bi bi-list-task"></i>
                                Inventario</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link active" aria-current="page" href="/vistas/administrador/pedidos.html"><i class="bi bi-cart-fill"></i>
                                Pedidos</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link active" aria-current="page" href="/vistas/administrador/clientes.html"><i class="bi bi-people-fill"></i>
                                Clientes</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link active" aria-current="page" href="/vistas/administrador/tiposUsuarios.html"><i
                                    class="bi bi-person-vcard-fill"></i> Tipos de usuarios</a>
                        </li>
                    </ul>
                </div>
                <div class="d-grid gap-2">
                    <button type="button" class="btn btn-danger">Cerrar sesión <i
                            class="bi bi-box-arrow-left"></i></button>
                </div>
            </div>
        </div>
    </nav>
</header>

`);
MAIN.insertAdjacentHTML('afterend', `
<footer class="py-3 bg-body-tertiary bottom">
    <div class="container-fluid clear">
        <ul class="nav justify-content-evenly border-bottom pb-3 mb-3">
            <li class="nav-item">
                <a href="https://www.instagram.com/" target="_blank" class="nav-link px-2 text-muted">
                    <h4><i class="bi bi-instagram"></i></h4>
                </a>
            </li>
            <li class="nav-item">
                <a href="https://www.facebook.com/" target="_blank" class="nav-link px-2 text-muted">
                    <h4><i class="bi bi-facebook"></i></h4>
                </a>
            </li>
            <li class="nav-item">
                <a class="navbar-brand">
                    <img src="../../recursos/imagenes/logo.png" class="rounded" alt="Nikis" width="100" height="70">
                </a>
            </li>
            <li class="nav-item">
                <a href="https://twitter.com/" target="_blank" class="nav-link px-2 text-muted">
                    <h4><i class="bi bi-twitter"></i></h4>
                </a>
            </li>
            <li class="nav-item">
                <a href="https://www.tiktok.com/" target="_blank" class="nav-link px-2 text-muted">
                    <h4><i class="bi bi-tiktok"></i></h4>
                </a>
            </li>
        </ul>
        <p class="text-center text-muted">&copy; 2024 NIKI’S El Salvador . Todos los derechos reservados.</p>
    </div>
</footer>
`);


} // Petición para obtener en nombre del usuario que ha iniciado sesión.
// // const DATA = await fetchData(USER_API, 'getUser');
// // Se verifica si el usuario está autenticado, de lo contrario se envía a iniciar sesión.
// if (DATA.session) {
// // Se comprueba si existe un alias definido para el usuario, de lo contrario se muestra un mensaje con la excepción.
// if (DATA.status) {
// // Se agrega el encabezado de la página web antes del contenido principal.
// // aqui iba el navbar
// // Se agrega el pie de la página web después del contenido principal.
// // aqui iba el footer
// } else {
// sweetAlert(3, DATA.error, false, 'index.html');
// }
// } else {
// // Se comprueba si la página web es la principal, de lo contrario se direcciona a iniciar sesión.
// if (location.pathname.endsWith('index.html')) {
// // Se agrega el encabezado de la página web antes del contenido principal.
// MAIN.insertAdjacentHTML('beforebegin', `
// <header>
    // <nav class="navbar fixed-top bg-body-tertiary">
        // <div class="container">
            // <a class="navbar-brand" href="index.html">
                // <img src="../../resources/img/logo.png" alt="inventory" width="50">
                // </a>
            // </div>
        // </nav>
    // </header>
// `);
// // Se agrega el pie de la página web después del contenido principal.
// MAIN.insertAdjacentHTML('afterend', `
// <footer>
    // <nav class="navbar fixed-bottom bg-body-tertiary">
        // <div class="container">
            // <p><a class="nav-link" href="https://github.com/dacasoft/coffeeshop" target="_blank"><i // 
                        //class="bi bi-github"></i> CoffeeShop</a></p>
            // <p><i class="bi bi-envelope-fill"></i> dacasoft@outlook.com</p>
            // </div>
        // </nav>
    // </footer>
// `);
// } else {
// location.href = 'index.html';
// }
// }