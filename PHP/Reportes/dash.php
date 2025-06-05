<?php
require '../Conexión/conexion.php';

$sqlListReport="SELECT 
    'Entrada' AS Tipo,
    CONCAT('EN', RIGHT(CONCAT('00000', e.id), 5)) AS MovimientoID,
    e.Producto_id AS ProductoID,
    CONCAT(p.Nombre,' - ','PR', RIGHT(CONCAT('00000', p.id), 5)) AS Producto,
    e.cantidad AS Cantidad,
    e.fecha AS Fecha
FROM 
    Entradas e
INNER JOIN 
    Producto p ON e.Producto_id = p.id

UNION ALL

SELECT 
    'Salida' AS Tipo,
    CONCAT('SD', RIGHT(CONCAT('00000', s.id), 5)) AS MovimientoID,
    s.Producto_id AS ProductoID,
    CONCAT(p.Nombre,' - ','PR', RIGHT(CONCAT('00000', p.id), 5)) AS Producto,
    s.cantidad AS Cantidad,
    s.fecha AS Fecha
FROM 
    Salidas s
INNER JOIN 
    Producto p ON s.Producto_id = p.id
    
UNION ALL

SELECT 
    'Devolución' AS Tipo,
    CONCAT('DV', RIGHT(CONCAT('00000', s.id), 5)) AS MovimientoID,
    s.Producto_id AS ProductoID,
    CONCAT(p.Nombre,' - ','PR', RIGHT(CONCAT('00000', p.id), 5)) AS Producto,
    s.cantidad AS Cantidad,
    s.fecha AS Fecha
FROM 
    Solicitud s
INNER JOIN 
    Producto p ON s.Producto_id = p.id

WHERE s.Tipo='Devolución'

ORDER BY 
    ProductoID, Fecha, MovimientoID";


$resultListReport = mysqli_query($conn,$sqlListReport);
$vecListReport=array();
while($array=mysqli_fetch_array($resultListReport))  {
    $vecListReport[]=$array;
}


$sqlListProd="SELECT id,CONCAT(Nombre,' - ','PR', RIGHT(CONCAT('00000',id), 5)) AS Producto FROM Producto";

$resultListProd = mysqli_query($conn,$sqlListProd);
$vecListProd=array();
while($array=mysqli_fetch_array($resultListProd))  {
    $vecListProd[]=$array;
}

?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Reportes SnowBox</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-icons/1.10.2/font/bootstrap-icons.min.css" rel="stylesheet">
    <link rel="stylesheet" href="../../CSS/Reportes/reportes.css">
    <link rel="icon" href="../../src/images/logo.ico">

    <link rel="stylesheet" href="https://unpkg.com/simplebar@latest/dist/simplebar.css" />
    <script src="https://unpkg.com/simplebar@latest/dist/simplebar.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <!-- select search -->
    <script src="https://code.jquery.com/jquery-3.7.1.js"></script>
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>

    <!-- fecha -->
    <script type="text/javascript" src="https://cdn.jsdelivr.net/momentjs/latest/moment.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.29.1/locale/es.min.js"></script>
    <script type="text/javascript" src="https://cdn.jsdelivr.net/npm/daterangepicker/daterangepicker.min.js"></script>
    <link rel="stylesheet" type="text/css" href="https://cdn.jsdelivr.net/npm/daterangepicker/daterangepicker.css" />

    <script src="../../JS/Reportes/reportes.js"></script>

