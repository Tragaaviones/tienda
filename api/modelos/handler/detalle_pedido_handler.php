<?php
// Se incluye la clase para trabajar con la base de datos.
require_once('../../ayudantes/base_datos.php');
/*
 *  Clase para manejar el comportamiento de los datos de la tabla administrador.
 */
class DetallesPedidosHandler
{
    /*
     *  Declaración de atributos para el manejo de datos.
     */
    protected $id = null;

    /*
     *  Métodos para realizar las operaciones SCRUD (search, create, read, update, and delete).
     */
    //Leer el detalle de pedido
    public function readOne()
    {
        $sql = 'SELECT p.fecha_venta AS FECHA, dp.precio_producto AS PRECIO, p.direccion_pedido AS DIRECCION, 
        cantidad_producto AS CANTIDAD 
        FROM tb_detalle_pedidos dp 
        INNER JOIN tb_pedidos p USING(id_pedido)
        WHERE id_pedido = ?
        ORDER BY FECHA;';
        $params = array($this->id);
        return Database::getRows($sql, $params);
    }

    // Función para leer todos los pedido
    public function graficosDetalle()
    {
        $sql =
            'SELECT p.nombre_producto AS NOMBRE, SUM(dp.cantidad_producto) AS CANTIDAD 
            FROM tb_detalle_pedidos dp
            JOIN tb_detalle_productos dpd ON dp.id_detalle_producto = dpd.id_detalle_producto 
            JOIN tb_productos p ON dpd.id_producto = p.id_producto 
            GROUP BY p.nombre_producto ORDER BY CANTIDAD DESC 
            LIMIT 10;';
        return Database::getRows($sql);
    }
}
