<?php
// Se incluye la clase para validar los datos de entrada.
require_once('../../ayudantes/validar.php');
// Se incluye la clase padre.
require_once('../../modelos/handler/cliente_handler.php');
/*
 *  Clase para manejar el encapsulamiento de los datos de la tabla clientes.
 */
class ClientesData extends ClientesHandler
{
    // Atributo genérico para manejo de errores.
    private $data_error = null;
    // Atributo para almacenar el nombre del archivo de imagen.
    private $filename = null;
     /*
     *  Métodos para validar y asignar valores de los atributos.
     */
    // Validación y asignación del ID del cliente.
    public function setId($value)
    {
        if (Validator::validateNaturalNumber($value)) {
            $this->id = $value;
            return true;
        } else {
            $this->data_error = 'El identificador del cliente es incorrecto';
            return false;
        }
    }

    

    // Método para obtener el error de los datos.
    public function getDataError()
    {
        return $this->data_error;
    }

    // Método para obtener el nombre del archivo de imagen.
    public function getFilename()
    {
        return $this->filename;
    }
    
}