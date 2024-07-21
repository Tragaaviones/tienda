<?php
// Se incluye la clase con las plantillas para generar reportes.
require_once('../../ayudantes/reportes.php');
// Se incluyen las clases para la transferencia y acceso a datos.
require_once('../../modelos/data/producto_data.php');
require_once('../../modelos/data/talla_data.php');
require_once('../../modelos/data/inventario_data.php');

// Se instancia la clase para crear el reporte.
$pdf = new Report;
// Se inicia el reporte con el encabezado del documento.
$pdf->startReport('Inventario de productos por tallas');
// Se instancia el módelo Categoría para obtener los datos.
$talla = new Talla_Data;
// Se verifica si existen registros para mostrar, de lo contrario se imprime un mensaje.
if ($datatallas = $talla->readAll()) {                
    // Colores para el encabezado
    $pdf->setFillColor(0, 128, 0); // Verde oscuro
    $pdf->setTextColor(255, 255, 255); // Blanco
    $pdf->setDrawColor(0, 0, 0); // Negro

    // Se establece la fuente para los encabezados.
    $pdf->setFont('Arial', 'B', 12);

    // Se imprimen las celdas con los encabezados.
    $pdf->cell(15, 10, 'ID', 1, 0, 'C', 1);
    $pdf->cell(120, 10, 'Producto', 1, 0, 'C', 1);
    $pdf->cell(30, 10, 'Precio (US$)', 1, 0, 'C', 1);
    $pdf->cell(25, 10, 'Existencias', 1, 1, 'C', 1);

    // Se establece la fuente para los datos de los productos.
    $pdf->setFillColor(255, 255, 255);
    $pdf->setFont('Arial', '', 11);
    $pdf->setTextColor(0, 0, 0); // Negro

    // Se recorren los registros fila por fila.
    foreach ($datatallas as $rowtalla) {
        // Se imprime una celda con el nombre de la categoría.
        $pdf->cell(190, 10, $pdf->encodeString('Talla: ' . $rowtalla['talla']), 1, 1, 'C', 1);
        // Se instancia el módelo Producto para procesar los datos.
        $producto = new InventarioData;
        // Se establece la categoría para obtener sus productos, de lo contrario se imprime un mensaje de error.
        if ($producto->setTalla($rowtalla['id_talla'])) {
            // Se verifica si existen registros para mostrar, de lo contrario se imprime un mensaje.
            if ($dataProductos = $producto->productosTalla()) {
                // Se recorren los registros fila por fila.
                foreach ($dataProductos as $rowProducto) {
                    // Se imprimen las celdas con los datos de los productos.
                    $pdf->cell(15, 10, $rowProducto['id_producto'], 1, 0, 'C', 1);
                    $pdf->cell(120, 10, $pdf->encodeString($rowProducto['nombre_producto']), 1, 0, 'L', 1);
                    $pdf->cell(30, 10, $rowProducto['precio_unitario'], 1, 0, 'C', 1);
                    $pdf->cell(25, 10, $rowProducto['stock_producto'], 1, 1, 'C', 1);
                }
            } else {
                $pdf->cell(190, 10, $pdf->encodeString('No hay productos registrados en esta talla'), 1, 1);
            }
        } else {
            $pdf->cell(190, 10, $pdf->encodeString('Talla incorrecta o inexistente'), 1, 1);
        }
    }
} else {
    $pdf->cell(190, 10, $pdf->encodeString('No hay productos para mostrar'), 1, 1);
}
// Se llama implícitamente al método footer() y se envía el documento al navegador web.
$pdf->output('I', 'Inventario.pdf');
