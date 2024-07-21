<?php
// Se incluye la clase con las plantillas para generar reportes.
require_once('../../ayudantes/reportes.php');

// Se instancia la clase para crear el reporte.
$pdf = new Report;

// Se verifica si existe un valor para la categoría, de lo contrario se muestra un mensaje.
if (isset($_GET['id_marca'])) {
    // Se incluyen las clases para la transferencia y acceso a datos.
    require_once('../../modelos/data/marca_data.php');
    require_once('../../modelos/data/producto_data.php');
    
    // Se instancian las entidades correspondientes.
    $categoria = new MarcaData;
    $producto = new ProductoData;
    
    // Se establece el valor de la categoría, de lo contrario se muestra un mensaje.
    if ($categoria->setId($_GET['id_marca']) && $producto->setCategoria($_GET['id_marca'])) {
        // Se verifica si la categoría existe, de lo contrario se muestra un mensaje.
        if ($rowCategoria = $categoria->readOne()) {
            // Se inicia el reporte con el encabezado del documento.
            $pdf->startReport('Productos de la marca: ' . $rowCategoria['nombre_marca']);
            
            // Se verifica si existen registros para mostrar, de lo contrario se imprime un mensaje.
            if ($dataProductos = $producto->productosMarca()) {
                // Colores para el encabezado
                $pdf->setFillColor(0, 128, 0); // Verde oscuro
                $pdf->setTextColor(255, 255, 255); // Blanco
                $pdf->setDrawColor(0, 0, 0); // Negro
                
                // Se establece la fuente para los encabezados.
                $pdf->setFont('Arial', 'B', 12);
                
                // Se imprimen las celdas con los encabezados.
                $pdf->cell(15, 10, 'ID', 1, 0, 'C', 1);
                $pdf->cell(171, 10, 'Producto', 1, 1, 'C', 1);
                
                // Se establece la fuente para los datos de los productos.
                $pdf->setFont('Arial', '', 11);
                $pdf->setTextColor(0, 0, 0); // Negro
                
                // Alternar colores para filas
                $fill = false;
                foreach ($dataProductos as $rowProducto) {
                    // Alternar color de fondo
                    $pdf->setFillColor($fill ? 230 : 255); // Gris claro y blanco
                    $pdf->cell(15, 10, $rowProducto['id_producto'], 1, 0, 'C', 1);
                    $pdf->cell(171, 10, $pdf->encodeString($rowProducto['nombre_producto']), 1, 1, 'L', 1);
                    $fill = !$fill;
                }
            } else {
                $pdf->cell(0, 10, $pdf->encodeString('No hay productos registrados con esta marca'), 1, 1);
            }
            
            // Se llama implícitamente al método footer() y se envía el documento al navegador web.
            $pdf->output('I', 'marcas.pdf');
        } else {
            print('Marca inexistente');
        }
    } else {
        print('Marca incorrecta');
    }
} else {
    print('Debe seleccionar una marca');
}
