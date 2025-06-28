<?php
require 'library/dompdf/autoload.inc.php';

use Dompdf\Dompdf;

include '../Conexion/conexion.php';

// Consulta a la base de datos
$obj = new Conexion();
$sql = "select MatriculaID, a.nombres, s.nombre_seccion, Periodo_Inicio, Periodo_Fin, Estado FROM matriculas m JOIN alumnos a ON m.AlumnoID = a.AlumnoID JOIN secciones s ON m.SeccionID = s.SeccionID";
$res = mysqli_query($obj->conecta(), $sql) or die(mysqli_error($obj->conecta()));


// Empieza a armar el HTML
$html = '
    <h2 style="text-align: center;">Reporte de Matrículas</h2>
    <table border="1" cellpadding="5" cellspacing="0" width="100%">
        <thead>
            <tr style="background-color: #eee;">
                <th>ID Matricula</th>
                <th>Alumno</th>
                <th>Sección</th>
                <th>Inicio</th>
                <th>Fin</th>
                <th>Estado</th>
            </tr>
        </thead>
        <tbody>';

while ($fila = mysqli_fetch_array($res)) {
    $html .= '<tr>
                <td>' . $fila[0] . '</td>
                <td>' . $fila[1] . '</td>
                <td>' . $fila[2] . '</td>
                <td>' . $fila[3] . '</td>
                <td>' . $fila[4] . '</td>
                <td>' . $fila[5] . '</td>
            </tr>';
}

$html .= '
        </tbody>
    </table>';

// Crear instancia Dompdf

$dompdf = new Dompdf();
$dompdf->loadHtml($html);
$dompdf->setPaper('A4', 'portrait');
$dompdf->render();
$dompdf->stream("reporte_matriculas.pdf", array("Attachment" => true));
header("location: ../Matricula/registro.php");
exit;
