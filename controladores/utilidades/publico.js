/*
* Controlador es de uso general en las páginas web del sitio público.
* Sirve para manejar las plantillas del encabezado y pie del documento.
*/

// Constante para completar la ruta de la API.
const USER_API = 'servicios/publico/cliente.php';
// Constante para establecer el elemento del contenido principal.
const MAIN = document.querySelector('main');
MAIN.style.paddingTop = '150px';
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
<header class="header fixed-top">
  <!-- barra de navegación -->
  <nav class="navbar bg-body-tertiary navegacion">
    <div class="container-fluid">
      <button class="navbar-toggler" type="button" data-bs-toggle="offcanvas" data-bs-target="#offcanvasNavbar"
        aria-controls="offcanvasNavbar" aria-label="Toggle navigation">
        <img src="../../recursos/imagenes/logo.png" alt="Niki's" class=" rounded" alt="Nikis" width="125" height="75">
        <span class=" navbar-toggler-icon"></span>
        <label for="navbar-toggler"> Menú</label>
      </button>

      <!-- barra de busqueda -->
      <form class="d-flex buscador" role="Buscar articulo">
        <input class="form-control me-3" type="search" placeholder="Buscar articulo" aria-label="Buscar articulo">
        <button class="btn btn-outline-success" type="submit"><i class="bi bi-search"></i></button>
      </form>

      <!-- contenido de carrito de compras -->
      <div class="button">
        <button class="btn btn-light" type="button" aria-expanded="false">
          <a href="/vistas/publico/historial.html"><img src="../../recursos/imagenes/historial.png" alt="" width="40"
              height="40"></a>
        </button>
        <button class="btn btn-light" type="button" aria-expanded="false" data-bs-toggle="modal"
          data-bs-target="#exampleModal">
          <img src="../../recursos/imagenes/carrito-de-compras.png" alt="" width="40" height="40">
        </button>
      </div>

      <div class="offcanvas offcanvas-start" tabindex="-1" id="offcanvasNavbar" aria-labelledby="offcanvasNavbarLabel">
        <div class="offcanvas-header">
          <h5 class="offcanvas-title" id="offcanvasNavbarLabel">Menu</h5>
          <button type="button" class="btn-close" data-bs-dismiss="offcanvas" aria-label="Close"></button>
        </div>
        <div class="offcanvas-body">
          <ul class="navbar-nav justify-content-end flex-grow-1 pe-3">
            <li class="nav-item">
              <a class="nav-link active" aria-current="page" href="index.html"><i class="bi bi-house-door-fill"></i>
                Home</a>
            </li>
            <li class="nav-item">
              <a class="nav-link active" aria-current="page" href="quienes_somos.html"><i class="bi bi-people-fill"></i>
                ¿Quienes
                somos?</a>
            </li>
            <li class="nav-item">
              <a class="nav-link active" href="perfil_cliente.html"><i class="bi bi-person-fill-gear"></i> Perfil</a>
            </li>
            <li class="nav-item">
            <a class="nav-link active" href="login.html"><i class="bi bi-box-arrow-in-right"></i> Iniciar sesion</a>
          </li>
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

<!-- Modal -->
<div class="modal fade" id="exampleModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="exampleModalLabel">Carrito de compras</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
        <div class="card mb-3 d-flex">
          <div class="row g-0">
            <div class="d-flex justify-content-end align-items-center">
              <a><img src="/recursos/imagenes/basurero.svg" class="d-block" height="50" alt="Imagen 1"></a>
            </div>
            <div class="col-md-5">
              <img src="/recursos/imagenes/botas6 1.svg" class="d-block w-100" height="200" alt="Imagen 1">
            </div>
            <div class="col-md-7">
              <div class="card-body">
                <h5 class="card-title text-center">Nike</h5>
                <p class="letra1 text-center">Botines Nike color blanco con tarugos anaranjados</p>
                <div class="row">
                  <div class="col-md-6 text-start">
                    <p class="card-title text-start">Descripción:</p>
                    <strong>-Para cancha engramada</strong><br>
                    <strong>-Para cancha sintetica</strong>
                  </div>
                  <div class="col-md-6 text-start">
                    <p>AHORA</p>
                    <p class="letra2">$199.99</p>
                  </div>
                </div>
                <div class="text-center">
                  <button class="botines text-primary"><a href="pantallahistorial.html">Historial de
                      compras</a></button>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cerrar</button>
      </div>
    </div>
  </div>
</div>

`);
MAIN.insertAdjacentHTML('afterend', `
<footer class="bg-body-tertiary">
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
