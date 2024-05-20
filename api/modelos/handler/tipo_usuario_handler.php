<?php
// Se incluye la clase para trabajar con la base de datos.
require_once('../../ayudantes/base_datos.php');
/*
 *  Clase para manejar el comportamiento de los datos de la tabla CATEGORIA.
 */
class Tipo_usuario_handler
{
    /*
     *  Declaración de atributos para el manejo de datos.
     */
    protected $id = null;
    protected $nombre = null;
    protected $descripcion = null;

    // Constante para establecer la ruta de las imágenes.
    // const RUTA_IMAGEN = '../../imagenes/categorias';

    /*
     *  Métodos para realizar las operaciones SCRUD (search, create, read, update, and delete).
     */

     // Función para buscar los tipos de usuarios.
    public function searchRows()
    {
        $value = '%' . Validator::getSearchValue() . '%';
        $sql = 'SELECT id_tipo, tipo_usuario, descripcion_usuario
                FROM tb_tipousuarios
                WHERE tipo_usuario LIKE ?
                ORDER BY id_tipo';
        $params = array($value);
        return Database::getRows($sql, $params);
    }

    // Función para crear un tipo usuario.
    public function createRow()
    {
        $sql = 'INSERT INTO tb_tipousuarios(tipo_usuario, descripcion_usuario)
                VALUES(?, ?)';
        $params = array($this->nombre, $this->descripcion);
        return Database::executeRow($sql, $params);
    }

    // Función para leer todos los tipos de usuarios.
    public function readAll()
    {
        $sql = 'SELECT id_tipo, tipo_usuario, descripcion_usuario
                FROM tb_tipousuarios
                ORDER BY id_tipo';
        return Database::getRows($sql);
    }

    // Función para leer un tipo de usuario.
    public function readOne()
    {
        $sql = 'SELECT id_tipo, tipo_usuario, descripcion_usuario
                FROM tb_tipousuarios
                WHERE id_tipo = ?';
        $params = array($this->id);
        return Database::getRow($sql, $params);
    }

    // public function readFilename()
    // {
    //     $sql = 'SELECT imagen_categoria
    //             FROM categoria
    //             WHERE id_categoria = ?';
    //     $params = array($this->id);
    //     return Database::getRow($sql, $params);
    // }

    // Función para actualizar el tipo de usuario.
    public function updateRow()
    {
        $sql = 'UPDATE tb_tipousuarios
                SET tipo_usuario = ?, descripcion_usuario = ?
                WHERE id_tipo = ?';
        $params = array($this->nombre, $this->descripcion, $this->id);
        return Database::executeRow($sql, $params);
    }

    // Función para eliminar el tipo de usuario.
    public function deleteRow()
    {
        $sql = 'DELETE FROM tb_tipousuarios
                WHERE id_tipo = ?';
        $params = array($this->id);
        return Database::executeRow($sql, $params);
    }
}
