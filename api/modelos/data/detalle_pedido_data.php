<?php
// Se incluye la clase para validar los datos de entrada.
require_once ('../../ayudantes/validar.php');
// Se incluye la clase padre.
require_once ('../../modelos/handler/detalle_pedido_handler.php');
/*
 *  Clase para manejar el encapsulamiento de los datos de la tabla detalles_pedidos.
 */
class DetallesPedidosData extends DetallesPedidosHandler
{
    // Atributo genérico para manejo de errores.
    private $data_error = null;
    // Atributo para almacenar el nombre del archivo de imagen.
    private $filename = null;
    /*
     *  Métodos para validar y asignar valores de los atributos.
     */
    // Validación y asignación del ID del detalle de pedido.
    public function setId($value)
    {
        if (Validator::validateNaturalNumber($value)) {
            $this->id = $value;
            return true;
        } else {
            $this->data_error = 'El identificador del detalle de pedido es incorrecto';
            return false;
        }
    }

    // Método para obtener el error de los datos.
    public function getDataError()
    {
        return $this->data_error;
    }

}