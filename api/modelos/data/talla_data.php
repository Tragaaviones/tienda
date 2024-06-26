<?php
// Se incluye la clase para validar los datos de entrada.
require_once('../../ayudantes/validar.php');
// Se incluye la clase padre.
require_once('../../modelos/handler/talla_handler.php');
/*
 *  Clase para manejar el encapsulamiento de los datos de la tabla CATEGORIA.
 */
class Talla_Data extends Talla_handler
{
    /*
     *  Atributos adicionales.
     */
    private $data_error = null;
    private $filename = null;

    /*
     *  Métodos para validar y establecer los datos.
     */
    public function setId($value)
    {
        if (Validator::validateNaturalNumber($value)) {
            $this->id = $value;
            return true;
        } else {
            $this->data_error = 'El identificador del tipo usuario es incorrecto';
            return false;
        }
    }

    public function setNombre($value, $min = 1, $max = 50)
    {
        if (!Validator::validateAlphanumeric($value)) {
            $this->data_error = 'El nombre debe ser un valor alfanumérico';
            return false;
        } elseif (Validator::validateLength($value, $min, $max)) {
            $this->talla = $value;
            return true;
        } else {
            $this->data_error = 'El nombre debe tener una longitud entre ' . $min . ' y ' . $max;
            return false;
        }
    }

    // public function setImagen($file, $filename = null)
    // {
    //     if (Validator::validateImageFile($file, 1000)) {
    //         $this->imagen = Validator::getFilename();
    //         return true;
    //     } elseif (Validator::getFileError()) {
    //         $this->data_error = Validator::getFileError();
    //         return false;
    //     } elseif ($filename) {
    //         $this->imagen = $filename;
    //         return true;
    //     } else {
    //         $this->imagen = 'default.png';
    //         return true;
    //     }
    // }

    

    // public function setFilename()
    // {
    //     if ($data = $this->readFilename()) {
    //         $this->filename = $data['imagen'];
    //         return true;
    //     } else {
    //         $this->data_error = 'Categoría inexistente';
    //         return false;
    //     }
    // }

    /*
     *  Métodos para obtener el valor de los atributos adicionales.
     */
    public function getDataError()
    {
        return $this->data_error;
    }

    public function getFilename()
    {
        return $this->filename;
    }
}
