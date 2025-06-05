<?php
require '../Conexión/conexion.php';

$query = isset($_GET['query']) ? $_GET['query'] : '';

$sqlListProv = "SELECT id,Nombre,RUC,telefono,correo FROM Proveedor WHERE Nombre LIKE '%$query%'";

$resultListProv = mysqli_query($conn, $sqlListProv);
$vecListProv = array();

while ($array = mysqli_fetch_array($resultListProv)) {
    $vecListProv[] = $array;
}



// Generar filas de la tabla
echo '<thead>
        <tr>
            <th scope="col">ID</th>
            <th scope="col">Nombre</th>
            <th scope="col">RUC</th>
            <th class="text-center" scope="col">Contacto</th>
            <th class="text-center" scope="col">Correo</th>
        </tr>
        </thead>
        <tbody>';

foreach ($vecListProv as $key => $value) {                                                                                                                                        
    echo '<tr class="selectable-row" onclick="fillForm(\'' . sprintf('PV%05d', $value[0]) . '\', \'' . $value[1] . '\', \'' . $value[2] . '\', \'' . $value[3] . '\', \'' . $value[4] . '\'  )">
            <td>' . sprintf("PV%05d", $value[0]) . '</td>
            <td>' . $value[1] . '</td>
            <td>' . $value[2] . '</td>
            <td class="text-center">' . $value[3] . '</td>
            <td class="text-center">' . $value[4] . '</td>
          </tr>';
}
echo '</tbody>';
?>
