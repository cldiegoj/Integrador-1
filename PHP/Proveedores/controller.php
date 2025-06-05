<?php
require '../Conexión/conexion.php';

//Capturamos la acción
$action = $_REQUEST['action'];

//Registrar
if ($action === 'register') {
    $iduser = $_POST['user'];

    $codigo = $_POST['codigo'];
    $nombre = $_POST['nombre'];
    $ruc = $_POST['ruc'];
    $telefono = $_POST['telefono'];
    $correo = $_POST['correo'];

    $numeroCodigo = (int) substr($codigo, 2);

    //echo fantasma para que funcione el script de sweetalert2
    echo "<h1></h1>";

    //Comprobación de variables
    if (empty($codigo)) {
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
        if (empty($nombre)) {
            echo "
            <script src='https://cdn.jsdelivr.net/npm/sweetalert2@11'></script>
            <script>
                Swal.fire({
                        icon: 'error',
                        title: 'Error',
                        text: 'Debe ingresar el nombre del proveedor'
                        }).then((result) => {
                            if (result.isConfirmed || result.isDismissed) {
                                window.location.href = 'dash.php?user=$iduser';
                            }
                        });
            </script>";
            exit;
        } else {
            if (empty($ruc) || strlen($ruc) !== 11) {
                echo "
                <script src='https://cdn.jsdelivr.net/npm/sweetalert2@11'></script>
                <script>
                    Swal.fire({
                            icon: 'error',
                            title: 'Error',
                            text: 'Debe ingresar correctamente el RUC del proveedor'
                            }).then((result) => {
                                if (result.isConfirmed || result.isDismissed) {
                                    window.location.href = 'dash.php?user=$iduser';
                                }
                            });
                </script>";
                exit;
            } else {
                if (empty($telefono) || strlen($telefono) !== 9) {
                    echo "
                    <script src='https://cdn.jsdelivr.net/npm/sweetalert2@11'></script>
                    <script>
                        Swal.fire({
                                icon: 'error',
                                title: 'Error',
                                text: 'Debe ingresar correctamente el número de contacto del proveedor'
                                }).then((result) => {
                                    if (result.isConfirmed || result.isDismissed) {
                                        window.location.href = 'dash.php?user=$iduser';
                                    }
                                });
                    </script>";
                    exit;
                } else {
                    if (empty($correo)) {
                        echo "
                        <script src='https://cdn.jsdelivr.net/npm/sweetalert2@11'></script>
                        <script>
                            Swal.fire({
                                    icon: 'error',
                                    title: 'Error',
                                    text: 'Debe ingresar el correo del proveedor'
                                    }).then((result) => {
                                        if (result.isConfirmed || result.isDismissed) {
                                            window.location.href = 'dash.php?user=$iduser';
                                        }
                                    });
                        </script>";
                        exit;
                    } else {
                        //Comprobar si es nuevo el proveedor-de lo contrario mostrar alert
                        $sql="SELECT id from Proveedor WHERE id='$numeroCodigo'";
                        $consulta1=mysqli_query($conn,$sql);
                        if (mysqli_num_rows($consulta1) > 0) {
                            echo "
                                <script src='https://cdn.jsdelivr.net/npm/sweetalert2@11'></script>
                                <script>
                                    Swal.fire({
                                        icon: 'error',
                                        title: 'Error',
                                        text: 'El proveedor no es nuevo, tiene un codigo ya usado'
                                    }).then((result) => {
                                        if (result.isConfirmed || result.isDismissed) {
                                            window.location.href = 'dash.php?user=$iduser';
                                        }
                                    });
                                </script>";
                            exit; // Termina el script aquí
                        } else {
                            //No puede duplicarse el nombre, para evitar duplicación de proveedore, se mostrara alert
                            $sql="SELECT Nombre from Proveedor WHERE Nombre='$nombre'";
                            $consulta2=mysqli_query($conn,$sql);
                            if (mysqli_num_rows($consulta2) > 0){
                                echo "
                                    <script src='https://cdn.jsdelivr.net/npm/sweetalert2@11'></script>
                                    <script>
                                        Swal.fire({
                                            icon: 'error',
                                            title: 'Error',
                                            text: 'Ya existe un proveedor con el mismo nombre'
                                        }).then((result) => {
                                            if (result.isConfirmed || result.isDismissed) {
                                                window.location.href = 'dash.php?user=$iduser';
                                            }
                                        });
                                    </script>";
                                exit;
                            } else { 
                                //El código será generado al ultimo siguiente-en caso se coloque notificara que será el siguiente al ultimo.
                                $sql="SELECT id from Proveedor WHERE id='$numeroCodigo'";
                                $consulta3=mysqli_query($conn,$sql);

                                if (mysqli_num_rows($consulta3) > 0) {
                                    //Registro con codigo corregido
                                    $sql3="SELECT COALESCE(MAX(id), 0) + 1 AS next_id FROM Proveedor";
                                    $result = mysqli_query($conn, $sql3);
                                    $row = mysqli_fetch_assoc($result);
                                    $nextIdP = $row['next_id'];
                                    $sql="INSERT INTO Proveedor (id,Nombre,RUC,telefono,correo) VALUES ('$nextIdP','$nombre','$ruc','$telefono','$correo')";
                                    mysqli_query($conn,$sql);
                                    echo "
                                        <script src='https://cdn.jsdelivr.net/npm/sweetalert2@11'></script>
                                        <script>
                                            Swal.fire({
                                                icon: 'success',
                                                title: 'Listo',
                                                text: 'Se registró el proveedor correctamente con el ID: " . sprintf("PV%05d", $nextIdP) . "'
                                            }).then((result) => {
                                                if (result.isConfirmed || result.isDismissed) {
                                                    window.location.href = 'dash.php?user=$iduser';
                                                }
                                            });
                                        </script>";
                                    exit; // Termina el script aquí
                                } else {
                                    //Registro normal
                                    $sql="INSERT INTO Proveedor (id,Nombre,RUC,telefono,correo) VALUES ('$numeroCodigo','$nombre','$ruc','$telefono','$correo')";
                                    mysqli_query($conn,$sql);
                                    echo "
                                        <script src='https://cdn.jsdelivr.net/npm/sweetalert2@11'></script>
                                        <script>
                                            Swal.fire({
                                                icon: 'success',
                                                title: 'Listo',
                                                text: 'Se registro el proveedor correctamente'
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
    }
}


//Modificar
if ($action === 'modify') {
    $iduser = $_POST['user'];

    $codigo = $_POST['codigo'];
    $nombre = $_POST['nombre'];
    $ruc = $_POST['ruc'];
    $telefono = $_POST['telefono'];
    $correo = $_POST['correo'];

    $numeroCodigo = (int) substr($codigo, 2);

    echo "<h1></h1>";
    //Comprobación de variables
    if (empty($codigo)) {
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
        if (empty($nombre)) {
            echo "
            <script src='https://cdn.jsdelivr.net/npm/sweetalert2@11'></script>
            <script>
                Swal.fire({
                        icon: 'error',
                        title: 'Error',
                        text: 'Debe ingresar el nombre del proveedor'
                        }).then((result) => {
                            if (result.isConfirmed || result.isDismissed) {
                                window.location.href = 'dash.php?user=$iduser';
                            }
                        });
            </script>";
            exit;
        } else {
            if (empty($ruc) || strlen($ruc) !== 11) {
                echo "
                <script src='https://cdn.jsdelivr.net/npm/sweetalert2@11'></script>
                <script>
                    Swal.fire({
                            icon: 'error',
                            title: 'Error',
                            text: 'Debe ingresar correctamente el RUC del proveedor'
                            }).then((result) => {
                                if (result.isConfirmed || result.isDismissed) {
                                    window.location.href = 'dash.php?user=$iduser';
                                }
                            });
                </script>";
                exit;
            } else {
                if (empty($telefono) || strlen($telefono) !== 9) {
                    echo "
                    <script src='https://cdn.jsdelivr.net/npm/sweetalert2@11'></script>
                    <script>
                        Swal.fire({
                                icon: 'error',
                                title: 'Error',
                                text: 'Debe ingresar correctamente el número de contacto del proveedor'
                                }).then((result) => {
                                    if (result.isConfirmed || result.isDismissed) {
                                        window.location.href = 'dash.php?user=$iduser';
                                    }
                                });
                    </script>";
                    exit;
                } else {
                    if (empty($correo)) {
                        echo "
                        <script src='https://cdn.jsdelivr.net/npm/sweetalert2@11'></script>
                        <script>
                            Swal.fire({
                                    icon: 'error',
                                    title: 'Error',
                                    text: 'Debe ingresar el correo del proveedor'
                                    }).then((result) => {
                                        if (result.isConfirmed || result.isDismissed) {
                                            window.location.href = 'dash.php?user=$iduser';
                                        }
                                    });
                        </script>";
                        exit;
                    } else {
                        //Comprobar que el código exista, esto no se puede modificar
                        $sql="SELECT id from Proveedor WHERE id='$numeroCodigo'";
                        $consulta1=mysqli_query($conn,$sql);
                        if (mysqli_num_rows($consulta1) > 0){
                            //se mostara que se quiere aplicar los cambios en el producto ya registrado
                            $sql="SELECT Nombre FROM Proveedor WHERE id='$numeroCodigo'";
                            $result = mysqli_query($conn, $sql);
                            $row = mysqli_fetch_assoc($result);
                            $nombreOrig = $row['Nombre'];
                            echo "
                                <script src='https://cdn.jsdelivr.net/npm/sweetalert2@11'></script>
                                <script>
                                    Swal.fire({
                                        icon: 'warning',
                                        title: 'Atención',
                                        text: '¿Desea aplicar los cambios para el proveedor " . $nombreOrig . " con ID: " . $codigo . "?',
                                        showCancelButton: true,
                                        confirmButtonText: 'Sí, aplicar',
                                        cancelButtonText: 'Cancelar'
                                    }).then((result) => {
                                        if (result.isConfirmed) {
                                            // Redirigir a la acción de aplicar cambios en PHP
                                            window.location.href = 'controller.php?action=apply_changes&proveedor_id={$numeroCodigo}&nombre=" . urlencode($nombre) . "&ruc={$ruc}&telefono={$telefono}&correo=" . urlencode($correo) . "&user={$iduser}';
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
                                        text: 'El proveedor no existe'
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

//aplicar cambios
if ($action === 'apply_changes') {
    echo "<h1></h1>";
    $iduser = $_GET['user'];

    $codigoProducto = $_GET['proveedor_id'] ?? '';
    $nombre = $_GET['nombre'] ?? '';
    $ruc = $_GET['ruc'] ?? '';
    $telefono = $_GET['telefono'] ?? '';
    $correo = $_GET['correo'] ?? '';

    $sql="UPDATE Proveedor SET Nombre='$nombre', RUC='$ruc', telefono='$telefono', correo='$correo' WHERE id='$codigoProducto'";
    mysqli_query($conn,$sql);

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