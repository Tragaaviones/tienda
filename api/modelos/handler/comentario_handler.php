<?php
// Se incluye la clase para trabajar con la base de datos.
require_once('../../ayudantes/base_datos.php');
/*
 *  Clase para manejar el comportamiento de los datos de la tabla administrador.
 */
class ComentarioHandler
{
    /*
     *  Declaración de atributos para el manejo de datos.
     */
    protected $id = null;
    protected $comentario = null;
    protected $calificacion_producto = null;
    protected $estado_comentario = null;
    protected $fecha_comentario = null;

    /*
     *  Métodos para realizar las operaciones SCRUD (search, create, read, update, and delete).
     */

    // Función para buscar un comentario. 
    public function searchRows()
    {
        $value = '%' . Validator::getSearchValue() . '%';
        $sql = 'SELECT id_comentario AS ID, comentario AS COMENTARIO, calificacion_producto AS CALIFICACION, fecha_comentario AS FECHA, nombre_cliente AS CLIENTE,  CASE 
        WHEN estado_comentario = 1 THEN "Activo"
        WHEN estado_comentario = 0 THEN "Bloqueado"
        END AS ESTADO FROM tb_comentarios
        INNER JOIN tb_clientes USING(id_cliente)
        WHERE comentario LIKE ?
        ORDER BY COMENTARIO;';
        $params = array($value);
        return Database::getRows($sql, $params);
    }
    // Función para leer todos los comentarios.
    public function readAll()
    {
        $sql = 'SELECT id_comentario AS ID, comentario AS COMENTARIO, calificacion_producto AS CALIFICACION, fecha_comentario AS FECHA, nombre_cliente AS CLIENTE,  CASE 
        WHEN estado_comentario = 1 THEN "Activo"
        WHEN estado_comentario = 0 THEN "Bloqueado"
        END AS ESTADO
        FROM tb_comentarios
        INNER JOIN tb_clientes USING(id_cliente)
        ORDER BY COMENTARIO;';
        return Database::getRows($sql);
    }

    //Función para cambiar el estado de un comentarios.
    public function changeState()
    {
        $sql = 'CALL cambiar_estado_comentario(?);';
        $params = array($this->id);
        return Database::executeRow($sql, $params);
    }

    // Función para crear un comentarios.
    public function createRow()
    {
        $sql = 'INSERT INTO tb_comentarios (comentario, calificacion_producto, estado_comentario, id_cliente)
        VALUES(?, ?, 1, ?)';
        $params = array($this->comentario, $this->calificacion_producto, $_SESSION['idCliente']);
        return Database::executeRow($sql, $params);
    }

        // Función para leer todos los comentarios en el sitio publico
        public function readAllPublic()
        {
            $sql = 'SELECT comentario, calificacion_producto, fecha_comentario, nombre_cliente 
            FROM tb_comentarios 
            INNER JOIN tb_clientes USING(id_cliente)
            WHERE estado_comentario = 1;';
            return Database::getRows($sql);
        }
}
