<?php
require '../Conexión/conexion.php';

//Capturamos la acción
$action = $_REQUEST['action'];


//Registrar
if ($action === 'register') {
    $iduser = $_POST['user'];

    $codigo = $_POST['codigo'];
    $entradas = $_POST['entradas'];
    $producto = $_POST['producto'];
    $salidas = $_POST['salidas'];
    $idproveedor = $_POST['codigo_proveedor'];
    $descripcion = $_POST['descripcion'];

    $numeroCodigo = (int) substr($codigo, 2);
    $numeroProveedor = intval(substr($idproveedor, 2, 5));

    //Comprobar si es nuevo el producto-de lo contrario mostrar alert
    $sql="SELECT id from Producto WHERE id='$numeroCodigo'";
    $consulta1=mysqli_query($conn,$sql);
    //echo fantasma para que funcione el script de sweetalert2
    echo "<h1></h1>";

    if (mysqli_num_rows($consulta1) > 0) {
        echo "
            <script src='https://cdn.jsdelivr.net/npm/sweetalert2@11'></script>
            <script>
                Swal.fire({
                    icon: 'error',
                    title: 'Error',
                    text: 'El producto no es nuevo, tiene un codigo ya usado'
                }).then((result) => {
                    if (result.isConfirmed || result.isDismissed) {
                        window.location.href = 'dash.php?user=$iduser';
                    }
                });
            </script>";
        exit; // Termina el script aquí
    } else {
        //Comprobar que el proveedor exista, sino mostrar alert
        $sql="SELECT id from Proveedor WHERE id='$numeroProveedor'";
        $consulta2=mysqli_query($conn,$sql);

        if (mysqli_num_rows($consulta2) > 0) {
            //Al ser nuevo no debe registrar salidas
            if (empty($salidas) || $salidas === "0") {
                //No puede duplicarse el nombre, para evitar duplicación de productos, se mostrara alert
                $sql="SELECT Nombre from Producto WHERE Nombre='$producto'";
                $consulta3=mysqli_query($conn,$sql);
                if (mysqli_num_rows($consulta3) > 0){
                    echo "
                        <script src='https://cdn.jsdelivr.net/npm/sweetalert2@11'></script>
                        <script>
                            Swal.fire({
                                icon: 'error',
                                title: 'Error',
                                text: 'Ya existe un producto con el mismo nombre'
                            }).then((result) => {
                                if (result.isConfirmed || result.isDismissed) {
                                    window.location.href = 'dash.php?user=$iduser';
                                }
                            });
                        </script>";
                    exit;
                } else {
                    //Las entradas sí o sí se deben registrar
                    if (empty($entradas)) {
                        echo "
                            <script src='https://cdn.jsdelivr.net/npm/sweetalert2@11'></script>
                            <script>
                                Swal.fire({
                                    icon: 'error',
                                    title: 'Error',
                                    text: 'Debe registrar entradas'
                                }).then((result) => {
                                    if (result.isConfirmed || result.isDismissed) {
                                        window.location.href = 'dash.php?user=$iduser';
                                    }
                                });
                            </script>";
                        exit; // Termina el script aquí
                    } else {
                        if (empty($codigo)) {
                            echo "
                                <script src='https://cdn.jsdelivr.net/npm/sweetalert2@11'></script>
                                <script>
                                    Swal.fire({
                                        icon: 'error',
                                        title: 'Error',
                                        text: 'Debe ingresar el código del producto'
                                    }).then((result) => {
                                        if (result.isConfirmed || result.isDismissed) {
                                            window.location.href = 'dash.php?user=$iduser';
                                        }
                                    });
                                </script>";
                            exit;
                        } else {
                            if (empty($producto)){
                                echo "
                                <script src='https://cdn.jsdelivr.net/npm/sweetalert2@11'></script>
                                <script>
                                    Swal.fire({
                                        icon: 'error',
                                        title: 'Error',
                                        text: 'Debe ingresar el nombre del producto'
                                    }).then((result) => {
                                        if (result.isConfirmed || result.isDismissed) {
                                            window.location.href = 'dash.php?user=$iduser';
                                        }
                                    });
                                </script>";
                                exit;
                            } else {
                                if (empty($idproveedor)){
                                    echo "
                                    <script src='https://cdn.jsdelivr.net/npm/sweetalert2@11'></script>
                                    <script>
                                        Swal.fire({
                                            icon: 'error',
                                            title: 'Error',
                                            text: 'Debe ingresar el codigo del proveedor'
                                        }).then((result) => {
                                            if (result.isConfirmed || result.isDismissed) {
                                                window.location.href = 'dash.php?user=$iduser';
                                            }
                                        });
                                    </script>";
                                    exit;
                                } else {
                                    if (empty($descripcion)){
                                        echo "
                                        <script src='https://cdn.jsdelivr.net/npm/sweetalert2@11'></script>
                                        <script>
                                            Swal.fire({
                                                icon: 'error',
                                                title: 'Error',
                                                text: 'Debe ingresar la descripción el producto'
                                            }).then((result) => {
                                                if (result.isConfirmed || result.isDismissed) {
                                                    window.location.href = 'dash.php?user=$iduser';
                                                }
                                            });
                                        </script>";
                                        exit;
                                    } else {
                                        //El código será generado al ultimo siguiente-en caso se coloque notificara que será el siguiente al ultimo.
                                        $sql="SELECT id from Producto WHERE id='$numeroCodigo'";
                                        $consulta6=mysqli_query($conn,$sql);

                                        if (mysqli_num_rows($consulta6) > 0) {
                                            //Registro con codigo corregido
                                            $sql3="SELECT COALESCE(MAX(id), 0) + 1 AS next_id FROM Producto";
                                            $result = mysqli_query($conn, $sql3);
                                            $row = mysqli_fetch_assoc($result);
                                            $nextIdP = $row['next_id'];
                                            $sql="INSERT INTO Producto (id,Nombre,Descripcion,Categoria,SubCategoria,Stock,Proveedor_id) VALUES ('$nextIdP','$producto', '$descripcion','Tecnología','Gadget','$entradas','$numeroProveedor')";
                                            mysqli_query($conn,$sql);

                                            $fechaActual = date('Y-m-d');

                                            //Registro en entradas
                                            $sql2="SELECT COALESCE(MAX(id), 0) + 1 AS next_id FROM Entradas";
                                            $result = mysqli_query($conn, $sql2);
                                            $row = mysqli_fetch_assoc($result);
                                            $nextId = $row['next_id'];
                                            $sql="INSERT INTO Entradas (id,fecha,cantidad,Producto_id) VALUES ('$nextId','$fechaActual','$entradas','$nextIdP')";
                                            mysqli_query($conn,$sql);
                                            echo "
                                                <script src='https://cdn.jsdelivr.net/npm/sweetalert2@11'></script>
                                                <script>
                                                    Swal.fire({
                                                        icon: 'success',
                                                        title: 'Listo',
                                                        text: 'Se registró el producto correctamente con el ID: " . sprintf("PR%05d", $nextIdP) . "'
                                                    }).then((result) => {
                                                        if (result.isConfirmed || result.isDismissed) {
                                                            window.location.href = 'dash.php?user=$iduser';
                                                        }
                                                    });
                                                </script>";
                                            exit; // Termina el script aquí
                                        } else {
                                            //Registro normal
                                            $sql="INSERT INTO Producto (id,Nombre,Descripcion,Categoria,SubCategoria,Stock,Proveedor_id) VALUES ('$numeroCodigo','$producto', '$descripcion','Tecnología','Gadget','$entradas','$numeroProveedor')";
                                            mysqli_query($conn,$sql);

                                            $fechaActual = date('Y-m-d');

                                            //Registro en entradas
                                            $sql2="SELECT COALESCE(MAX(id), 0) + 1 AS next_id FROM Entradas";
                                            $result = mysqli_query($conn, $sql2);
                                            $row = mysqli_fetch_assoc($result);
                                            $nextId = $row['next_id'];
                                            $sql="INSERT INTO Entradas (id,fecha,cantidad,Producto_id) VALUES ('$nextId','$fechaActual','$entradas','$numeroCodigo')";
                                            mysqli_query($conn,$sql);
                                            echo "
                                                <script src='https://cdn.jsdelivr.net/npm/sweetalert2@11'></script>
                                                <script>
                                                    Swal.fire({
                                                        icon: 'success',
                                                        title: 'Listo',
                                                        text: 'Se registro el producto correctamente'
                                                    }).then((result) => {
                                                        if (result.isConfirmed || result.isDismissed) {
                                                            window.location.href = 'dash.php?user=$iduser';
                                                        }
                                                    });
                                                </script>";
                                            exit;
                                        }
                                    }
                                }
                                
                            }
                        }
                    }
                }
            } else {
                echo "
                    <script src='https://cdn.jsdelivr.net/npm/sweetalert2@11'></script>
                    <script>
                        Swal.fire({
                            icon: 'error',
                            title: 'Error',
                            text: 'No se deben registrar salidas'
                        }).then((result) => {
                            if (result.isConfirmed || result.isDismissed) {
                                window.location.href = 'dash.php?user=$iduser';
                            }
                        });
                    </script>";
                exit; // Termina el script aquí
            }
        } else {
            echo "
                <script src='https://cdn.jsdelivr.net/npm/sweetalert2@11'></script>
                <script>
                    Swal.fire({
                        icon: 'error',
                        title: 'Error',
                        text: 'No existe el proveedor'
                    }).then((result) => {
                        if (result.isConfirmed || result.isDismissed) {
                            window.location.href = 'dash.php?user=$iduser';
                        }
                    });
                </script>";
            exit;
        }
    }
}


