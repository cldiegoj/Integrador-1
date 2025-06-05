<?php
require '../Conexión/conexion.php';

$query = isset($_GET['query']) ? $_GET['query'] : '';

$sqlListUrs = "SELECT id,nombre_completo,dni,contrasenia,telefono FROM Usuario WHERE id>1 AND estado='1' AND  nombre_completo LIKE '%$query%'";

$resultListUrs = mysqli_query($conn, $sqlListUrs);
$vecListUrs = array();

while ($array = mysqli_fetch_array($resultListUrs)) {
    $vecListUrs[] = $array;
}



// Generar filas de la tabla
echo '<thead>
        <tr>
            <th scope="col">ID</th>
            <th scope="col">Nombre</th>
            <th scope="col">DNI</th>
            <th class="text-center" scope="col">Contraseña</th>
            <th class="text-center" scope="col">Telefono</th>
        </tr>
        </thead>
        <tbody>';

foreach ($vecListUrs as $key => $value) {                                                                                                                                        
    echo '<tr class="selectable-row" onclick="fillForm(\'' . sprintf('UR%05d', $value[0]) . '\', \'' . $value[1] . '\', \'' . $value[2] . '\', \'' . $value[3] . '\', \'' . $value[4] . '\'  )">
            <td>' . sprintf("UR%05d", $value[0]) . '</td>
            <td>' . $value[1] . '</td>
            <td>' . $value[2] . '</td>
            <td class="text-center">********</td>
            <td class="text-center">' . $value[4] . '</td>
          </tr>';
}
echo '</tbody>';
?>
