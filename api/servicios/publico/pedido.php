<?php
// Se incluye la clase del modelo.
require_once('../../modelos/data/pedido_data.php');

// Se comprueba si existe una acción a realizar, de lo contrario se finaliza el script con un mensaje de error.
if (isset($_GET['action'])) {
    // Se crea una sesión o se reanuda la actual para poder utilizar variables de sesión en el script.
    session_start();
    // Se instancia la clase correspondiente.
    $pedido = new PedidoData;
    // Se declara e inicializa un arreglo para guardar el resultado que retorna la API.
    $result = array('status' => 0, 'session' => 0, 'cliente' => 0 , 'recaptcha' => 0, 'message' => null, 'error' => null, 'exception' => null, 'username' => null);
    // Se verifica si existe una sesión iniciada como cliente para realizar las acciones correspondientes.
    if (isset($_SESSION['idCliente'])) {
        $result['session'] = 1;
        $result['cliente'] = $_SESSION['idCliente'];
        // Se compara la acción a realizar cuando un cliente ha iniciado sesión.
        switch ($_GET['action']) {
            // Acción para obtener los productos agregados en el carrito de compras.
            case 'readDetail':
                if ($result['dataset'] = $pedido->readDetail()) {
                    $result['status'] = 1;
                } else {
                    $result['error'] = 'No existen productos para mostrar en el carrito';
                }
                break;
                // Buscar historial
            case 'searchRows':
                if (!Validator::validateSearch($_POST['search'])) {
                    $result['error'] = Validator::getSearchError();
                } elseif ($result['dataset'] = $pedido->searchRows()) {
                    $result['status'] = 1;
                    $result['message'] = 'Existen ' . count($result['dataset']) . ' coincidencias';
                } else {
                    $result['error'] = 'No hay coincidencias';
                }
                break;
                // Acción para agregar un producto al carrito de compras.
            case 'manipulateDetail':
                $_POST = Validator::validateForm($_POST);
                if (
                    !$pedido->setCantidad($_POST['cantidad']) or
                    !$pedido->setProducto($_POST['idProducto'])
                ) {
                    $result['error'] = $pedido->getDataError();
                } elseif ($pedido->manipulateDetail()) {
                    $result['status'] = 1;
                    $result['message'] = 'Producto agregado correctamente';
                } else {
                    $result['error'] = 'Ocurrió un problema al agregar el producto';
                }
                break;
                // Acción para actualizar la cantidad de un producto en el carrito de compras.
            case 'updateDetail':
                $_POST = Validator::validateForm($_POST);
                if (
                    !$pedido->setIdDetalle($_POST['idDetalle']) or
                    !$pedido->setCantidad($_POST['cantidadProducto'])
                ) {
                    $result['error'] = $pedido->getDataError();
                } elseif ($pedido->updateDetail()) {
                    $result['status'] = 1;
                    $result['message'] = 'Cantidad modificada correctamente';
                } else {
                    $result['error'] = 'Ocurrió un problema al modificar la cantidad';
                }
                break;
                // Acción para remover un producto del carrito de compras.
            case 'deleteDetail':
                if (!$pedido->setIdDetalle($_POST['idDetalle'])) {
                    $result['error'] = $pedido->getDataError();
                } elseif ($pedido->deleteDetail()) {
                    $result['status'] = 1;
                    $result['message'] = 'Producto removido correctamente';
                } else {
                    $result['error'] = 'Ocurrió un problema al remover el producto';
                }
                break;
                // Acción para finalizar el carrito de compras.
            case 'finishOrder':
                if ($pedido->finishOrder()) {
                    $result['status'] = 1;
                    $result['message'] = 'Pedido finalizado correctamente';
                } else {
                    $result['error'] = 'Ocurrió un problema al finalizar el pedido';
                }
                break;
                // Leer todos historial
            case 'readAll':
                if ($result['dataset'] = $pedido->readAllHistory()) {
                    $result['status'] = 1;
                    $result['message'] = 'Existen ' . count($result['dataset']) . ' registros';
                } else {
                    $result['error'] = 'No hay pedidos registrados';
                }
                break;
                // Estado
            case 'changeState':
                if (
                    !$pedido->setId($_POST['idPedido'])
                ) {
                    $result['error'] = $pedido->getDataError();
                } elseif ($pedido->changeState()) {
                    $result['status'] = 1;
                    $result['message'] = 'Estado del cliente cambiado correctamente';
                } else {
                    $result['error'] = 'Ocurrió un problema al alterar el estado del cliente';
                }
                break;
            default:
                $result['error'] = 'Acción no disponible dentro de la sesión';
        }
    } else {
         // Se compara la acción a realizar cuando un cliente no ha iniciado sesión.
         switch ($_GET['action']) {
            case 'manipulateDetail':
                $result['error'] = 'Debe iniciar sesión para agregar el producto al carrito';
                break;
            default:
                $result['error'] = 'Acción no disponible fuera de la sesión';
        }
        print(json_encode('Acceso denegado'));
    }
    // Se obtiene la excepción del servidor de base de datos por si ocurrió un problema.
    $result['exception'] = Database::getException();
    // Se indica el tipo de contenido a mostrar y su respectivo conjunto de caracteres.
    header('Content-type: application/json; charset=utf-8');
    // Se imprime el resultado en formato JSON y se retorna al controlador.
    print(json_encode($result));
} else {
    print(json_encode('Recurso no disponible'));
}
