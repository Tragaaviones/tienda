<?php
// Se incluye la clase para trabajar con la base de datos.
require_once('../../ayudantes/base_datos.php');
/*
 *  Clase para manejar el comportamiento de los datos de la tabla administrador.
 */
class ClientesHandler
{
    /*
     *  Declaración de atributos para el manejo de datos.
     */
    protected $id = null;
    protected $nombre = null;
    protected $apellido = null;
    protected $correo = null;
    protected $telefono = null;
    protected $dui = null;
    protected $nacimiento = null;
    protected $direccion = null;
    protected $clave = null;
    protected $estado = null;
    protected $genero = null;


    /*
     *  Métodos para realizar las operaciones SCRUD (search, create, read, update, and delete).
     */

    // Función para buscar un cliente
    public function searchRows()
    {
        $value = '%' . Validator::getSearchValue() . '%';
        $sql = 'SELECT id_cliente AS ID, nombre_cliente AS NOMBRE, correo_cliente AS CORREO,
        telefono_cliente AS TELEFONO, CASE 
        WHEN estado_cliente = 1 THEN "Activo"
        WHEN estado_cliente = 0 THEN "Bloqueado"
        END AS ESTADO FROM tb_clientes
                WHERE nombre_cliente LIKE ?
                ORDER BY NOMBRE;';
        $params = array($value);
        return Database::getRows($sql, $params);
    }
    // Función para leer todos los clientes
    public function readAll()
    {
        $sql = 'SELECT id_cliente AS ID, nombre_cliente AS NOMBRE, apellido_cliente AS APELLIDO, correo_cliente AS CORREO,
        telefono_cliente AS TELEFONO, CASE 
        WHEN estado_cliente = 1 THEN "Activo"
        WHEN estado_cliente = 0 THEN "Bloqueado"
        END AS ESTADO FROM tb_clientes
                ORDER BY NOMBRE;';
        return Database::getRows($sql);
    }

    public function createRow()
    {
        $sql = 'INSERT INTO tb_clientes(clave_cliente, nombre_cliente, apellido_cliente, correo_cliente, telefono_cliente, direccion_cliente, estado_cliente)
                VALUES(?, ?, ?, ?, ?, ?, 1)';
        $params = array($this->clave, $this->nombre, $this->apellido, $this->correo, $this->telefono, $this->direccion);
        return Database::executeRow($sql, $params);
    }

    //Función para cambiar el estado de un cliente.
    public function changeState()
    {
        $sql = 'CALL cambiar_estado_cliente(?);';
        $params = array($this->id);
        return Database::executeRow($sql, $params);
    }

    //Metodos para la publica

    /*
    *   Métodos para gestionar la cuenta del cliente.
    */
    //Función para el inicio de sesión (se piden dos parametros uno para el email y el otro para la contraseña)
    public function checkUser($mail, $password)
    {
        //Consulta sql para ejecutar la consulta (se pide un parametro identificado como ?, para verificar el correo)
        $sql = 'SELECT id_cliente, correo_cliente, clave_cliente, estado_cliente
                FROM tb_clientes
                WHERE correo_cliente = ?';
        //arreglo que inserta todas los parametros que se van a utilizar
        $params = array($mail);
        //variable data, que se ocupa para ejecutar la consulta con la base de datos, se llama a la clase DataBase
        //y se ejecuta la función getRow para traer solo un dato de la base.
        $data = Database::getRow($sql, $params);
        //se llama a una función propia de php llamada password_verify que sirve para comparar encriptada la clave
        if (password_verify($password, $data['clave_cliente'])) {
            //se dan valores a las variables protegidas, con el fin de mandarlas al metodo checkStatus luego de que el
            //inicio de sesión sea exitoso.
            $this->id = $data['id_cliente'];
            $this->correo = $data['correo_cliente'];
            $this->estado = $data['estado_cliente'];
            //se retorna true si es exitoso la verificación
            return true;
        } else {
            //se retorna false si es fallida la verificación
            return false;
        }
    }

    //Función para chequear el estado de la cuenta del cliente
    public function checkStatus()
    {
        //se verifica si el estado es activo
        if ($this->estado) {
            //se crea variable de sesión llamada idCliente, para verificar que exista una sesión iniciada
            $_SESSION['idCliente'] = $this->id;
            //se crea variable de sesión llamada correoCliente para alguna verificación que se pueda utilizar con esta
            //ya sea para el perfil o alguna otra cosa mas
            $_SESSION['correoCliente'] = $this->correo;
            //se retorna true si es correcta la verificación del estado
            return true;
        } 
        //en caso que el estado sea inactivo o bloqueado
        else {
            //se retorna falso y no se dejara iniciar sesión
            return false;
        }
    }
}
