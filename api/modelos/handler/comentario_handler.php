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

    /*
     *  Métodos para realizar las operaciones SCRUD (search, create, read, update, and delete).
     */

    // Función para buscar un comentario. 
    public function searchRows()
    {
        $value = '%' . Validator::getSearchValue() . '%';
        $sql = 'SELECT id_comentario AS ID, comentario AS COMENTARIO, calificacion_producto AS CALIFICACION, fecha_comentario AS FECHA,  CASE 
        WHEN estado_comentario = 1 THEN "Activo"
        WHEN estado_comentario = 0 THEN "Bloqueado"
        END AS ESTADO FROM tb_comentarios
                WHERE comentario LIKE ?
                ORDER BY COMENTARIO;';
        $params = array($value);
        return Database::getRows($sql, $params);
    }
    // Función para leer todos los comentarios.
    public function readAll()
    {
        $sql = 'SELECT id_comentario AS ID, comentario AS COMENTARIO, calificacion_producto AS CALIFICACION, fecha_comentario AS FECHA,  CASE 
        WHEN estado_comentario = 1 THEN "Activo"
        WHEN estado_comentario = 0 THEN "Bloqueado"
        END AS ESTADO FROM tb_comentarios
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
}
