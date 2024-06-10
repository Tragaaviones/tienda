<?php
// Se incluye la clase para trabajar con la base de datos.
require_once ('../../ayudantes/base_datos.php');
/*
 *  Clase para manejar el comportamiento de los datos de la tabla administrador.
 */
class PedidoHandler
{
    /*
     *  Declaración de atributos para el manejo de datos.
     */
    protected $id_pedido = null;
    protected $id_detalle = null;
    protected $cliente = null;
    protected $producto = null;
    protected $cantidad = null;
    protected $estado = null;


    /*
     *  Métodos para realizar las operaciones SCRUD (search, create, read, update, and delete).
     */

    // Función para buscar un pedido 
    public function searchRows()
    {
        $value = '%' . Validator::getSearchValue() . '%';
        $sql = 'SELECT id_pedido AS ID, fecha_venta AS FECHA, direccion_pedido AS DIRECCION, CASE 
        WHEN estado_pedido = 1 THEN "Entregado"
        WHEN estado_pedido = 0 THEN "Cancelado"
        END AS ESTADO FROM tb_pedidos
                WHERE fecha_venta LIKE ?
                ORDER BY FECHA;';
        $params = array($value);
        return Database::getRows($sql, $params);
    }
    // Función para leer todos los pedido
    public function readAll()
    {
        $sql = 'SELECT id_pedido AS ID, fecha_venta AS FECHA, direccion_pedido AS DIRECCION, estado_pedido AS ESTADO FROM tb_pedidos
        ORDER BY FECHA;';
        return Database::getRows($sql);
    }

    //Funcion de buscador
    public function readAllPublic()
    {
        $sql = 'SELECT id_pedido AS ID, fecha_venta AS FECHA, direccion_pedido AS DIRECCION, CASE 
        WHEN estado_pedido = 1 THEN "Entregado"
        WHEN estado_pedido = 0 THEN "Cancelado"
        END AS ESTADO FROM tb_pedidos
        ORDER BY FECHA;';
        return Database::getRows($sql);
    }

    //Función para cambiar el estado de un pedido.
    public function changeState()
    {
        $sql = 'CALL actualizar_estado_pedido(?,?);';
        $params = array($this->id, $this->estado);
        return Database::executeRow($sql, $params);
    }

    //Metodos para la publica

    // Método en procedimiento, para manipular el detalle de pedido y simplificar el paso a paso
    //insertar o actualizar un producto al carrito
    public function manipulateDetail()
    {
        // Se realiza una subconsulta para obtener el precio del producto.
        $sql = 'CALL insertar_orden_validado(?, ?, ?)';
        $params = array($_SESSION['idCliente'], $this->cantidad, $this->producto);
        return Database::executeRow($sql, $params);
    }

    // Método para obtener los productos que se encuentran en el carrito de compras.
    //Leer el carrito de compras
    public function readDetail()
    {
        $sql = 'SELECT dp.id_detalle_pedido AS ID,
       p.nombre_producto AS NOMBRE,
       dp.cantidad_producto AS CANTIDAD,
       dp.precio_producto AS PRECIO,
       ROUND(dp.precio_producto * dp.cantidad_producto, 2) AS TOTAL
        FROM tb_detalle_pedidos dp
        JOIN tb_detalle_productos dpd ON dp.id_detalle_producto = dpd.id_detalle_producto
        JOIN tb_productos p ON dpd.id_producto = p.id_producto
        WHERE dp.id_pedido = (SELECT id_pedido FROM tb_pedidos WHERE id_cliente = ? AND estado_pedido = "Pendiente" LIMIT 1);SELECT dp.id_detalle_pedido AS ID,
        p.nombre_producto AS NOMBRE,
        dp.cantidad_producto AS CANTIDAD, dp.precio_producto AS PRECIO,
        ROUND(dp.precio_producto * dp.cantidad_producto, 2) AS TOTAL FROM  tb_detalle_pedidos dp JOIN  tb_productos p ON dp.id_producto = p.id_producto
        WHERE dp.id_pedido = (SELECT id_pedido FROM tb_pedidos WHERE id_cliente = ? AND estado_pedido = "Pendiente" LIMIT 1);';
        $params = array($_SESSION['idCliente'], $_SESSION['idCliente']);
        return Database::getRows($sql, $params);
    }

    // Método para finalizar un pedido por parte del cliente.
    //Finalizar el carrito
    public function finishOrder()
    {
        $this->estado = 'En camino';
        $sql = 'UPDATE tb_pedidos
                SET estado_pedido = ?
                WHERE id_pedido = (SELECT id_pedido FROM tb_pedidos WHERE id_cliente = ? AND estado_pedido = "Pendiente" LIMIT 1);';
        $params = array($this->estado, $_SESSION['idCliente']);
        return Database::executeRow($sql, $params);
    }

    // Método para actualizar la cantidad de un producto agregado al carrito de compras.
    //actualizar un producto de carrito
    public function updateDetail()
    {
        $sql = 'CALL actualizar_orden_validado(?,?,?);';
        $params = array($this->cantidad, $this->id_detalle, $_SESSION['idCliente']);
        return Database::executeRow($sql, $params);
    }

    // Método para eliminar un producto que se encuentra en el carrito de compras.
    //eliminar un producto de carrito
    public function deleteDetail()
    {
        $sql = 'CALL eliminar_orden_validado(?,?)';
        $params = array($this->id_detalle, $_SESSION['idCliente']);
        return Database::executeRow($sql, $params);
    }

    //Función para leer un pedido del historial de compras.
    //leer el historial de pedidos
    public function readAllHistory()
    {
        $sql = 'SELECT p.id_pedido AS ID, p.estado_pedido AS ESTADO, p.fecha_pedido AS FECHA, 
        p.direccion_pedido AS DIRECCION, CONCAT(c.nombre_cliente, " ", c.apellido_cliente) AS CLIENTE
        FROM tb_pedidos p
        INNER JOIN tb_clientes c ON p.id_cliente = c.id_cliente
        WHERE (estado_pedido = "En camino" OR estado_pedido = "Entregado" OR estado_pedido = "Cancelado") AND c.id_cliente = ?
        ORDER BY CLIENTE;';
        $params = array($_SESSION['idCliente']);
        return Database::getRows($sql, $params);
    }

    //Función para leer un pedido del historial de compras.
    //Leer un elemento del historial
    public function readOneHistory()
    {
        $sql = 'SELECT p.id_pedido AS ID, p.estado_pedido AS ESTADO, p.fecha_venta AS FECHA, 
        p.direccion_pedido AS DIRECCION, CONCAT(c.nombre_cliente, " ", c.apellido_cliente) AS CLIENTE
        FROM tb_pedidos p
        INNER JOIN tb_clientes c ON p.id_cliente = c.id_cliente
        WHERE (estado_pedido = "En camino" OR estado_pedido = "Entregado" OR estado_pedido = "Cancelado") AND c.id_cliente = 1  AND p.id_pedido = 1
        ORDER BY CLIENTE;';
        $params = array($_SESSION['idCliente'], $this->id_pedido);
        return Database::getRow($sql, $params);
    }
}
