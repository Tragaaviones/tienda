<?php
// Se incluye la clase del modelo.
require_once('../../modelos/data/inventario_data.php');

// Se comprueba si existe una acción a realizar, de lo contrario se finaliza el script con un mensaje de error.
if (isset($_GET['action'])) {
    // Se crea una sesión o se reanuda la actual para poder utilizar variables de sesión en el script.
    session_start();
    // Se instancia la clase correspondiente.
    $inventario = new InventarioData;
    // Se declara e inicializa un arreglo para guardar el resultado que retorna la API.
    $result = array('status' => 0, 'message' => null, 'dataset' => null, 'error' => null, 'exception' => null, 'fileStatus' => null);
    // Se verifica si existe una sesión iniciada como administrador, de lo contrario se finaliza el script con un mensaje de error.
    if (isset($_SESSION['id_administrador'])) {
        // Se compara la acción a realizar cuando un administrador ha iniciado sesión.
        switch ($_GET['action']) {
            case 'searchRows':
                if (!Validator::validateSearch($_POST['search'])) {
                    $result['error'] = Validator::getSearchError();
                } elseif ($result['dataset'] = $inventario->searchRows()) {
                    $result['status'] = 1;
                    $result['message'] = 'Existen ' . count($result['dataset']) . ' coincidencias';
                } else {
                    $result['error'] = 'No hay coincidencias';
                }
                break;
            case 'createRow':
                $_POST = Validator::validateForm($_POST);
                if (
                    !$inventario->setProducto($_POST['inventario_producto']) or
                    !$inventario->setTalla($_POST['inventario_talla']) or
                    !$inventario->setExistencias($_POST['stock'])
                ) {
                    $result['error'] = $inventario->getDataError();
                } elseif ($inventario->createRow()) {
                    $result['status'] = 1;
                    $result['message'] = 'Inventario creado correctamente';
                } else {
                    $result['error'] = 'Ocurrió un problema al crear el inventario';
                }
                break;
            case 'readAll':
                if ($result['dataset'] = $inventario->readAll()) {
                    $result['status'] = 1;
                    $result['message'] = 'Existen ' . count($result['dataset']) . ' registros';
                } else {
                    $result['error'] = 'No existen productos registrados';
                }
                break;
            case 'readOne':
                if (!$inventario->setId($_POST['id_inventario'])) {
                    $result['error'] = $inventario->getDataError();
                } elseif ($result['dataset'] = $inventario->readOne()) {
                    $result['status'] = 1;
                } else {
                    $result['error'] = 'Inventario inexistente';
                }
                break;
            case 'updateRow':
                $_POST = Validator::validateForm($_POST);
                if (
                    !$inventario->setProducto($_POST['inventario_producto']) or
                    !$inventario->setTalla($_POST['inventario_talla']) or
                    !$inventario->setExistencias($_POST['stock']) or
                    !$inventario->setId($_POST['id_inventario'])
                ) {
                    $result['error'] = $inventario->getDataError();
                } elseif ($inventario->updateRow()) {
                    $result['status'] = 1;
                    $result['message'] = 'Producto modificado correctamente';
                } else {
                    $result['error'] = 'Ocurrió un problema al modificar el inventario';
                }
                break;
            case 'deleteRow':
                if (
                    !$inventario->setId($_POST['id_inventario'])
                ) {
                    $result['error'] = $inventario->getDataError();
                } elseif ($inventario->deleteRow()) {
                    $result['status'] = 1;
                    $result['message'] = 'Inventaio eliminado correctamente';
                } else {
                    $result['error'] = 'Ocurrió un problema al eliminar el inventario';
                }
                break;
            default:
                $result['error'] = 'Acción no disponible dentro de la sesión';
        }
        // Se obtiene la excepción del servidor de base de datos por si ocurrió un problema.
        $result['exception'] = Database::getException();
        // Se indica el tipo de contenido a mostrar y su respectivo conjunto de caracteres.
        header('Content-type: application/json; charset=utf-8');
        // Se imprime el resultado en formato JSON y se retorna al controlador.
        print(json_encode($result));
    } else {
        print(json_encode('Acceso denegado'));
    }
} else {
    print(json_encode('Recurso no disponible'));
}
