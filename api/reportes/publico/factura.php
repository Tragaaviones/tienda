<?php
// Se incluye la clase con las plantillas para generar reportes.
require_once('../../ayudantes/reportes.php');

// Se instancia la clase para crear el reporte
$pdf = new Report;
$pdf->startReportPublic('Comprobante de compra');

if (isset($_SESSION['idCliente'])) {
    // Se incluyen las clases para la transferencia y acceso a datos.
    require_once('../../modelos/data/inventario_data.php');
    require_once('../../modelos/data/pedido_data.php');
    require_once('../../modelos/data/detalle_pedido_data.php');
    require_once('../../modelos/data/producto_data.php');
    
    // Se instancian las entidades correspondientes.
    $pedido = new PedidoData;
    $dtpedido = new DetallesPedidosData;
            
    // Se verifica si existen registros para mostrar, de lo contrario se imprime un mensaje.
    if ($dataPedido = $pedido->readDetail2()) {
        // Colores para el encabezado
        $pdf->setFillColor(0, 128, 0); // Verde oscuro
        $pdf->setTextColor(255, 255, 255); // Blanco
        $pdf->setDrawColor(0, 0, 0); // Negro
        
        // Se establece la fuente para los encabezados.
        $pdf->setFont('Arial', 'B', 12);
        
        // Se imprimen las celdas con los encabezados.
        $pdf->cell(15, 10, 'ID', 1, 0, 'C', 1);
        $pdf->cell(50, 10, 'NOMBRE', 1, 0, 'C', 1);
        $pdf->cell(25, 10, 'CANTIDAD', 1, 0, 'C', 1);
        $pdf->cell(30, 10, 'PRECIO', 1, 0, 'C', 1);
        $pdf->cell(50, 10, 'SUBTOTAL', 1, 0, 'C', 1);
        $pdf->ln(); // Nueva línea después del encabezado

        // Se establece la fuente para los datos de los productos.
        $pdf->setFont('Arial', '', 11);
        $pdf->setTextColor(0, 0, 0); // Negro
        
        // Inicializar el total general
        $totalGeneral = 0;

        // Alternar colores para filas
        $fill = false;

        // Verificar que $dataPedido sea un array y contenga arrays
        if (is_array($dataPedido)) {
            foreach ($dataPedido as $rowProducto) {
                if (is_array($rowProducto)) {
                    // Alternar color de fondo
                    $pdf->setFillColor($fill ? 230 : 255); // Gris claro y blanco
                    $pdf->cell(15, 10, $rowProducto['ID'], 1, 0, 'C', $fill);
                    $pdf->cell(50, 10, $rowProducto['NOMBRE'], 1, 0, 'C', $fill);
                    $pdf->cell(25, 10, $rowProducto['CANTIDAD'], 1, 0, 'C', $fill);
                    $pdf->cell(30, 10, $rowProducto['PRECIO'], 1, 0, 'C', $fill);
                    $pdf->cell(50, 10, $rowProducto['SUBTOTAL'], 1, 1, 'C', $fill); // Ajuste para nueva línea al final
                    $fill = !$fill;
                    // Sumar el subtotal al total general
                    $totalGeneral += $rowProducto['SUBTOTAL'];
                } else {
                    // Manejo del error: $rowProducto no es un array
                    $pdf->cell(0, 10, 'Error: Fila de producto no válida', 1, 1, 'C');
                }
            }
            // Imprimir el total general
            $pdf->setFont('Arial', 'B', 12);
            $pdf->cell(120, 10, 'Total General', 1, 0, 'C', 1);
            $pdf->cell(50, 10, number_format($totalGeneral, 2), 1, 1, 'C', 1);
        } else {
            // Manejo del error: $dataPedido no es un array
            $pdf->cell(0, 10, 'Error: Datos de pedido no válidos', 1, 1, 'C');
        }
    } else {
        $pdf->cell(0, 10, $pdf->encodeString('No hay productos registrados'), 1, 1);
    }
    
    // Se llama implícitamente al método footer() y se envía el documento al navegador web.
    $pdf->output('I', 'Comprobante de compra.pdf');
} else {
    print('Debe seleccionar un cliente');
}