</head>
<body>

    <!-- Navbar -->
    <nav class="navbar navbar-expand-lg navbar-light custom-navbar">
        <div class="container-fluid">
            <a class="navbar-brand" href="#">
                <img src="../../src/images/logo.jpg" alt="Logo SnowBox" class="logo-img">
            </a>
            <?php 
                if (isset($_GET['user']) && $_GET['user'] == 1) {
            ?>
            <span class="navbar-text text-white">
                Administrador
            </span>
            <?php 
                } 
            ?>
            <a href="../../index.html" class="text-white"><i class="bi bi-box-arrow-left"></i></a>
        </div>
    </nav>

    <div class="container-fluid">
        <div class="row">
            <!-- Sidebar -->
            <div class="col-2 custom-sidebar">
                <div class="nav flex-column">
                    <?php 
                        if (isset($_GET['user']) && $_GET['user'] == 1) {
                    ?>
                    <a href="../Inventario/dash.php?user=1" class="nav-link"><i class="bi bi-box-seam"></i> Inventario</a>
                    <a href="../Pedidos/dash.php?user=1" class="nav-link"><i class="bi bi-truck"></i> Pedidos y Devoluciones</a>
                    <a href="../Proveedores/dash.php?user=1" class="nav-link"><i class="bi bi-globe"></i> Proveedores</a>
                    <a href="dash.php?user=1" class="nav-link active"><i class="bi bi-clipboard-data"></i> Reportes</a>
                    <a href="../Configuracion/dash.php?user=1" class="nav-link"><i class="bi bi-gear"></i> Configuración</a>
                    <?php 
                        } else {
                    ?>
                        <a href="../Inventario/dash.php?user=<?php echo urlencode($_GET['user']); ?>" class="nav-link"><i class="bi bi-box-seam"></i> Inventario</a>
                        <a href="../Pedidos/dash.php?user=<?php echo urlencode($_GET['user']); ?>" class="nav-link"><i class="bi bi-truck"></i> Pedidos y Devoluciones</a>
                        <a href="../Proveedores/dash.php?user=<?php echo urlencode($_GET['user']); ?>" class="nav-link"><i class="bi bi-globe"></i> Proveedores</a>
                        <a href="dash.php?user=1" class="nav-link active"><i class="bi bi-clipboard-data"></i> Reportes</a>
                    <?php
                        }
                    ?>
                </div>
            </div>

            <!-- Main Content -->
            <div class="col-10 mt-3">
                <!-- Registro Form -->
                <!-- style="background-color: blue;" -->
                <div class="row">
                    <div class="col">
                        <div class="card">
                            <div class="card-body">
                                <input type="hidden" name="user" value="<?= $_GET['user'] ?>">
                                <div class="row mb-2">
                                    <div class="col mt-1">
                                        <select class="form-select" aria-label="Default select example" id="producto" name="producto" >
                                            <option selected disabled>Producto</option>
                                            <option value="0">Todos</option>
                                            <?php
                                                foreach ($vecListProd as $key => $value){
                                            ?>
                                            <option value="<?= htmlspecialchars($value[1]) ?>"><?= $value[1] ?></option>
                                            <?php
                                                }
                                            ?>
                                        </select>
                                    </div>
                                    <div class="col">
                                        <div id="reportrange" style="background: #fff; cursor: pointer; padding: 5px 10px; border: 1px solid #ccc; width: 100%">
                                            <i class="bi bi-calendar-event"></i>&nbsp;
                                            <span></span> <i class="bi bi-caret-down"></i>
                                        </div>
                                    </div>
                                    <div class="col-2">
                                        <button id="savePdf" class="btn btn-danger">Guardar PDF <i class="bi bi-file-earmark-pdf"></i></button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Tabla de Inventario -->
                <div class="row">
                    <div class="col" data-simplebar style="max-height: 420px;">
                        <table class="table" id="registers-table">
                        <thead>
                                <tr>
                                    <th scope="col">ID</th>
                                    <th scope="col">Tipo</th>
                                    <th class="text-center" scope="col">Producto</th>
                                    <th class="text-center" scope="col">Cantidad</th>
                                    <th class="text-center" scope="col">Fecha</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php
                                    foreach ($vecListReport as $key => $value){
                                ?>
                                <tr>
                                    <td><?=$value[1] ?></td>
                                    <td><?=$value[0]?></td>
                                    <td class="text-center"><?=$value[3]?></td>
                                    <td class="text-center"><?=$value[4]?></td>
                                    <td class="text-center"><?=date('d-m-Y', strtotime($value[5]))?></td>
                                </tr>
                                <?php
                                    }
                                ?>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
