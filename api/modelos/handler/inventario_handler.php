<?php
// Se incluye la clase para trabajar con la base de datos.
require_once ('../../ayudantes/base_datos.php');
/*
 *	Clase para manejar el comportamiento de los datos de la tabla PRODUCTO.
 */
class InventarioHandler
{
    /*
     *   Declaración de atributos para el manejo de datos.
     */
    protected $id = null;
    protected $producto = null;
    protected $talla = null;
    protected $stock_producto = null;
    // protected $descripcion = null;
    // protected $marca = null;
    // protected $imagen_producto = null;

    // Constante para establecer la ruta de las imágenes.
    const RUTA_IMAGEN = '../../imagenes/productos/';

    /*
     *   Métodos para realizar las operaciones SCRUD (search, create, read, update, and delete).
     */

    // Función para buscar inventario.
    public function searchRows()
    {
        $value = '%' . Validator::getSearchValue() . '%';
        $sql = 'SELECT nombre_producto, talla, precio_unitario, stock_producto, id_detalle_producto
                FROM tb_detalle_productos
                INNER JOIN tb_tallas USING(id_talla)
                INNER JOIN tb_productos USING(id_producto)
                WHERE nombre_producto LIKE ? OR talla LIKE ?
                ORDER BY id_producto';
        $params = array($value, $value);
        return Database::getRows($sql, $params);
    }

    // Función para crear un inventario.
    public function createRow()
    {
        $sql = 'INSERT INTO tb_detalle_productos(id_producto, id_talla, stock_producto)
                VALUES(?, ?, ?)';
        $params = array($this->producto, $this->talla, $this->stock_producto);
        return Database::executeRow($sql, $params);
    }

    // Función para leer todos los inventarios.
    public function readAll()
    {
        $sql = 'SELECT nombre_producto, talla, precio_unitario, stock_producto, id_detalle_producto
                FROM tb_detalle_productos
                INNER JOIN tb_tallas USING(id_talla)
                INNER JOIN tb_productos USING(id_producto)
                ORDER BY id_producto';
        return Database::getRows($sql);
    }

    // Función para leer un solo inventario.
    public function readOne()
    {
        $sql = 'SELECT nombre_producto, talla, precio_unitario, stock_producto, id_detalle_producto
                FROM tb_detalle_productos
                INNER JOIN tb_tallas USING(id_talla)
                INNER JOIN tb_productos USING(id_producto)
                WHERE id_detalle_producto = ?';
        $params = array($this->id);
        return Database::getRow($sql, $params);
    }

    // Función para actualizar inventario.
    public function updateRow()
    {
        $sql = 'UPDATE tb_detalle_productos
                SET id_producto = ?, id_talla = ?, stock_producto = ?
                WHERE id_detalle_producto = ?';
        $params = array($this->producto, $this->talla, $this->stock_producto, $this->id);
        return Database::executeRow($sql, $params);
    }

    // Función para eliminar inventario.
    public function deleteRow()
    {
        $sql = 'DELETE FROM tb_detalle_productos
                WHERE id_detalle_producto = ?';
        $params = array($this->id);
        return Database::executeRow($sql, $params);
    }

        public function productosTalla()
    {
        $sql = 'SELECT id_producto, nombre_producto, precio_unitario, stock_producto, talla
                FROM tb_productos 
                RIGHT JOIN tb_detalle_productos USING(id_producto)
                INNER JOIN tb_tallas USING(id_talla)
                WHERE id_talla = ?
                ORDER BY nombre_producto';
        $params = array($this->talla);
        return Database::getRows($sql, $params);
    }
}
