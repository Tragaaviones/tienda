<?php
// Se incluye la clase para trabajar con la base de datos.
require_once ('../../ayudantes/base_datos.php');
/*
 *	Clase para manejar el comportamiento de los datos de la tabla PRODUCTO.
 */
class ProductoHandler
{
    /*
     *   Declaración de atributos para el manejo de datos.
     */
    protected $id = null;
    protected $nombre = null;
    protected $categoria = null;
    protected $precio = null;
    protected $descripcion = null;
    protected $marca = null;
    protected $imagen_producto = null;

    // Constante para establecer la ruta de las imágenes.
    const RUTA_IMAGEN = '../../imagenes/productos/';

    /*
     *   Métodos para realizar las operaciones SCRUD (search, create, read, update, and delete).
     */

    // Barra de de busqueda, buscar producto.
    public function searchRows()
    {
        $value = '%' . Validator::getSearchValue() . '%';
        $sql = 'SELECT p.id_producto, p.nombre_producto, p.id_categoria, nombre, p.precio_unitario, p.descripcion, p.id_marca, nombre_marca, p.imagen
                FROM tb_productos p
                INNER JOIN tb_categorias USING(id_categoria)
                INNER JOIN tb_marcas USING(id_marca)
                WHERE nombre_producto LIKE ? OR descripcion LIKE ?
                ORDER BY nombre_producto';
        $params = array($value, $value);
        return Database::getRows($sql, $params);
    }

    // Función para crear un producto.
    public function createRow()
    {
        $sql = 'INSERT INTO tb_productos(nombre_producto, id_categoria, precio_unitario, descripcion, id_marca, imagen, id_administrador)
                VALUES(?, ?, ?, ?, ?, ?, ?)';
        $params = array($this->nombre, $this->categoria, $this->precio, $this->descripcion, $this->marca, $this->imagen_producto, $_SESSION['id_administrador']);
        return Database::executeRow($sql, $params);
    }

    // Fucnión para leer todos los productos. 
    public function readAll()
    {
        $sql = 'SELECT p.id_producto, p.nombre_producto, p.id_categoria, nombre, p.precio_unitario, p.descripcion, p.id_marca, nombre_marca, p.imagen
                FROM tb_productos p
                INNER JOIN tb_categorias USING(id_categoria)
                INNER JOIN tb_marcas USING(id_marca)
                ORDER BY id_producto';
        return Database::getRows($sql);
    }

    // Función para leer un producto.
    public function readOne()
    {
        $sql = 'SELECT p.id_producto, p.nombre_producto, p.id_categoria, nombre, p.precio_unitario, p.descripcion, p.id_marca, nombre_marca, p.imagen
                FROM tb_productos p
                INNER JOIN tb_categorias USING(id_categoria)
                INNER JOIN tb_marcas USING(id_marca)
                WHERE id_producto = ?';
        $params = array($this->id);
        return Database::getRow($sql, $params);
    }

    // Función para leer un archivo del producto.
    public function readFilename()
    {
        $sql = 'SELECT imagen
                FROM tb_productos
                WHERE id_producto = ?';
        $params = array($this->id);
        return Database::getRow($sql, $params);
    }


    // Función para actualizar producto.
    public function updateRow()
    {
        $sql = 'UPDATE tb_productos
                SET nombre_producto = ?, id_categoria = ?, precio_unitario = ?, descripcion = ?, id_marca = ?, imagen = ?
                WHERE id_producto = ?';
        $params = array($this->nombre, $this->categoria, $this->precio, $this->descripcion, $this->marca, $this->imagen_producto, $this->id);
        return Database::executeRow($sql, $params);
    }

    // Funcion para eliminar un producto.
    public function deleteRow()
    {
        $sql = 'DELETE FROM tb_productos
                WHERE id_producto = ?';
        $params = array($this->id);
        return Database::executeRow($sql, $params);
    }

    // Función para visualizar gficas.
    public function readProductosCategoria()
    {
        $sql = 'SELECT id_producto, imagen_producto, nombre_producto, descripcion_producto, precio_producto, existencias_producto
                FROM producto
                INNER JOIN categoria USING(id_categoria)
                WHERE id_categoria = ? AND estado_producto = true
                ORDER BY nombre_producto';
        $params = array($this->categoria);
        return Database::getRows($sql, $params);
    }

    /*
     *   Métodos para generar gráficos.
     */
    public function cantidadProductosCategoria()
    {
        $sql = 'SELECT nombre_categoria, COUNT(id_producto) cantidad
                FROM producto
                INNER JOIN categoria USING(id_categoria)
                GROUP BY nombre_categoria ORDER BY cantidad DESC LIMIT 5';
        return Database::getRows($sql);
    }

    public function porcentajeProductosCategoria()
    {
        $sql = 'SELECT nombre_categoria, ROUND((COUNT(id_producto) * 100.0 / (SELECT COUNT(id_producto) FROM producto)), 2) porcentaje
                FROM producto
                INNER JOIN categoria USING(id_categoria)
                GROUP BY nombre_categoria ORDER BY porcentaje DESC';
        return Database::getRows($sql);
    }

    /*
     *   Métodos para generar reportes.
     */
    public function productosCategoria()
    {
        $sql = 'SELECT nombre_producto, precio_producto, estado_producto
                FROM producto
                INNER JOIN categoria USING(id_categoria)
                WHERE id_categoria = ?
                ORDER BY nombre_producto';
        $params = array($this->categoria);
        return Database::getRows($sql, $params);
    }
}