//Modificar
if ($action === 'modify') {
    $iduser = $_POST['user'];

    $codigo = $_POST['codigo'];
    $entradas = $_POST['entradas'];
    $producto = $_POST['producto'];
    $salidas = $_POST['salidas'];
    $idproveedor = $_POST['codigo_proveedor'];
    $descripcion = $_POST['descripcion'];

    $numeroCodigo = (int) substr($codigo, 2);
    $numeroProveedor = intval(substr($idproveedor, 2, 5));
    echo "<h1></h1>";
    //comprobaciones de datos
    if (empty($salidas)) {
        $salidas=0;
    } 
    if (empty($entradas)) {
        $entradas=0;
    } 
    if (empty($codigo)) {
        echo "
        <script src='https://cdn.jsdelivr.net/npm/sweetalert2@11'></script>
        <script>
            Swal.fire({
                    icon: 'error',
                    title: 'Error',
                    text: 'Debe ingresar el código del producto'
                    }).then((result) => {
                        if (result.isConfirmed || result.isDismissed) {
                            window.location.href = 'dash.php?user=$iduser';
                        }
                    });
        </script>";
        exit;
    } else {
        if (empty($producto)) {
            echo "
            <script src='https://cdn.jsdelivr.net/npm/sweetalert2@11'></script>
            <script>
                Swal.fire({
                        icon: 'error',
                        title: 'Error',
                        text: 'Debe ingresar el nombre del producto'
                        }).then((result) => {
                            if (result.isConfirmed || result.isDismissed) {
                                window.location.href = 'dash.php?user=$iduser';
                            }
                        });
            </script>";
            exit;
        } else {
            if (empty($idproveedor)) {
                echo "
                <script src='https://cdn.jsdelivr.net/npm/sweetalert2@11'></script>
                <script>
                    Swal.fire({
                            icon: 'error',
                            title: 'Error',
                            text: 'Debe ingresar el código del proveedor'
                            }).then((result) => {
                                if (result.isConfirmed || result.isDismissed) {
                                    window.location.href = 'dash.php?user=$iduser';
                                }
                            });
                </script>";
                exit;
            } else {
                if (empty($descripcion)) {
                    echo "
                    <script src='https://cdn.jsdelivr.net/npm/sweetalert2@11'></script>
                    <script>
                        Swal.fire({
                                icon: 'error',
                                title: 'Error',
                                text: 'Debe ingresar la descripción del proveedor'
                                }).then((result) => {
                                    if (result.isConfirmed || result.isDismissed) {
                                        window.location.href = 'dash.php?user=$iduser';
                                    }
                                });
                    </script>";
                    exit;
                } else{ 
                    //Comprobar que el código exista, esto no se puede modificar
                    $sql="SELECT id from Producto WHERE id='$numeroCodigo'";
                    $consulta1=mysqli_query($conn,$sql);
                    if (mysqli_num_rows($consulta1) > 0){
                        //Comprobar que las entradas sean mayor a las ya registradas, ya que solo se podrá aumentar de entradas con la fecha actual
                        $sql="SELECT COALESCE((SELECT SUM(e.cantidad) FROM Entradas e WHERE e.Producto_id = p.id), 0) AS entradas FROM Producto p WHERE p.id='$numeroCodigo'";
                        $result = mysqli_query($conn, $sql);
                        $row = mysqli_fetch_assoc($result);
                        $cantEnt = $row['entradas'];
                        if ($cantEnt<=$entradas){
                            //Comprobar que las salidas sean mayor a las ya registradas, ya que solo se podrá aumentar las salidas a las ya registradas
                            $sql="SELECT COALESCE((SELECT SUM(s.cantidad) FROM Salidas s WHERE s.Producto_id = p.id), 0) AS salidas FROM Producto p WHERE p.id='$numeroCodigo'";
                            $result = mysqli_query($conn, $sql);
                            $row = mysqli_fetch_assoc($result);
                            $cantSalt = $row['salidas'];
                            if ($cantSalt<=$salidas){
                                //La diferencia de entradas y salidas no puede ser menor a cero, sino mostrar alert
                                if ($entradas-$salidas>=0){
                                    //Comprobar que el proveedor exista, sino mostrar alert
                                    $sql="SELECT id from Proveedor WHERE id='$numeroProveedor'";
                                    $consulta2=mysqli_query($conn,$sql);

                                    if (mysqli_num_rows($consulta2) > 0) {
                                        //se mostara que se quiere aplicar los cambios en el producto ya registrado
                                        $sql="SELECT Nombre FROM Producto WHERE id='$numeroCodigo'";
                                        $result = mysqli_query($conn, $sql);
                                        $row = mysqli_fetch_assoc($result);
                                        $nombreOrig = $row['Nombre'];
                                        echo "
                                            <script src='https://cdn.jsdelivr.net/npm/sweetalert2@11'></script>
                                            <script>
                                                Swal.fire({
                                                    icon: 'warning',
                                                    title: 'Atención',
                                                    text: '¿Desea aplicar los cambios en el producto " . $nombreOrig . " con ID: " . $codigo . "?',
                                                    showCancelButton: true,
                                                    confirmButtonText: 'Sí, aplicar',
                                                    cancelButtonText: 'Cancelar'
                                                }).then((result) => {
                                                    if (result.isConfirmed) {
                                                        // Redirigir a la acción de aplicar cambios en PHP
                                                        window.location.href = 'controller.php?action=apply_changes&product_id={$numeroCodigo}&numEntradas={$entradas}&nomProducto=" . urlencode($producto) . "&numSalidas={$salidas}&numProveedor={$numeroProveedor}&descrip=" . urlencode($descripcion) . "&user={$iduser}';
                                                    } else {
                                                        // Redirigir al dashboard si se cancela
                                                        window.location.href = 'dash.php?user=$iduser';
                                                    }
                                                });
                                            </script>
                                            ";
                                            exit;
                                    } else {
                                        echo "
                                            <script src='https://cdn.jsdelivr.net/npm/sweetalert2@11'></script>
                                            <script>
                                                Swal.fire({
                                                    icon: 'error',
                                                    title: 'Error',
                                                    text: 'No existe el proveedor'
                                                }).then((result) => {
                                                    if (result.isConfirmed || result.isDismissed) {
                                                        window.location.href = 'dash.php?user=$iduser';
                                                    }
                                                });
                                            </script>";
                                        exit;
                                    }
                                } else {
                                    echo "
                                    <script src='https://cdn.jsdelivr.net/npm/sweetalert2@11'></script>
                                    <script>
                                        Swal.fire({
                                                icon: 'error',
                                                title: 'Error',
                                                text: 'Las salidas no pueden ser mayores a las entradas'
                                                }).then((result) => {
                                                    if (result.isConfirmed || result.isDismissed) {
                                                        window.location.href = 'dash.php?user=$iduser';
                                                    }
                                                });
                                    </script>";
                                    exit;
                                }
                            } else {
                                echo "
                                <script src='https://cdn.jsdelivr.net/npm/sweetalert2@11'></script>
                                <script>
                                    Swal.fire({
                                            icon: 'error',
                                            title: 'Error',
                                            text: 'Las salidas que se ingresan debe ser mayor a las ya registradas'
                                            }).then((result) => {
                                                if (result.isConfirmed || result.isDismissed) {
                                                    window.location.href = 'dash.php?user=$iduser';
                                                }
                                            });
                                </script>";
                                exit;
                            }
                        } else {
                            echo "
                            <script src='https://cdn.jsdelivr.net/npm/sweetalert2@11'></script>
                            <script>
                                Swal.fire({
                                        icon: 'error',
                                        title: 'Error',
                                        text: 'Las entradas que se ingresan debe ser mayor a las ya registradas'
                                        }).then((result) => {
                                            if (result.isConfirmed || result.isDismissed) {
                                                window.location.href = 'dash.php?user=$iduser';
                                            }
                                        });
                            </script>";
                            exit;
                        }
                    } else {
                        echo "
                        <script src='https://cdn.jsdelivr.net/npm/sweetalert2@11'></script>
                        <script>
                            Swal.fire({
                                    icon: 'error',
                                    title: 'Error',
                                    text: 'El producto no existe'
                                    }).then((result) => {
                                        if (result.isConfirmed || result.isDismissed) {
                                            window.location.href = 'dash.php?user=$iduser';
                                        }
                                    });
                        </script>";
                        exit;
                    }
                }
            }
        } 
    }
}

