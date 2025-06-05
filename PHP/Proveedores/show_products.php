<?php
require '../Conexión/conexion.php';

$query = isset($_GET['query']) ? $_GET['query'] : '';

$numeroCodigo = (int) substr($query, 2);

$sqlListProd = "SELECT p.Nombre FROM Producto p JOIN Proveedor prov ON p.Proveedor_id = prov.id WHERE prov.id='$numeroCodigo'";

$resultListProd = mysqli_query($conn, $sqlListProd);
$vecListProd = array();

while ($array = mysqli_fetch_array($resultListProd)) {
    $vecListProd[] = $array;
}



// Generar filas de la tabla
echo '<thead>
        <tr>
            <th scope="col">Productos</th>
        </tr>
        </thead>
        <tbody>';

foreach ($vecListProd as $key => $value) {                                                                                                                                        
    echo '<tr>
            <td>' . $value[0] . '</td>
          </tr>';
}
echo '</tbody>';
?>
