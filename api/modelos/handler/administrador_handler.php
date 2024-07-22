<?php
// Se incluye la clase para trabajar con la base de datos.
require_once('../../ayudantes/base_datos.php');
/*
 *  Clase para manejar el comportamiento de los datos de la tabla administrador.
 */
class AdministradorHandler
{
    /*
     *  Declaración de atributos para el manejo de datos.
     */
    protected $id = null;
    protected $nombre = null;
    protected $apellido = null;
    protected $correo = null;
    protected $alias = null;
    protected $clave = null;

    protected $tipo = null;

    /*
     *  Métodos para gestionar la cuenta del administrador.
     */

     // Función para el login.
    public function checkUser($username, $password)
    {
        $sql = 'SELECT id_administrador, clave_administrador, nombre_administrador
                FROM tb_administradores
                WHERE  correo_administrador = ?';
        $params = array($username);
        if (!($data = Database::getRow($sql, $params))) {
            return false;
        } elseif (password_verify($password, $data['clave_administrador'])) {
            $_SESSION['id_administrador'] = $data['id_administrador'];
            $_SESSION['nombre_administrador'] = $data['nombre_administrador'];
            return true;
        } else {
            return false;
        }
    }

    // Función para comprobar la contraseña.
    public function checkPassword($password)
    {
        $sql = 'SELECT clave_administrador
                FROM tb_administradores
                WHERE id_administrador = ?';
        $params = array($_SESSION['id_administrador']);
        $data = Database::getRow($sql, $params);
        // Se verifica si la contraseña coincide con el hash almacenado en la base de datos.
        if (password_verify($password, $data['clave_administrador'])) {
            return true;
        } else {
            return false;
        }
    }

    // Función para cambiar contraseña.
    public function changePassword()
    {
        $sql = 'UPDATE administrador
                SET clave_administrador = ?
                WHERE id_administrador = ?';
        $params = array($this->clave, $_SESSION['idadministrador']);
        return Database::executeRow($sql, $params);
    }

    // Función para buscar los tipos de usuarios.
    public function readProfile()
    {
        $sql = 'SELECT id_administrador, nombre_administrador, apellido_administrador, correo_administrador, alias_administrador
                FROM administrador
                WHERE id_administrador = ?';
        $params = array($_SESSION['idAdministrador']);
        return Database::getRow($sql, $params);
    }

    // Función para leer perfil.
    public function editProfile()
    {
        $sql = 'UPDATE administrador
                SET nombre_administrador = ?, apellido_administrador = ?, correo_administrador = ?, alias_administrador = ?
                WHERE id_administrador = ?';
        $params = array($this->nombre, $this->apellido, $this->correo, $this->alias, $_SESSION['idAdministrador']);
        return Database::executeRow($sql, $params);
    }

    /*
     *  Métodos para realizar las operaciones SCRUD (search, create, read, update, and delete).
     */

    // Función para buscar los administradores.
    public function searchRows()
    {
        $value = '%' . Validator::getSearchValue() . '%';
        $sql = 'SELECT id_administrador, nombre_administrador, apellido_administrador, correo_administrador, id_tipo, tipo_usuario
                FROM tb_administradores
                INNER JOIN tb_tipousuarios USING (id_tipo)
                WHERE nombre_administrador LIKE ? OR apellido_administrador LIKE ?
                ORDER BY id_administrador';
        $params = array($value, $value);
        return Database::getRows($sql, $params);
    }

    // Función para crear un admministrador.
    public function createRow()
    {
        $sql = 'INSERT INTO tb_administradores(nombre_administrador, apellido_administrador, correo_administrador, clave_administrador,id_tipo)
                VALUES(?, ?, ?, ?, ?)';
        $params = array($this->nombre, $this->apellido, $this->correo, $this->clave, $this->tipo);
        return Database::executeRow($sql, $params);
    }

    // Función para primer uso.
    public function firstUser()
    {
        $sql = 'INSERT INTO tb_administradores(nombre_administrador, apellido_administrador, correo_administrador, clave_administrador,id_tipo)
                VALUES(?, ?, ?, ?, 1)';
        $params = array($this->nombre, $this->apellido, $this->correo, $this->clave);
        return Database::executeRow($sql, $params);
    }

    // Función para leer administradores.
    public function readAll()
    {
        $sql = 'SELECT id_administrador, nombre_administrador, apellido_administrador, correo_administrador, id_tipo, tipo_usuario
                FROM tb_administradores
                INNER JOIN tb_tipousuarios USING (id_tipo)
                ORDER BY id_administrador';
        return Database::getRows($sql);
    }

    // Función para leer un administrador.
    public function readOne()
    {
        $sql = 'SELECT id_administrador, nombre_administrador, apellido_administrador, correo_administrador, id_tipo, tipo_usuario
                FROM tb_administradores
                INNER JOIN tb_tipousuarios USING (id_tipo)
                WHERE id_administrador = ?';
        $params = array($this->id);
        return Database::getRow($sql, $params);
    }

    // Función para eliminar un administrador.
    public function updateRow()
    {
        $sql = 'UPDATE tb_administradores
                SET nombre_administrador = ?, apellido_administrador = ?, correo_administrador = ?, id_tipo = ?
                WHERE id_administrador = ?';
        $params = array($this->nombre, $this->apellido, $this->correo, $this->tipo, $this->id);
        return Database::executeRow($sql, $params);
    }

    // Función para eliminar un administrador.
    public function deleteRow()
    {
        $sql = 'DELETE FROM tb_administradores
                WHERE id_administrador = ?';
        $params = array($this->id);
        return Database::executeRow($sql, $params);
    }
}
