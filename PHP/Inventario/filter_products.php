<?php
require '../Conexión/conexion.php';

$query = isset($_GET['query']) ? $_GET['query'] : '';

$sqlListProducts = "SELECT 
    p.id AS producto_id,
    p.Nombre AS nombre,
    p.Descripcion AS descripcion,
    COALESCE((SELECT SUM(e.cantidad) FROM Entradas e WHERE e.Producto_id = p.id), 0) AS entradas,
    COALESCE((SELECT SUM(s.cantidad) FROM Salidas s WHERE s.Producto_id = p.id), 0) AS salidas,
    COALESCE((SELECT SUM(t.cantidad) FROM Solicitud t WHERE t.Producto_id = p.id AND t.Tipo='Devolución'), 0) AS devolucion,
    p.Stock AS stock,
    p.Proveedor_id,
    prov.Nombre
FROM 
    Producto p
JOIN 
    Proveedor prov ON p.Proveedor_id = prov.id
WHERE 
    p.Nombre LIKE '%$query%'
    AND p.Stock >= 5
ORDER BY 
    p.id";

$resultListProducts = mysqli_query($conn, $sqlListProducts);
$vecListProducts = array();

while ($array = mysqli_fetch_array($resultListProducts)) {
    $vecListProducts[] = $array;
}



// Generar filas de la tabla
echo '<thead>
        <tr>
            <th scope="col">Código</th>
            <th scope="col">Producto</th>
            <th scope="col">Descripción</th>
            <th scope="col">Entradas</th>
            <th scope="col">Salidas</th>
            <th scope="col">Devoluciones</th>
            <th scope="col">Total</th>
        </tr>
        </thead>
        <tbody>';

foreach ($vecListProducts as $key => $value) {                                                                                                                                        
    echo '<tr class="selectable-row" onclick="fillForm(\'' . sprintf('PR%05d', $value[0]) . '\', \'' . $value[1] . '\', \'' . $value[2] . '\', \'' . $value[3] . '\', \'' . $value[4] . '\' , \'' . sprintf('PV%05d', $value[7]) . ' - ' . $value[8] . '\' )">
            <td>' . sprintf("PR%05d", $value[0]) . '</td>
            <td>' . $value[1] . '</td>
            <td>' . $value[2] . '</td>
            <td class="text-center">' . $value[3] . '</td>
            <td class="text-center">' . $value[4] . '</td>
            <td class="text-center">' . $value[5] . '</td>
            <td class="text-center">' . $value[6] . '</td>
          </tr>';
}
echo '</tbody>';
?>
