/*
* Controlador es de uso general en las páginas web del sitio público.
* Sirve para manejar las plantillas del encabezado y pie del documento.
*/

// Constante para completar la ruta de la API.
const USER_API = 'services/public/cliente.php';
// Constante para establecer el elemento del contenido principal.
const MAIN = document.querySelector('main');
MAIN.style.paddingTop = '75px';
MAIN.style.paddingBottom = '100px';
MAIN.classList.add('container');
// Se establece el título de la página web.
// document.querySelector('title').textContent = 'CoffeeShop - Store';
// Constante para establecer el elemento del título principal.
// const MAIN_TITLE = document.getElementById('mainTitle');
// MAIN_TITLE.classList.add('text-center', 'py-3');

/* Función asíncrona para cargar el encabezado y pie del documento.
* Parámetros: ninguno.
* Retorno: ninguno.
*/
const loadTemplate = async () => {
MAIN.insertAdjacentHTML('beforebegin', `
<header class="header">
    <nav class="navbar bg-body-tertiary navegacion">
        <div class="container-fluid">
            <button class="navbar-toggler" type="button" data-bs-toggle="offcanvas" data-bs-target="#offcanvasNavbar"
                aria-controls="offcanvasNavbar" aria-label="Toggle navigation">
                <img src="/recursos/imagenes/logo.png" alt="Niki's class=" rounded" alt="Nikis" width="125" height="75">
                <span class=" navbar-toggler-icon"></span>
                <label for="navbar-toggler"> Menú</label>
            </button>

            <form class="d-flex buscador" role="Buscar articulo">
                <input class="form-control me-3" type="search" placeholder="Buscar articulo"
                    aria-label="Buscar articulo">
                <button class="btn btn-outline-success" type="submit"><i class="bi bi-search"></i></button>
            </form>

            <div class="button">
                <button class="btn btn-light" type="button" aria-expanded="false">
                    <img src="/recursos/imagenes/usuario.png" alt="" width="40" height="40">
                </button>
                <button class="btn btn-light" type="button" aria-expanded="false" data-bs-toggle="modal"
                    data-bs-target="#exampleModal">
                    <img src="/recursos/imagenes/carrito-de-compras.png" alt="" width="40" height="40">
                </button>
            </div>

            <div class="offcanvas offcanvas-start" tabindex="-1" id="offcanvasNavbar"
                aria-labelledby="offcanvasNavbarLabel">
                <div class="offcanvas-header">
                    <h5 class="offcanvas-title" id="offcanvasNavbarLabel">Menu</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="offcanvas" aria-label="Close"></button>
                </div>
                <div class="offcanvas-body">
                    <ul class="navbar-nav justify-content-end flex-grow-1 pe-3">
                        <li class="nav-item">
                            <a class="nav-link active" aria-current="page" href="#">Quienes somos</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="#">Misión</a>
                        </li>
                </div>
            </div>
        </div>

        <div class="botones">
            <a href="">
                <button class="botines">
                    <img src="../../recursos/imagenes/botas-de-futbol 1.svg" alt="">
                    <span><a href="pantallavermas.html">Botines deportivos</a></span>
                </button>
            </a>
            <a href="">
                <button class="botines">
                    <img src="../../recursos/imagenes/zapatos 6.svg" alt="">
                    <span>Zapatos deportivos</span>
                </button>
            </a>
            <a href="">
                <button class="botines">
                    <img src="../../recursos/imagenes/ropa-deportiva 6.svg" alt="">
                    <span>Ropa deportivos</span>
                </button>
            </a>
            <a href="">
                <button class="botines">
                    <img src="../../recursos/imagenes/camiseta-de-futbol 6.svg" alt="">
                    <span>Camisas coleccionables</span>
                </button>
            </a>
        </div>
    </nav>
</header>

`);
MAIN.insertAdjacentHTML('afterend', `
<footer class="py-3 bg-body-tertiary fixed-bottom">
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
}

// const loadTemplate = async () => {
// // Petición para obtener en nombre del usuario que ha iniciado sesión.
// const DATA = await fetchData(USER_API, 'getUser');
// // Se comprueba si el usuario está autenticado para establecer el encabezado respectivo.
// if (DATA.session) {
// // Se verifica si la página web no es el inicio de sesión, de lo contrario se direcciona a la página web principal.
// if (!location.pathname.endsWith('login.html')) {
// // Se agrega el encabezado de la página web antes del contenido principal.
// MAIN.insertAdjacentHTML('beforebegin', `
// <header>
    // // <nav class="navbar fixed-top navbar-expand-lg bg-body-tertiary">
    //     // <div class="container">
    //         // <a class="navbar-brand" href="index.html"><img src="../../resources/img/logo.png" height="50"
    //                 alt="CoffeeShop"></a>
    //         // <button class="navbar-toggler" type="button" data-bs-toggle="collapse"
    //             data-bs-target="#navbarNavAltMarkup" aria-controls="navbarNavAltMarkup" aria-expanded="false"
    //             aria-label="Toggle navigation">
    //             // <span class="navbar-toggler-icon"></span>
    //             // </button>
    //         // <div class="collapse navbar-collapse" id="navbarNavAltMarkup">
    //             // <div class="navbar-nav ms-auto">
    //                 // <a class="nav-link" href="index.html"><i class="bi bi-shop"></i> Catálogo</a>
    //                 // <a class="nav-link" href="cart.html"><i class="bi bi-cart"></i> Carrito</a>
    //                 // <a class="nav-link" href="#" onclick="logOut()"><i class="bi bi-box-arrow-left"></i> Cerrar
    //                     sesión</a>
                    // </div>
                // </div>
            // </div>
        // </nav>
    // </header>
// `);
// } else {
// location.href = 'index.html';
// }
// } else {
// // Se agrega el encabezado de la página web antes del contenido principal.
// MAIN.insertAdjacentHTML('beforebegin', `
// <header>
    // <nav class="navbar fixed-top navbar-expand-lg bg-body-tertiary">
        // <div class="container">
            // <a class="navbar-brand" href="index.html"><img src="../../resources/img/logo.png" height="50"
            //         alt="CoffeeShop"></a>
            // // <button class="navbar-toggler" type="button" data-bs-toggle="collapse"
            //     data-bs-target="#navbarNavAltMarkup" aria-controls="navbarNavAltMarkup" aria-expanded="false"
            //     aria-label="Toggle navigation">
                // <span class="navbar-toggler-icon"></span>
                // </button>
            // <div class="collapse navbar-collapse" id="navbarNavAltMarkup">
                // <div class="navbar-nav ms-auto">
                    // <a class="nav-link" href="index.html"><i class="bi bi-shop"></i> Catálogo</a>
                    // <a class="nav-link" href="signup.html"><i class="bi bi-person"></i> Crear cuenta</a>
                    // <a class="nav-link" href="login.html"><i class="bi bi-box-arrow-right"></i> Iniciar sesión</a>
                    // </div>
                // </div>
            // </div>
        // </nav>
    // </header>
// `);
// }
// // Se agrega el pie de la página web después del contenido principal.
// MAIN.insertAdjacentHTML('afterend', `
// <footer>
    // <nav class="navbar fixed-bottom bg-body-tertiary">
        // <div class="container">
            // <div>
                // <h6>CoffeeShop</h6>
                // <p><i class="bi bi-c-square"></i> 2018-2024 Todos los derechos reservados</p>
                // </div>
            // <div>
                // <h6>Contáctanos</h6>
                // <p><i class="bi bi-envelope"></i> dacasoft@outlook.com</p>
                // </div>
            // </div>
        // </nav>
    // </footer>
// `);
// }