//aplicar cambios
if ($action === 'apply_changes') {
    echo "<h1></h1>";
    $iduser = $_GET['user'];

    $codigoProducto = $_GET['product_id'] ?? '';
    $numEntradas = $_GET['numEntradas'] ?? 0;
    $nombreProducto = $_GET['nomProducto'] ?? '';
    $numSalidas = $_GET['numSalidas'] ?? 0;
    $numProveedor = $_GET['numProveedor'] ?? 0;
    $descripcion = $_GET['descrip'] ?? '';

    $stock=$numEntradas-$numSalidas;
    $sql="UPDATE Producto SET Nombre='$nombreProducto', Descripcion='$descripcion', Stock='$stock', Proveedor_id='$numProveedor' WHERE id='$codigoProducto'";
    mysqli_query($conn,$sql);

    $fechaActual = date('Y-m-d');

    $sql="SELECT COALESCE((SELECT SUM(e.cantidad) FROM Entradas e WHERE e.Producto_id = p.id), 0) AS entradas FROM Producto p WHERE p.id='$codigoProducto'";
    $result = mysqli_query($conn, $sql);
    $row = mysqli_fetch_assoc($result);
    $cantEnt = $row['entradas'];

    $sql="SELECT COALESCE((SELECT SUM(s.cantidad) FROM Salidas s WHERE s.Producto_id = p.id), 0) AS salidas FROM Producto p WHERE p.id='$codigoProducto'";
    $result = mysqli_query($conn, $sql);
    $row = mysqli_fetch_assoc($result);
    $cantSalt = $row['salidas'];

    $auxEnt=$numEntradas-$cantEnt;
    $auxSalt=$numSalidas-$cantSalt;
    
    if ($auxEnt>0){
        $sql2="SELECT COALESCE(MAX(id), 0) + 1 AS next_id FROM Entradas";
        $result = mysqli_query($conn, $sql2);
        $row = mysqli_fetch_assoc($result);
        $nextId = $row['next_id'];

        $sql="INSERT INTO Entradas (id,fecha,cantidad,Producto_id) VALUES ('$nextId','$fechaActual','$auxEnt','$codigoProducto')";
        mysqli_query($conn,$sql);
    } 
    if ($auxSalt>0){
        $sql3="SELECT COALESCE(MAX(id), 0) + 1 AS next_id FROM Salidas";
        $result = mysqli_query($conn, $sql3);
        $row = mysqli_fetch_assoc($result);
        $nextId = $row['next_id'];

        $sql="INSERT INTO Salidas (id,fecha,cantidad,Producto_id) VALUES ('$nextId','$fechaActual','$auxSalt','$codigoProducto')";
        mysqli_query($conn,$sql);
    }
    echo "
        <script src='https://cdn.jsdelivr.net/npm/sweetalert2@11'></script>
        <script>
        Swal.fire({
            icon: 'success',
            title: 'Listo',
            text: 'Se hicieron los cambios correctamente'
        }).then((result) => {
            if (result.isConfirmed || result.isDismissed) {
                window.location.href = 'dash.php?user=$iduser';
            }
        });
        </script>";
    exit;
}                  



?>