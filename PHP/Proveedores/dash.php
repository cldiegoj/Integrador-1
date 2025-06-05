<?php
require '../Conexión/conexion.php';

$sqlListProv="SELECT id,Nombre,RUC,telefono,correo FROM Proveedor";


$resultListProv = mysqli_query($conn,$sqlListProv);
$vecListProv=array();
while($array=mysqli_fetch_array($resultListProv))  {
    $vecListProv[]=$array;
}

?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Proveedores SnowBox</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-icons/1.10.2/font/bootstrap-icons.min.css" rel="stylesheet">
    <link rel="stylesheet" href="../../CSS/Proveedores/proveedores.css">
    <link rel="icon" href="../../src/images/logo.ico">

    <link rel="stylesheet" href="https://unpkg.com/simplebar@latest/dist/simplebar.css" />
    <script src="https://unpkg.com/simplebar@latest/dist/simplebar.min.js"></script>
    <script src="../../JS/Proveedores/proveedores.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
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
                        <a href="dash.php?user=1" class="nav-link active"><i class="bi bi-globe"></i> Proveedores</a>
                        <a href="../Reportes/dash.php?user=1" class="nav-link"><i class="bi bi-clipboard-data"></i> Reportes</a>
                        <a href="../Configuracion/dash.php?user=1" class="nav-link"><i class="bi bi-gear"></i> Configuración</a>
                    <?php 
                        } else {
                    ?>
                        <a href="../Inventario/dash.php?user=<?php echo urlencode($_GET['user']); ?>" class="nav-link"><i class="bi bi-box-seam"></i> Inventario</a>
                        <a href="../Pedidos/dash.php?user=<?php echo urlencode($_GET['user']); ?>" class="nav-link"><i class="bi bi-truck"></i> Pedidos y Devoluciones</a>
                        <a href="dash.php?user=<?php echo urlencode($_GET['user']); ?>" class="nav-link active"><i class="bi bi-globe"></i> Proveedores</a>
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
                                    <div class="col-7">
                                        <div class="row mb-2">
                                            <div class="col">
                                                <div class="input-group">
                                                    <span class="input-group-text"><i class="bi bi-search"></i></span>
                                                    <input type="text" class="form-control" placeholder="Proveedor" onkeyup="filterProveedores(this.value)">
                                                </div>
                                            </div>
                                        </div>
                                        <div class="row">
                                            <h5 class="card-title">Registro:</h5>
                                        </div>
                                        <form id="productForm" action="controller.php" method="post">
										<input type="hidden" name="user" value="<?= $_GET['user'] ?>">
                                            <div class="row">
                                                <div class="col">
                                                    <div class="row mb-2">
                                                        <div class="col">
                                                            <input type="text" class="form-control" id="codigo-input" name="codigo" placeholder="Código">
                                                        </div>
                                                        <div class="col">
                                                            <input type="text" class="form-control" id="nombre-input" name="nombre" placeholder="Nombre">
                                                        </div>
                                                        <div class="col">
                                                            <button type="submit" class="btn btn-success col" onclick="setAction('register')">Registrar</button>
                                                        </div>
                                                    </div>
                                                    <div class="row mb-2">
                                                        <div class="col">
                                                            <input type="text" class="form-control" id="ruc-input" name="ruc" placeholder="RUC" maxlength="11">
                                                        </div>
                                                        <div class="col">
                                                            <input type="text" class="form-control" id="telefono-input" name="telefono" placeholder="Número de Contacto" maxlength="9">
                                                        </div>
                                                        <div class="col">
                                                            <button type="submit" class="btn btn-warning" onclick="setAction('modify')">Modificar</button>
                                                        </div>
                                                    </div>
                                                    <div class="row">
                                                        <div class="col">
                                                            <input type="text" class="form-control" id="correo_proveedor" name="correo" placeholder="Correo">
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </form>
                                    </div>
                                    <div class="col-5" data-simplebar style="max-height: 200px;">
                                        <table class="table" id="productos-table">
                                        </table>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Tabla de Inventario -->
                <div class="row">
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
                                <?php
                                    foreach ($vecListProv as $key => $value){
                                ?>
                                <tr class="selectable-row" onclick="fillForm('<?= sprintf('PV%05d', $value[0]) ?>', '<?= $value[1] ?>', '<?= $value[2] ?>', '<?= $value[3] ?>', '<?= $value[4] ?>')">
                                    <td><?= sprintf("PV%05d", $value[0]) ?></td>
                                    <td><?=$value[1]?></td>
                                    <td><?=$value[2]?></td>
                                    <td class="text-center"><?=$value[3]?></td>
                                    <td class="text-center"><?=$value[4]?></td>
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
