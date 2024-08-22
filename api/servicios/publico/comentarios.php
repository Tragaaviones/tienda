<?php
// Se incluye la clase del modelo.
require_once('../../modelos/data/comentario_data.php');
// Se comprueba si existe una acción a realizar, de lo contrario se finaliza el script con un mensaje de error.
if (isset($_GET['action'])) {
    // Se crea una sesión o se reanuda la actual para poder utilizar variables de sesión en el script.
    session_start();
    // Se instancia la clase correspondiente.
    $comentario = new ComentarioData;
    // Se declara e inicializa un arreglo para guardar el resultado que retorna la API.
    $result = array('status' => 0, 'session' => 0, 'recaptcha' => 0, 'message' => null, 'error' => null, 'exception' => null, 'username' => null);
    // Se verifica si existe una sesión iniciada como administrador, de lo contrario se finaliza el script con un mensaje de error.
    if (isset($_SESSION['idCliente'])) {
        $result['session'] = 1;
        // Se compara la acción a realizar cuando un administrador ha iniciado sesión.
        switch ($_GET['action']) {
                // Acción para agregar un comentario al producto.
                case 'createRow':
                    $_POST = Validator::validateForm($_POST);
                    if (
                        !$comentario->setCalificacion($_POST['VALORACION']) or
                        !$comentario->setComentario($_POST['comentario'])
                    ) {
                        $result['error'] = $comentario->getDataError();
                    } elseif ($comentario->createRow()) {
                        $result['status'] = 1;
                        $result['message'] = 'Comentario agregado correctamente';
                    } else {
                        $result['error'] = 'Ocurrió un problema al agregar el comentario';
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
