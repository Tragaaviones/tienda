/*
* Controlador de uso general en las páginas web del sitio privado.
* Sirve para manejar la plantilla del encabezado y pie del documento.
*/

// Constante para completar la ruta de la API.
const USER_API = 'servicios/administrador/administrador.php';
// Constante para establecer el elemento del contenido principal.
const MAIN = document.querySelector('main');
MAIN.style.paddingTop = '150px';
MAIN.style.paddingBottom = '150px';
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
    const DATA = await fetchData(USER_API, 'getUser');
    if (DATA.session) {
        if (DATA.status) {
            MAIN.insertAdjacentHTML('beforebegin', `
                <header>
                    <nav class="fixed-top navbar bg-body-tertiary fixed-top">
                        <div class="container-fluid" class="offcanvas offcanvas-start">
                            <button class="navbar-toggler" type="button" data-bs-toggle="offcanvas" data-bs-target="#offcanvasNavbar"
                                aria-controls="offcanvasNavbar" aria-label="Toggle navigation">
                                <a class="navbar-brand">
                                    <img src="../../recursos/imagenes/logo.png" class="rounded" alt="Nikis" width="125" height="75">
                                </a>
                                <span class="navbar-toggler-icon"></span>
                                <label for="navbar-toggler"> Menú</label>
                            </button>

                            <div class="offcanvas offcanvas-start rounded" tabindex="-1" id="offcanvasNavbar"
                                aria-labelledby="offcanvasNavbarLabel">
                                <div class="offcanvas-header">
                                    <a class="navbar-brand">Menú</a>
                                    <button type="button" class="btn-close" data-bs-dismiss="offcanvas" aria-label="Close"></button>
                                </div>
                                <div class="offcanvas-body">
                                    <ul class="navbar-nav justify-content-end flex-grow-1 pe-3">
                                        <li class="nav-item">
                                            <a class="nav-link active" aria-current="page" href="dashboard.html"><i
                                                    class="bi bi-house-fill"></i>
                                                Dashboard</a>
                                        </li>
                                        <li class="nav-item">
                                            <a class="nav-link active" aria-current="page" href="producto.html"><i
                                                    class="bi bi-archive-fill"></i>
                                                Productos</a>
                                        </li>
                                        <li class="nav-item">
                                            <a class="nav-link active" href="usuarios.html"><i class="bi bi-people-fill"></i>
                                                Usuarios</a>
                                        </li>
                                        <li class="nav-item">
                                            <a class="nav-link active" aria-current="page" href="marcas.html"><i
                                                    class="bi bi-bookmark-fill"></i>
                                                Marcas</a>
                                        </li>
                                        <li class="nav-item">
                                            <a class="nav-link active" aria-current="page" href="tallas.html"><i
                                                    class="bi bi-bookmark-fill"></i>
                                                Tallas</a>
                                        </li>
                                        <li class="nav-item">
                                            <a class="nav-link active" aria-current="page" href="categoria.html"><i
                                                    class="bi bi-bookmarks-fill"></i>
                                                Categorias</a>
                                        </li>
                                        <li class="nav-item">
                                            <a class="nav-link active" aria-current="page" href="comentarios.html"><i
                                                    class="bi bi-chat-text-fill"></i>
                                                Comentarios</a>
                                        </li>
                                        <li class="nav-item">
                                            <a class="nav-link active" aria-current="page" href="inventario.html"><i
                                                    class="bi bi-list-task"></i>
                                                Inventario</a>
                                        </li>
                                        <li class="nav-item">
                                            <a class="nav-link active" aria-current="page" href="pedidos.html"><i
                                                    class="bi bi-cart-fill"></i>
                                                Pedidos</a>
                                        </li>
                                        <li class="nav-item">
                                            <a class="nav-link active" aria-current="page" href="clientes.html"><i
                                                    class="bi bi-people-fill"></i>
                                                Clientes</a>
                                        </li>
                                        <li class="nav-item">
                                            <a class="nav-link active" aria-current="page" href="tiposUsuarios.html"><i
                                                    class="bi bi-person-vcard-fill"></i>
                                                Tipos de usuarios</a>
                                        </li>
                                    </ul>
                                </div>
                                <div class="d-grid gap-2">
                                    <button type="button" class="btn btn-danger" onclick="logOut()">Cerrar sesión <i
                                            class="bi bi-box-arrow-left"></i></button>
                                </div>
                            </div>
                        </div>
                    </nav>
                </header>
        }
`);
            MAIN.insertAdjacentHTML('afterend', `
    <footer id='footer' class="bg-body-tertiary py-5">
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
        } else {
            sweetAlert(3, DATA.error, false, 'index.html');
        }
    } else {
        // Se comprueba si la página web es la principal, de lo contrario se direcciona a iniciar sesión.
        if (location.pathname.endsWith('index.html')) {
            // Se agrega el encabezado de la página web antes del contenido principal.
            MAIN.insertAdjacentHTML('beforebegin', `

`);
            // Se agrega el pie de la página web después del contenido principal.
            MAIN.insertAdjacentHTML('afterend', `

`);
        } else {
            location.href = 'index.html';
        }
    }
}