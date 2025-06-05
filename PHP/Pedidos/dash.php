<?php
require '../Conexión/conexion.php';

$sqlListSold="SELECT 
    s.id,
    s.Usuario_id,
    CONCAT('PR', LPAD(p.id, 5, '0'), ' - ', p.Nombre) AS Producto,
    s.Tipo,
    s.cantidad,
    s.fecha,
    s.estado
FROM 
    Solicitud s
INNER JOIN 
    Producto p ON s.Producto_id = p.id
ORDER BY 
    s.id";

$resultListSold = mysqli_query($conn,$sqlListSold);
$vecListSold=array();
while($array=mysqli_fetch_array($resultListSold))  {
    $vecListSold[]=$array;
}


$sqlListProd="SELECT id,CONCAT(Nombre, ' - ', 'PR', RPAD(id, 5, '0')) AS Producto FROM Producto";

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
    <title>Solicitudes SnowBox</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-icons/1.10.2/font/bootstrap-icons.min.css" rel="stylesheet">

    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/5.3.0/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdn.datatables.net/2.1.8/css/dataTables.bootstrap5.css">
    
    

    <link rel="stylesheet" href="../../CSS/Pedidos/pedidos.css">
    <link rel="icon" href="../../src/images/logo.ico">

    <link rel="stylesheet" href="https://unpkg.com/simplebar@latest/dist/simplebar.css" />
    <script src="https://unpkg.com/simplebar@latest/dist/simplebar.min.js"></script>

    <script src="../../JS/Pedidos/pedidos.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>


    <script src="https://code.jquery.com/jquery-3.7.1.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/5.3.0/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdn.datatables.net/2.1.8/js/dataTables.js"></script>
    <script src="https://cdn.datatables.net/2.1.8/js/dataTables.bootstrap5.js"></script>

    <!-- select search -->
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
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
                        <a href="dash.php?user=1" class="nav-link active"><i class="bi bi-truck"></i> Pedidos y Devoluciones</a>
                        <a href="../Proveedores/dash.php?user=1" class="nav-link"><i class="bi bi-globe"></i> Proveedores</a>
                        <a href="../Reportes/dash.php?user=1" class="nav-link"><i class="bi bi-clipboard-data"></i> Reportes</a>
                        <a href="../Configuracion/dash.php?user=1" class="nav-link"><i class="bi bi-gear"></i> Configuración</a>
                    <?php 
                        } else {
                    ?>
                        <a href="../Inventario/dash.php?user=<?php echo urlencode($_GET['user']); ?>" class="nav-link"><i class="bi bi-box-seam"></i> Inventario</a>
                        <a href="dash.php?user=<?php echo urlencode($_GET['user']); ?>" class="nav-link active"><i class="bi bi-truck"></i> Pedidos y Devoluciones</a>
                        <a href="../Proveedores/dash.php?user=<?php echo urlencode($_GET['user']); ?>" class="nav-link"><i class="bi bi-globe"></i> Proveedores</a>
                        <a href="../Reportes/dash.php?user=<?php echo urlencode($_GET['user']); ?>" class="nav-link"><i class="bi bi-clipboard-data"></i> Reportes</a>
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
                                <div class="row">
                                    <div class="col">
                                        <div class="row">
                                            <h5 class="card-title">Registro:</h5>
                                        </div>
                                        <form id="productForm" action="controller.php" method="post">
                                            <!-- Almacenar id del usuario-->
                                            <input type="hidden" name="user" value="<?= $_GET['user'] ?>">
                                            <div class="row">
                                                <div class="col">
                                                    <div class="row">
                                                        <div class="col mt-1">
                                                            <select class="form-select" aria-label="Default select example" id="producto" name="producto" >
                                                                <option value="0" selected disabled>Producto</option>
                                                                <?php
                                                                    foreach ($vecListProd as $key => $value){
                                                                ?>
                                                                <option value=<?= $value[0] ?>><?= $value[1] ?></option>
                                                                <?php
                                                                    }
                                                                ?>
                                                            </select>
                                                        </div>
                                                        <div class="col-2 mb-2">
                                                            <input type="text" class="form-control" id="cantidad-input" name="cantidad" placeholder="Cantidad">
                                                        </div>
                                                        
                                                        <div class="col-2 mb-2">
                                                            <select class="form-select" aria-label="Default select example" name="tipo" value="0">
                                                                <option value="0" selected disabled>Tipo</option>
                                                                <option value="1">Pedio</option>
                                                                <option value="2">Devolución</option>
                                                            </select>
                                                        </div>
                                                        <div class="col-2">
                                                            <button type="submit" class="btn btn-success col" onclick="setAction('register')">Registrar</button>
                                                        </div>
                                                    </div>
                                                    <div class="row">
                                                        <div class="col">
                                                            <input type="text" class="form-control" id="descripcion_solicitud" name="descripcion" placeholder="Descripción">
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Tabla de Inventario
                <div class="row">
                        <div class="col-4">
                            <div class="row">
                                <div class="col-3">
                                <label for="miCombo">Opciones</label>
                                </div>
                                <div class="col-10">
                                <select class="form-control" id="miCombo" name="opcion">
                                        <option value="opcion1">Opción 1</option>
                                        <option value="opcion2">Opción 2</option>
                                        <option value="opcion3">Opción 3</option>
                                        <option value="opcion4">Opción 4</option>
                                </select>
                                </div>
                            </div>
                        </div>
                        <div class="col-8">
                            <div class="input-group">
                                <span class="input-group-text"><i class="bi bi-search"></i></span>
                                <input type="text" class="form-control" placeholder="Proveedor" onkeyup="filterProveedores(this.value)">
                            </div>
                        </div>
                    </div>
                    <div class="col" data-simplebar style="max-height: 250px;">
                            <table class="table" id="proveedores-table">
                            <thead>
                                    <tr>
                                        <th scope="col">Código</th>
                                        <th scope="col">Nombre</th>
                                        <th scope="col">RUC</th>
                                        <th class="text-center" scope="col">Contacto</th>
                                        <th class="text-center" scope="col">Correo</th>
                                    </tr>
                                </thead>
                                <tbody>
                                
                            </table>
                        </div>
                         -->
                <div class="row">
                    <div class="col">
                    <table id="example" class="table table-striped" style="width:100%">
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>Usuario</th>
                                <th>Producto</th>
                                <th>Fecha</th>
                                <th>Cantidad</th>
                                <th>Tipo</th>
                                <th>Estado</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                                foreach ($vecListSold as $key => $value){
                            ?>
                            <tr>
                                <td><?= sprintf("EV%05d", $value[0]) ?></td>
                                <td><?= sprintf("UR%05d", $value[1]) ?></td>
                                <td><?=$value[2]?></td>
                                <td class="text-center"><?=date('d-m-Y', strtotime($value[5]))?></td>
                                <td class="text-center"><?=$value[4]?></td>
                                <td class="text-center"><?=$value[3]?></td>
                                <td class="text-center"><?=$value[6]?></td>
                            </tr>
                            <?php
                                }
                            ?>
                        </tbody>
                    </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <script>
        $(document).ready(function(){
            $('#example').DataTable({
                "language": {
                    "decimal":        "",
                    "emptyTable":     "No hay datos disponibles en la tabla",
                    "info":           "Mostrando _START_ a _END_ de _TOTAL_ entradas",
                    "infoEmpty":      "Mostrando 0 a 0 de 0 entradas",
                    "infoFiltered":   "(filtrado de _MAX_ entradas en total)",
                    "infoPostFix":    "",
                    "thousands":      ",",
                    "lengthMenu":     "Mostrar _MENU_ entradas",
                    "loadingRecords": "Cargando...",
                    "processing":     "Procesando...",
                    "search":         "Buscar:",
                    "zeroRecords":    "No se encontraron registros coincidentes",
                    "paginate": {
                        "first":      "Primera",
                        "last":       "Última",
                        "next":       "Siguiente",
                        "previous":   "Anterior"
                    },
                }
            });
            $('#producto').select2();
        });
    </script>
    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
