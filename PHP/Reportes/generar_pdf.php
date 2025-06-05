<?php
require '../Conexión/conexion.php';
require 'library/dompdf/autoload.inc.php';  // Ajusta la ruta de DOMPDF

use Dompdf\Dompdf;

$producto = isset($_GET['producto']) ? $_GET['producto'] : 'Todos';
$prodOriginal = '';
if($producto=='Producto' || $producto==0){
    $producto='Todos';
    $prodOriginal='Todos';
}else{
    $prodOriginal=$producto;
    // Extrae solo el número después de "PR"
    preg_match('/PR0*(\d+)$/', $producto, $matches);
    $producto = $matches[1];
}

$dateRange = isset($_GET['dateRange']) ? $_GET['dateRange'] : 'Sin filtro';

// Inicializamos las fechas de filtro
$startDate = '';
$endDate = '';

// Si existe un rango de fechas, lo descomponemos
if ($dateRange !== 'Sin filtro') {
    list($startDate, $endDate) = explode(' - ', $dateRange);
}

// Genera la consulta de acuerdo a los filtros
$sql = "SELECT 'Entrada' AS Tipo, CONCAT('EN', RIGHT(CONCAT('00000', e.id), 5)) AS MovimientoID, e.Producto_id AS ProductoID, CONCAT(p.Nombre,' - ','PR', RIGHT(CONCAT('00000', p.id), 5)) AS Producto, e.cantidad AS Cantidad, e.fecha AS Fecha FROM Entradas e INNER JOIN Producto p ON e.Producto_id = p.id";

if ($producto !== 'Todos') {
    $sql .= "  AND e.Producto_id = '".mysqli_real_escape_string($conn, $producto)."'";
}


// Si hay un rango de fechas, agregamos el filtro en la consulta
if ($startDate && $endDate) {
    $sql .= " AND e.fecha BETWEEN '".mysqli_real_escape_string($conn, $startDate)."' AND '".mysqli_real_escape_string($conn, $endDate)."'";
}

// Agregar UNION para salidas, usando el mismo filtro de producto si existe
$sql .= " UNION ALL SELECT 'Salida' AS Tipo, CONCAT('SD', RIGHT(CONCAT('00000', s.id), 5)) AS MovimientoID, s.Producto_id AS ProductoID, CONCAT(p.Nombre,' - ','PR', RIGHT(CONCAT('00000', p.id), 5)) AS Producto, s.cantidad AS Cantidad, s.fecha AS Fecha FROM Salidas s INNER JOIN Producto p ON s.Producto_id = p.id";

if ($producto !== 'Todos') {
    // Extrae solo el número después de "PR"
    $sql .= " AND s.Producto_id = '".mysqli_real_escape_string($conn, $producto)."'";
}

// Si hay un rango de fechas, agregamos el filtro en la consulta para las salidas también
if ($startDate && $endDate) {
    $sql .= " AND s.fecha BETWEEN '".mysqli_real_escape_string($conn, $startDate)."' AND '".mysqli_real_escape_string($conn, $endDate)."'";
}

// Agregar UNION para salidas, usando el mismo filtro de producto si existe
$sql .= " UNION ALL SELECT 'Devolución' AS Tipo, CONCAT('DV', RIGHT(CONCAT('00000', s.id), 5)) AS MovimientoID, s.Producto_id AS ProductoID, CONCAT(p.Nombre,' - ','PR', RIGHT(CONCAT('00000', p.id), 5)) AS Producto, s.cantidad AS Cantidad, s.fecha AS Fecha FROM Solicitud s INNER JOIN Producto p ON s.Producto_id = p.id WHERE s.Tipo='Devolución'";

$sql .= " ORDER BY ProductoID, Fecha, MovimientoID";

$result = mysqli_query($conn, $sql);
$tableRows = '';

while ($row = mysqli_fetch_array($result)) {
    $tableRows .= '<tr>';
    $tableRows .= '<td>' . $row['MovimientoID'] . '</td>';
    $tableRows .= '<td>' . $row['Tipo'] . '</td>';
    $tableRows .= '<td>' . $row['Producto'] . '</td>';
    $tableRows .= '<td>' . $row['Cantidad'] . '</td>';
    $tableRows .= '<td>' . date('d-m-Y', strtotime($row['Fecha'])) . '</td>';
    $tableRows .= '</tr>';
}

$dateRange = date('d-m-Y', strtotime($startDate)).' - '. date('d-m-Y', strtotime($endDate));

// Contenido del PDF
$html = "
<h2>Reporte de Movimientos - SnowBox</h2>
<p><strong>Producto:</strong> $prodOriginal</p>
<p><strong>Rango de Fecha:</strong> $dateRange</p>
<table border='1' width='100%' style='border-collapse: collapse;'>
    <thead>
        <tr>
            <th>ID</th>
            <th>Tipo</th>
            <th>Producto</th>
            <th>Cantidad</th>
            <th>Fecha</th>
        </tr>
    </thead>
    <tbody>
        $tableRows
    </tbody>
</table>
";

// Inicializar y generar PDF
$dompdf = new Dompdf();
$dompdf->loadHtml($html);
$dompdf->setPaper('A4', 'portrait');
$dompdf->render();

// Descargar PDF
$dompdf->stream("Reporte_SnowBox.pdf", array("Attachment" => true));
?>
