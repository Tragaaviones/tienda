<?php
// Se incluye la clase para trabajar con la base de datos.
require_once ('../../ayudantes/base_datos.php');
/*
 *  Clase para manejar el comportamiento de los datos de la tabla CATEGORIA.
 */
class Talla_handler
{
    /*
     *  Declaración de atributos para el manejo de datos.
     */
    protected $id = null;
    protected $talla = null;

    // Constante para establecer la ruta de las imágenes.
    // const RUTA_IMAGEN = '../../imagenes/categorias';

    /*
     *  Métodos para realizar las operaciones SCRUD (search, create, read, update, and delete).
     */

    // Función para buscar tallas.
    public function searchRows()
    {
        $value = '%' . Validator::getSearchValue() . '%';
        $sql = 'SELECT id_talla, talla
                FROM tb_tallas
                WHERE talla LIKE ?
                ORDER BY id_talla';
        $params = array($value);
        return Database::getRows($sql, $params);
    }

    // Función para crear una talla.
    public function createRow()
    {
        $sql = 'INSERT INTO tb_tallas(talla)
                VALUES(?)';
        $params = array($this->talla);
        return Database::executeRow($sql, $params);
    }


    // Función para leer todas tallas.
    public function readAll()
    {
        $sql = 'SELECT id_talla, talla
                FROM tb_tallas
                ORDER BY id_talla';
        return Database::getRows($sql);
    }

    // Función para leer una talla.
    public function readOne()
    {
        $sql = 'SELECT id_talla, talla
                FROM tb_tallas
                WHERE id_talla = ?';
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

    // Función para actualizar tallas.
    public function updateRow()
    {
        $sql = 'UPDATE tb_tallas
                SET talla = ?
                WHERE id_talla = ?';
        $params = array($this->talla, $this->id);
        return Database::executeRow($sql, $params);
    }

    // Función para eliminar tallas.
    public function deleteRow()
    {
        $sql = 'DELETE FROM tb_tallas
                WHERE id_talla = ?';
        $params = array($this->id);
        return Database::executeRow($sql, $params);
    }

    public function productosTalla()
    {
        $sql = 'SELECT id_producto, nombre_producto, precio_unitario, stock_producto, talla
                FROM tb_productos 
                RIGHT JOIN tb_detalle_productos USING(id_producto)
                INNER JOIN tb_tallas USING(id_talla)
                ORDER BY nombre_producto';
        $params = array($this->talla);
        return Database::getRows($sql, $params);
    }
}
