<?php
// Se incluye la clase para trabajar con la base de datos.
require_once('../../ayudantes/base_datos.php');
/*
 *  Clase para manejar el comportamiento de los datos de la tabla CATEGORIA.
 */
class CategoriaHandler
{
    /*
     *  Declaración de atributos para el manejo de datos.
     */
    protected $id = null;
    protected $nombre = null;
    protected $imagen = null;

    // Constante para establecer la ruta de las imágenes.
    const RUTA_IMAGEN = '../../Imagenes/categorias/';

    /*
     *  Métodos para realizar las operaciones SCRUD (search, create, read, update, and delete).
     */

     // Función para buscar categorías.
    public function searchRows()
    {
        $value = '%' . Validator::getSearchValue() . '%';
        $sql = 'SELECT id_categoria, nombre
                FROM tb_categorias
                WHERE nombre LIKE ?
                ORDER BY id_categoria';
        $params = array($value, $value);
        return Database::getRows($sql, $params);
    }

    // Función para crear una categoría.
    public function createRow()
    {
        $sql = 'INSERT INTO tb_categorias(nombre, imagen)
                VALUES(?, ?)';
        $params = array($this->nombre, $this->imagen);
        return Database::executeRow($sql, $params);
    }

    // Función para leer categorías.
    public function readAll()
    {
        $sql = 'SELECT id_categoria, nombre, imagen
                FROM tb_categorias
                ORDER BY id_categoria';
        return Database::getRows($sql);
    }

    // Función para leer una categoría.
    public function readOne()
    {
        $sql = 'SELECT id_categoria, nombre, imagen
                FROM tb_categorias
                WHERE id_categoria = ?';
        $params = array($this->id);
        return Database::getRow($sql, $params);
    }

    // Función para leer un archivo.
    public function readFilename()
    {
        $sql = 'SELECT imagen_categoria
                FROM tb_categorias
                WHERE id_categoria = ?';
        $params = array($this->id);
        return Database::getRow($sql, $params);
    }

    // Función para actualizar una categoría.
    public function updateRow()
    {
        $sql = 'UPDATE tb_categorias
                SET imagen = ?, nombre = ?
                WHERE id_categoria = ?';
        $params = array($this->imagen, $this->nombre, $this->id);
        return Database::executeRow($sql, $params);
    }

    // Función para eliminar una categoria.
    public function deleteRow()
    {
        $sql = 'DELETE FROM tb_categorias
                WHERE id_categoria = ?';
        $params = array($this->id);
        return Database::executeRow($sql, $params);
    }
}
