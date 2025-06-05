<?php
require '../Conexión/conexion.php';

$sqlListProducts="SELECT 
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
    p.Stock >= 5
ORDER BY 
    p.id";


$resultListProducts = mysqli_query($conn,$sqlListProducts);
$vecListProducts=array();
while($array=mysqli_fetch_array($resultListProducts))  {
    $vecListProducts[]=$array;
}

$sqlLowStockProducts="SELECT 
    p.id AS producto_id,
    p.Nombre AS nombre,
    p.Descripcion AS descripcion,
    COALESCE((SELECT SUM(e.cantidad) FROM Entradas e WHERE e.Producto_id = p.id), 0) AS entradas,
    COALESCE((SELECT SUM(s.cantidad) FROM Salidas s WHERE s.Producto_id = p.id), 0) AS salidas,
    p.Stock AS stock,
    p.Proveedor_id,
    prov.Nombre
FROM 
    Producto p
JOIN 
    Proveedor prov ON p.Proveedor_id = prov.id
WHERE 
    p.Stock < 5
ORDER BY 
    p.id";

$resultLowStockProducts = mysqli_query($conn,$sqlLowStockProducts);
$vecLowStockProducts=array();
while($array=mysqli_fetch_array($resultLowStockProducts))  {
    $vecLowStockProducts[]=$array;
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Inventario SnowBox</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-icons/1.10.2/font/bootstrap-icons.min.css" rel="stylesheet">
    <link rel="stylesheet" href="../../CSS/Inventario/inventario.css">
    <link rel="icon" href="../../src/images/logo.ico">

    <link rel="stylesheet" href="https://unpkg.com/simplebar@latest/dist/simplebar.css" />
    <script src="https://unpkg.com/simplebar@latest/dist/simplebar.min.js"></script>
    <script src="../../JS/Inventario/inventario.js"></script>
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
                    <a href="dash.php?user=1" class="nav-link active"><i class="bi bi-box-seam"></i> Inventario</a>
                    <a href="../Pedidos/dash.php?user=1" class="nav-link"><i class="bi bi-truck"></i> Pedidos y Devoluciones</a>
                    <a href="../Proveedores/dash.php?user=1" class="nav-link"><i class="bi bi-globe"></i> Proveedores</a>
                    <a href="../Reportes/dash.php?user=1" class="nav-link"><i class="bi bi-clipboard-data"></i> Reportes</a>
                    <a href="../Configuracion/dash.php?user=1" class="nav-link"><i class="bi bi-gear"></i> Configuración</a>
                    <?php 
                        } else {
                    ?>
                        <a href="dash.php?user=<?php echo urlencode($_GET['user']); ?>" class="nav-link active"><i class="bi bi-box-seam"></i> Inventario</a>
                        <a href="../Pedidos/dash.php?user=<?php echo urlencode($_GET['user']); ?>" class="nav-link"><i class="bi bi-truck"></i> Pedidos y Devoluciones</a>
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
                    <div class="col-7">
                        <div class="card">
                            <div class="card-body">
                                <div class="row mb-2">
                                    <div class="col-6">
                                        <div class="input-group">
                                            <span class="input-group-text"><i class="bi bi-search"></i></span>
                                            <input type="text" class="form-control" placeholder="Producto" onkeyup="filterProducts(this.value)">
                                        </div>
                                    </div>
                                </div>
                                <h5 class="card-title">Registro:</h5>
                                <form id="productForm" action="controller.php" method="post">
                                <input type="hidden" name="user" value="<?= $_GET['user'] ?>">
                                    <div class="row mb-2">
                                        <div class="col">
                                            <input type="text" class="form-control" id="codigo-input" name="codigo" placeholder="Código">
                                        </div>
                                        <div class="col">
                                            <input type="number" class="form-control" id="entradas-input" name="entradas" placeholder="Entradas" min="0" value="0" oninput="validarEntradas(this)">
                                        </div>
                                        <div class="col">
                                            <button type="submit" class="btn btn-success col" onclick="setAction('register')">Registrar</button>
                                        </div>
                                    </div>
                                    <div class="row mb-2">
                                        <div class="col">
                                            <input type="text" class="form-control" id="producto-input" name="producto" placeholder="Producto">
                                        </div>
                                        <div class="col">
                                            <input type="number" class="form-control" id="salidas-input" name="salidas" placeholder="Salidas" min="0" value="0" oninput="validarSalidas(this)">
                                        </div>
                                        <div class="col">
                                            <button type="submit" class="btn btn-warning" onclick="setAction('modify')">Modificar</button>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col">
                                            <input type="text" class="form-control" id="codigo_proveedor" name="codigo_proveedor" placeholder="ID Proveedor">
                                        </div>
                                        <div class="col">
                                            <input type="text" class="form-control" id="descripcion-input" name="descripcion" placeholder="Descripción">
                                        </div>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>

                    <!-- Restock -->
                    <div class="col-5">
                        <div class="card">
                            <div class="card-body">
                                <h5 class="card-title text-center">Restock</h5>
                                <div class="col" data-simplebar style="max-height: 175px;">
                                <table class="table">
                                    <thead>
                                        <tr>
                                            <th>Código</th>
                                            <th>Producto</th>
                                            <th>Total</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php
                                            foreach ($vecLowStockProducts as $key => $value){
                                            ?>
                                        <tr class="selectable-row" onclick="fillForm('<?= sprintf('PR%05d', $value[0]) ?>', '<?= $value[1] ?>', '<?= $value[2] ?>', '<?= $value[3] ?>', '<?= $value[4] ?>', '<?= sprintf('PV%05d', $value[6]). ' - ' . $value[7] ?>')">
                                            <td><?= sprintf("PR%05d", $value[0]) ?></td>
                                            <td><?=$value[1]?></td>
                                            <td><?=$value[5]?></td>
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

                <!-- Tabla de Inventario -->
                <div class="row">
                    <div class="col" data-simplebar style="max-height: 250px;">
                        <table class="table" id="inventory-table">
                        <thead>
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
                            <tbody>
                                <?php
                                    foreach ($vecListProducts as $key => $value){
                                ?>
                                <tr class="selectable-row" onclick="fillForm('<?= sprintf('PR%05d', $value[0]) ?>', '<?= $value[1] ?>', '<?= $value[2] ?>', '<?= $value[3] ?>', '<?= $value[4] ?>', '<?= sprintf('PV%05d', $value[7]). ' - ' . $value[8] ?>')">
                                    <td><?= sprintf("PR%05d", $value[0]) ?></td>
                                    <td><?=$value[1]?></td>
                                    <td><?=$value[2]?></td>
                                    <td class="text-center"><?=$value[3]?></td>
                                    <td class="text-center"><?=$value[4]?></td>
                                    <td class="text-center"><?=$value[5]?></td>
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

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
