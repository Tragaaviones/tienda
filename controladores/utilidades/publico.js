/*
* Controlador es de uso general en las páginas web del sitio público.
* Sirve para manejar las plantillas del encabezado y pie del documento.
*/

// Constante para completar la ruta de la API.
const USER_API = 'servicios/publico/cliente.php';
// Constante para establecer el elemento del contenido principal.
const MAIN = document.querySelector('main');
MAIN.style.paddingTop = '150px';
MAIN.style.paddingBottom = '150px';
MAIN.classList.add('container');
// Se establece el título de la página web.
document.querySelector('title').textContent = 'Niki´s - Store';

/* Función asíncrona para cargar el encabezado y pie del documento.
* Parámetros: ninguno.
* Retorno: ninguno.
*/
const loadTemplate = async () => {
const DATA = await fetchData(USER_API, 'getUser');
if (DATA.session) {
if (!location.pathname.endsWith('login.html')) {
MAIN.insertAdjacentHTML('beforebegin', `
<header class="header fixed-top">
    <!-- barra de navegación -->
    <nav class="navbar bg-body-tertiary navegacion">
        <div class="container-fluid">
            <button class="navbar-toggler" type="button" data-bs-toggle="offcanvas" data-bs-target="#offcanvasNavbar"
                aria-controls="offcanvasNavbar" aria-label="Toggle navigation">
                <img src="../../recursos/imagenes/logo.png" alt="Niki's" class=" rounded" alt="Nikis" width="125"
                    height="75">
                <span class=" navbar-toggler-icon"></span>
                <label for="navbar-toggler"> Menú</label>
            </button>

            <!-- contenido de carrito de compras -->
            <div class="button">
                <button class="btn btn-light" type="button" aria-expanded="false">
                    <a href="historial.html"><img src="../../recursos/imagenes/historial.png" alt="" width="40"
                            height="40"></a>
                </button>
                <button class="btn btn-light" type="button" aria-expanded="false">
                    <a href="carrito_compra.html"><img src="../../recursos/imagenes/carrito-de-compras.png" alt=""
                            width="40" height="40"></a>
                </button>
            </div>

            <div class="offcanvas offcanvas-start rounded" tabindex="-1" id="offcanvasNavbar"
                aria-labelledby="offcanvasNavbarLabel">
                <div class="offcanvas-header">
                    <h5 class="offcanvas-title" id="offcanvasNavbarLabel">Menu</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="offcanvas" aria-label="Close"></button>
                </div>
                <div class="offcanvas-body">
                    <ul class="navbar-nav justify-content-end flex-grow-1 pe-3">
                        <li class="nav-item">
                            <a class="nav-link active" aria-current="page" href="index.html"><i
                                    class="bi bi-house-door-fill"></i>
                                Página principal</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link active" aria-current="page" href="quienes_somos.html"><i
                                    class="bi bi-people-fill"></i>
                                Sobre nosotros</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link active" href="perfil_cliente.html"><i class="bi bi-person-fill-gear"></i>
                                Actualizar
                                perfil y contraseña</a>
                        </li>
                    </ul>
                </div>
                <div class="d-grid gap-2 nav-item logout">
                    <button type="button" class="btn btn-danger" onclick="logOut()">Cerrar sesión <i
                            class="bi bi-box-arrow-left"></i>
                    </button>
                </div>
            </div>
        </div>
    </nav>
</header>
`);
} else {
location.href = 'index.html';
}
} else {
MAIN.insertAdjacentHTML('beforebegin', `
<header class="header fixed-top">
    <!-- barra de navegación -->
    <nav class="navbar bg-body-tertiary navegacion">
        <div class="container-fluid">
            <button class="navbar-toggler" type="button" data-bs-toggle="offcanvas" data-bs-target="#offcanvasNavbar"
                aria-controls="offcanvasNavbar" aria-label="Toggle navigation">
                <img src="../../recursos/imagenes/logo.png" alt="Niki's" class=" rounded" alt="Nikis" width="125"
                    height="75">
                <span class=" navbar-toggler-icon"></span>
                <label for="navbar-toggler"> Menú</label>
            </button>

            <div class="button">
            </div>

            <div class="offcanvas offcanvas-start rounded" tabindex="-1" id="offcanvasNavbar"
                aria-labelledby="offcanvasNavbarLabel">
                <div class="offcanvas-header">
                    <h5 class="offcanvas-title" id="offcanvasNavbarLabel">Menu</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="offcanvas" aria-label="Close"></button>
                </div>
                <div class="offcanvas-body">
                    <ul class="navbar-nav justify-content-end flex-grow-1 pe-3">
                        <li class="nav-item">
                            <a class="nav-link active" aria-current="page" href="index.html"><i
                                    class="bi bi-house-door-fill"></i>
                                Página principal</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link active" aria-current="page" href="quienes_somos.html"><i
                                    class="bi bi-people-fill"></i>
                                Sobre nosotros</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link active" href="login.html"><i class="bi bi-box-arrow-in-right"></i>
                                Iniciar sesion</a>
                        </li>
                    </ul>
                </div>
            </div>
        </div>
    </nav>
</header>
`);
}

MAIN.insertAdjacentHTML('afterend', `
<footer class="bg-body-tertiary py-5">
    <div class="container-fluid clear">
        <ul class="nav justify-content-evenly ">
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
                    <img src="../../recursos/imagenes/logo.png" class="rounded" alt="Nikis" width="80" height="60">
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
        <hr>
        <p class="text-center text-muted">&copy; 2024 NIKI’S El Salvador . Todos los derechos reservados.</p>
    </div>
</footer>
`);
}