<?php
// Se incluye la clase para trabajar con la base de datos.
require_once ('../../ayudantes/base_datos.php');
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
    protected $direccion = null;
    protected $clave = null;
    protected $estado = null;
    protected $token = null;


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

        // Función para leer un cliente
        public function readOne()
        {
            $sql = 'SELECT id_cliente AS ID, nombre_cliente AS NOMBRE, apellido_cliente AS APELLIDO, correo_cliente AS CORREO,
            telefono_cliente AS TELEFONO FROM tb_clientes
                    ORDER BY NOMBRE;';
            return Database::getRows($sql);
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


    public function graficoState()
    {
        $sql = 'SELECT estado_cliente AS ESTADO, COUNT(*) AS CANTIDAD
        FROM tb_clientes 
        GROUP BY estado_cliente;';
        return Database::getRows($sql);
    }

    //Funcion para poder registrarse (crear un cliente)
    public function createRow()
    {
        $sql = 'INSERT INTO tb_clientes(clave_cliente, nombre_cliente, apellido_cliente, correo_cliente, telefono_cliente, direccion_cliente, estado_cliente, token_cliente)
                VALUES(?, ?, ?, ?, ?, ?, 1 ,?)';
        $params = array($this->clave, $this->nombre, $this->apellido, $this->correo, $this->telefono, $this->direccion, $this->token);
        return Database::executeRow($sql, $params);
    }

    //Funcion para llenar los campos del perfil de usuario
    public function readProfile()
    {
        $sql = 'SELECT id_cliente, nombre_cliente, apellido_cliente, correo_cliente, telefono_cliente, direccion_cliente
                FROM tb_clientes
                WHERE id_cliente = ?';
        $params = array($_SESSION['idCliente']);
        return Database::getRow($sql, $params);
    }

    //Funcion para actualizar perfil del usuario
    public function editProfile()
    {
        $sql = 'UPDATE tb_clientes
                SET nombre_cliente = ?, apellido_cliente = ?, correo_cliente = ?, telefono_cliente = ?, direccion_cliente = ?
                WHERE id_cliente = ?';
        $params = array($this->nombre, $this->apellido, $this->correo, $this->telefono, $this->direccion, $_SESSION['idCliente']);
        return Database::executeRow($sql, $params);
    }

    public function readOneCorreo()
    {
        $sql = 'SELECT id_cliente, nombre_cliente, apellido_cliente, correo_cliente, telefono_cliente, direccion_cliente, estado_cliente
            FROM tb_clientes
            WHERE correo_cliente = ?';
        $params = array($_SESSION['correoCliente']);
        return Database::getRow($sql, $params);
    }

    //Función para cambiar el estado de un cliente.
    public function changeState()
    {
        $sql = 'CALL cambiar_estado_cliente(?);';
        $params = array($this->id);
        return Database::executeRow($sql, $params);
    }

    
    public function pedidosCliente()
    {
        $sql = 'SELECT  nombre_cliente, correo_cliente, direccion_cliente, fecha_venta, nombre_producto, precio_unitario
                FROM tb_clientes 
                INNER JOIN tb_pedidos USING(id_cliente)
                INNER JOIN tb_detalle_pedidos USING(id_pedido)
                INNER JOIN tb_detalle_productos USING(id_detalle_producto)
                INNER JOIN tb_productos USING(id_producto)
                WHERE id_cliente = ?
                ORDER BY nombre_producto';
        $params = array($this->id);
        return Database::getRows($sql, $params);
    }

    //Metodos para la publica

    /*
     *   Métodos para gestionar la cuenta del cliente.
     */
    //Función para el inicio de sesión (se piden dos parametros uno para el email y el otro para la contraseña)
    public function checkUser($mail, $password)
    {
        //Consulta sql para ejecutar la consulta (se pide un parametro identificado como ?, para verificar el correo)
        $sql = 'SELECT id_cliente, nombre_cliente, correo_cliente, clave_cliente, estado_cliente
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
            $this->nombre = $data['nombre_cliente'];
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
            $_SESSION['nombreCliente'] = $this->nombre;
            //se retorna true si es correcta la verificación del estado
            return true;
        }
        //en caso que el estado sea inactivo o bloqueado
        else {
            //se retorna falso y no se dejara iniciar sesión
            return false;
        }
    }

    public function changePassword()
    {
        $sql = 'UPDATE tb_clientes
                SET clave_cliente = ?
                WHERE id_cliente = ?';
        $params = array($this->clave, $_SESSION['idCliente']);
        return Database::executeRow($sql, $params);
    }
    public function changePassword2()
    {
        $sql = 'UPDATE tb_clientes
                SET clave_cliente = ?
                WHERE id_cliente = ?';
        $params = array($this->clave, $this->id);
        return Database::executeRow($sql, $params);
    }
    function generarCodigoAleatorio($longitud = 10)
    {
        $codigo = '';
        for ($i = 0; $i < $longitud; $i++) {
            $codigo .= mt_rand(0, 9);
        }
        return $codigo;
    }


    // Función para comprobar la contraseña.
    public function checkPassword($password)
    {
        $sql = 'SELECT clave_cliente
                    FROM tb_clientes
                    WHERE id_cliente = ?';
        $params = array($_SESSION['idCliente']);
        $data = Database::getRow($sql, $params);
        // Se verifica si la contraseña coincide con el hash almacenado en la base de datos.
        if (password_verify($password, $data['clave_cliente'])) {
            return true;
        } else {
            return false;
        }
    }

    public function checkToken($mail, $token)
    {
        $sql = 'SELECT id_cliente, correo_cliente, token_cliente, estado_cliente
                FROM tb_clientes
                WHERE correo_cliente = ?';
        $params = array($mail);
        $data = Database::getRow($sql, $params);
        if (password_verify($token, $data['token_cliente'])) {
            $this->id = $data['id_cliente'];
            $this->correo = $data['correo_cliente'];
            $this->estado = $data['estado_cliente'];
            return true;
        } else {
            return false;
        }
    }
}
