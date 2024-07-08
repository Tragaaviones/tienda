<?php
// Se incluye la clase del modelo.
require_once ('../../modelos/data/cliente_data.php');

// Se comprueba si existe una acción a realizar, de lo contrario se finaliza el script con un mensaje de error.
if (isset($_GET['action'])) {
    // Se crea una sesión o se reanuda la actual para poder utilizar variables de sesión en el script.
    session_start();
    // Se instancia la clase correspondiente.
    $cliente = new ClientesData;
    // Se declara e inicializa un arreglo para guardar el resultado que retorna la API.
    $result = array('status' => 0, 'session' => 0, 'recaptcha' => 0, 'message' => null, 'error' => null, 'exception' => null, 'username' => null);
    // Se verifica si existe una sesión iniciada como cliente para realizar las acciones correspondientes.
    if (isset($_SESSION['idCliente'])) {
        $result['session'] = 1;
        // Se compara la acción a realizar cuando un cliente ha iniciado sesión.
        switch ($_GET['action']) {
            case 'getUser':
                if (isset($_SESSION['correo_cliente'])) {
                    $result['status'] = 1;
                    $result['username'] = $_SESSION['correo_cliente'];
                    $result['name'] = $cliente->readOneCorreo($_SESSION['correo_cliente']);
                } else {
                    $result['error'] = 'Correo de usuario indefinido';
                }
                break;
            case 'readProfile':
                if ($result['dataset'] = $cliente->readProfile()) {
                    $result['status'] = 1;
                } else {
                    $result['error'] = 'Ocurrió un problema al leer el perfil';
                }
                break;
            case 'editProfile':
                $_POST = Validator::validateForm($_POST);
                if (
                    !$cliente->setNombre($_POST['nombre_perfil']) or
                    !$cliente->setApellido($_POST['apellido_perfil']) or
                    !$cliente->setCorreo($_POST['correo_perfil']) or
                    !$cliente->setTelefono($_POST['telefono_perfil']) or
                    !$cliente->setDireccion($_POST['direccion_perfil'])
                ) {
                    $result['error'] = $cliente->getDataError();
                } elseif ($cliente->editProfile()) {
                    $result['status'] = 1;
                    $result['message'] = 'Perfil modificado correctamente';
                    $_SESSION['correo_perfil'] = $_POST['correo_perfil'];
                } else {
                    $result['error'] = 'Ocurrió un problema al modificar el perfil';
                }
                break;
            case 'changePassword':
                $_POST = Validator::validateForm($_POST);
                if (!$cliente->checkPassword($_POST['contra_actual'])) {
                    $result['error'] = 'Contraseña actual incorrecta';
                } elseif ($_POST['contra_reciente'] != $_POST['repetir_contra']) {
                    $result['error'] = 'Confirmación de contraseña diferente';
                } elseif (!$cliente->setClave($_POST['contra_reciente'])) {
                    $result['error'] = $cliente->getDataError();
                } elseif ($cliente->changePassword()) {
                    $result['status'] = 1;
                    $result['message'] = 'Contraseña cambiada correctamente';
                } else {
                    $result['error'] = 'Ocurrió un problema al cambiar la contraseña';
                }
                break;
            case 'logOut':
                if (session_destroy()) {
                    $result['status'] = 1;
                    $result['message'] = 'Sesión eliminada correctamente';
                } else {
                    $result['error'] = 'Ocurrió un problema al cerrar la sesión';
                }
                break;
            default:
                $result['error'] = 'Acción no disponible dentro de la sesión';
        }
    } else {
        // Se compara la acción a realizar cuando el cliente no ha iniciado sesión.
        switch ($_GET['action']) {
            case 'signUp':
                $_POST = Validator::validateForm($_POST);
                if (
                    !$cliente->setNombre($_POST['nombre_cliente']) or
                    !$cliente->setApellido($_POST['apellido_cliente']) or
                    !$cliente->setCorreo($_POST['correo_cliente']) or
                    !$cliente->setDireccion($_POST['direccion_cliente']) or
                    !$cliente->setTelefono($_POST['telefono_cliente']) or
                    !$cliente->setClave($_POST['contra_cliente'])
                ) {
                    $result['error'] = $cliente->getDataError();
                } elseif ($_POST['contra_cliente'] != $_POST['confirmar_contra']) {
                    $result['error'] = 'Contraseñas diferentes';
                } elseif ($cliente->createRow()) {
                    $result['status'] = 1;
                    $result['message'] = 'Cuenta registrada correctamente';
                } else {
                    $result['error'] = 'Ocurrió un problema al registrar la cuenta';
                }
                break;
                case 'signUpMovil':
                    $_POST = Validator::validateForm($_POST);
                if (
                        !$cliente->setNombre($_POST['nombre_cliente']) or
                        !$cliente->setApellido($_POST['apellido_cliente']) or
                        !$cliente->setCorreo($_POST['correo_cliente']) or
                        !$cliente->setDireccion($_POST['direccion_cliente']) or
                        !$cliente->setTelefono($_POST['telefono_cliente']) or
                        !$cliente->setClave($_POST['contra_cliente'])
                    ) {
                        $result['error'] = $cliente->getDataError();
                    } elseif ($_POST['contra_cliente'] != $_POST['confirmar_contra']) {
                        $result['error'] = 'Contraseñas diferentes';
                    } elseif ($cliente->createRow()) {
                        $result['status'] = 1;
                        $result['message'] = 'Cuenta registrada correctamente';
                    } else {
                        $result['error'] = 'Ocurrió un problema al registrar la cuenta';
                    }
                    break;
            case 'logIn':
                $_POST = Validator::validateForm($_POST);
                if (!$cliente->checkUser($_POST['email'], $_POST['password'])) {
                    $result['error'] = 'Datos incorrectos';
                } elseif ($cliente->checkStatus()) {
                    $result['status'] = 1;
                    $result['message'] = 'Autenticación correcta';
                } else {
                    $result['error'] = 'La cuenta ha sido desactivada';
                }
                break;
            default:
                $result['error'] = 'Acción no disponible fuera de la sesión';
        }
    }
    // Se obtiene la excepción del servidor de base de datos por si ocurrió un problema.
    $result['exception'] = Database::getException();
    // Se indica el tipo de contenido a mostrar y su respectivo conjunto de caracteres.
    header('Content-type: application/json; charset=utf-8');
    // Se imprime el resultado en formato JSON y se retorna al controlador.
    print (json_encode($result));
} else {
    print (json_encode('Recurso no disponible'));
}
