<?php
require '../Conexión/conexion.php';

//Capturamos la acción
$action = $_REQUEST['action'];

//Registrar
if ($action === 'register') {
    $iduser = $_POST['user'];

    $codigo = $_POST['codigo'];
    $nombre = $_POST['nombre'];
    $dni = $_POST['dni'];
    $telefono = $_POST['telefono'];
    $contrasenia = $_POST['contrasenia'];

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
                    text: 'Debe ingresar el codigo del usuario'
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
                        text: 'Debe ingresar el nombre del usuario'
                        }).then((result) => {
                            if (result.isConfirmed || result.isDismissed) {
                                window.location.href = 'dash.php?user=$iduser';
                            }
                        });
            </script>";
            exit;
        } else {
            if (empty($dni) || strlen($dni) !== 8) {
                echo "
                <script src='https://cdn.jsdelivr.net/npm/sweetalert2@11'></script>
                <script>
                    Swal.fire({
                            icon: 'error',
                            title: 'Error',
                            text: 'Debe ingresar correctamente el DNI del usuario'
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
                                text: 'Debe ingresar correctamente el número de teléfono del usuario'
                                }).then((result) => {
                                    if (result.isConfirmed || result.isDismissed) {
                                        window.location.href = 'dash.php?user=$iduser';
                                    }
                                });
                    </script>";
                    exit;
                } else {
                    if (empty($contrasenia)) {
                        echo "
                        <script src='https://cdn.jsdelivr.net/npm/sweetalert2@11'></script>
                        <script>
                            Swal.fire({
                                    icon: 'error',
                                    title: 'Error',
                                    text: 'Debe ingresar la contraseña del usuario'
                                    }).then((result) => {
                                        if (result.isConfirmed || result.isDismissed) {
                                            window.location.href = 'dash.php?user=$iduser';
                                        }
                                    });
                        </script>";
                        exit;
                    } else {
                        //Comprobar si es nuevo el usuario-de lo contrario mostrar alert
                        $sql="SELECT id FROM Usuario WHERE id='$numeroCodigo'";
                        $consulta1=mysqli_query($conn,$sql);
                        if (mysqli_num_rows($consulta1) > 0) {
                            echo "
                                <script src='https://cdn.jsdelivr.net/npm/sweetalert2@11'></script>
                                <script>
                                    Swal.fire({
                                        icon: 'error',
                                        title: 'Error',
                                        text: 'El usuario no es nuevo, tiene un codigo ya usado'
                                    }).then((result) => {
                                        if (result.isConfirmed || result.isDismissed) {
                                            window.location.href = 'dash.php?user=$iduser';
                                        }
                                    });
                                </script>";
                            exit; // Termina el script aquí
                        } else {
                            //No puede duplicarse el nombre, para evitar duplicación de proveedore, se mostrara alert
                            $sql="SELECT nombre_completo from Usuario WHERE nombre_completo='$nombre'";
                            $consulta2=mysqli_query($conn,$sql);
                            if (mysqli_num_rows($consulta2) > 0){
                                echo "
                                    <script src='https://cdn.jsdelivr.net/npm/sweetalert2@11'></script>
                                    <script>
                                        Swal.fire({
                                            icon: 'error',
                                            title: 'Error',
                                            text: 'Ya existe un usuario con el mismo nombre'
                                        }).then((result) => {
                                            if (result.isConfirmed || result.isDismissed) {
                                                window.location.href = 'dash.php?user=$iduser';
                                            }
                                        });
                                    </script>";
                                exit;
                            } else { 
                                //El código será generado al ultimo siguiente-en caso se coloque notificara que será el siguiente al ultimo.
                                $sql="SELECT id from Usuario WHERE id='$numeroCodigo'";
                                $consulta3=mysqli_query($conn,$sql);

                                if (mysqli_num_rows($consulta3) > 0) {
                                    //Registro con codigo corregido
                                    $sql3="SELECT COALESCE(MAX(id), 0) + 1 AS next_id FROM Usuario";
                                    $result = mysqli_query($conn, $sql3);
                                    $row = mysqli_fetch_assoc($result);
                                    $nextIdP = $row['next_id'];
                                    $sql="INSERT INTO Usuario (id,nombre_completo,dni,contrasenia,telefono,estado) VALUES ('$nextIdP','$nombre','$dni','$contrasenia','$telefono','1')";
                                    mysqli_query($conn,$sql);
                                    echo "
                                        <script src='https://cdn.jsdelivr.net/npm/sweetalert2@11'></script>
                                        <script>
                                            Swal.fire({
                                                icon: 'success',
                                                title: 'Listo',
                                                text: 'Se registró el usuario correctamente con el ID: " . sprintf("UR%05d", $nextIdP) . "'
                                            }).then((result) => {
                                                if (result.isConfirmed || result.isDismissed) {
                                                    window.location.href = 'dash.php?user=$iduser';
                                                }
                                            });
                                        </script>";
                                    exit; // Termina el script aquí
                                } else {
                                    //Registro normal
                                    $sql="INSERT INTO Usuario (id,nombre_completo,dni,contrasenia,telefono,estado) VALUES ('$numeroCodigo','$nombre','$dni','$contrasenia','$telefono','1')";
                                    mysqli_query($conn,$sql);
                                    echo "
                                        <script src='https://cdn.jsdelivr.net/npm/sweetalert2@11'></script>
                                        <script>
                                            Swal.fire({
                                                icon: 'success',
                                                title: 'Listo',
                                                text: 'Se registro el usuario correctamente'
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
    $dni = $_POST['dni'];
    $telefono = $_POST['telefono'];
    $contrasenia = $_POST['contrasenia'];

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
                    text: 'Debe ingresar el codigo del usuario'
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
                        text: 'Debe ingresar el nombre del usuario'
                        }).then((result) => {
                            if (result.isConfirmed || result.isDismissed) {
                                window.location.href = 'dash.php?user=$iduser';
                            }
                        });
            </script>";
            exit;
        } else {
            if (empty($dni) || strlen($dni) !== 8) {
                echo "
                <script src='https://cdn.jsdelivr.net/npm/sweetalert2@11'></script>
                <script>
                    Swal.fire({
                            icon: 'error',
                            title: 'Error',
                            text: 'Debe ingresar correctamente el DNI del usuario'
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
                                text: 'Debe ingresar correctamente el número de teléfono del usuario'
                                }).then((result) => {
                                    if (result.isConfirmed || result.isDismissed) {
                                        window.location.href = 'dash.php?user=$iduser';
                                    }
                                });
                    </script>";
                    exit;
                } else {
                    if (empty($contrasenia)) {
                        echo "
                        <script src='https://cdn.jsdelivr.net/npm/sweetalert2@11'></script>
                        <script>
                            Swal.fire({
                                    icon: 'error',
                                    title: 'Error',
                                    text: 'Debe ingresar la contraseña del usuario'
                                    }).then((result) => {
                                        if (result.isConfirmed || result.isDismissed) {
                                            window.location.href = 'dash.php?user=$iduser';
                                        }
                                    });
                        </script>";
                        exit;
                    } else {
                        //Comprobar que el código exista, esto no se puede modificar
                        $sql="SELECT id from Usuario WHERE id='$numeroCodigo'";
                        $consulta1=mysqli_query($conn,$sql);
                        if (mysqli_num_rows($consulta1) > 0){
                            //se mostara que se quiere aplicar los cambios en el usuario ya registrado
                            $sql="SELECT nombre_completo FROM Usuario WHERE id='$numeroCodigo'";
                            $result = mysqli_query($conn, $sql);
                            $row = mysqli_fetch_assoc($result);
                            $nombreOrig = $row['nombre_completo'];
                            echo "
                                <script src='https://cdn.jsdelivr.net/npm/sweetalert2@11'></script>
                                <script>
                                    Swal.fire({
                                        icon: 'warning',
                                        title: 'Atención',
                                        text: '¿Desea aplicar los cambios para el usuario " . $nombreOrig . " con ID: " . $codigo . "?',
                                        showCancelButton: true,
                                        confirmButtonText: 'Sí, aplicar',
                                        cancelButtonText: 'Cancelar'
                                    }).then((result) => {
                                        if (result.isConfirmed) {
                                            // Redirigir a la acción de aplicar cambios en PHP
                                            window.location.href = 'controller.php?action=apply_changes&user_id={$numeroCodigo}&nombre=" . urlencode($nombre) . "&dni={$dni}&telefono={$telefono}&contrasenia=" . urlencode($contrasenia) . "&user={$iduser}';
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
                                        text: 'El usuario no existe'
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

    $codigo = $_GET['user_id'] ?? '';
    $nombre = $_GET['nombre'] ?? '';
    $dni = $_GET['dni'] ?? '';
    $telefono = $_GET['telefono'] ?? '';
    $contrasenia = $_GET['contrasenia'] ?? '';


    $sql="SELECT contrasenia FROM Usuario WHERE id='$codigo'";
    $result = mysqli_query($conn, $sql);
    $row = mysqli_fetch_assoc($result);
    $contraOrig = $row['contrasenia'];

    if($contraOrig==$contrasenia){
        $sql="UPDATE Usuario SET nombre_completo='$nombre', dni='$dni', telefono='$telefono' WHERE id='$codigo'";
        mysqli_query($conn,$sql);
    }else{
        $pass = hash('sha256', $contrasenia);
        $sql="UPDATE Usuario SET nombre_completo='$nombre', dni='$dni', telefono='$telefono', contrasenia='$pass' WHERE id='$codigo'";
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



//Eliminar
if ($action === 'delete') {
    $iduser = $_POST['user'];

    $codigo = $_POST['codigo'];

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
                    text: 'Debe ingresar el codigo del usuario'
                    }).then((result) => {
                        if (result.isConfirmed || result.isDismissed) {
                            window.location.href = 'dash.php?user=$iduser';
                        }
                    });
        </script>";
        exit;
    } else {
        //Comprobar que el código exista
        $sql="SELECT id from Usuario WHERE id='$numeroCodigo'";
        $consulta1=mysqli_query($conn,$sql);
        if (mysqli_num_rows($consulta1) > 0){
            //se mostara que se quiere aplicar los cambios en el usuario ya registrado
            $sql="SELECT nombre_completo FROM Usuario WHERE id='$numeroCodigo'";
            $result = mysqli_query($conn, $sql);
            $row = mysqli_fetch_assoc($result);
            $nombreOrig = $row['nombre_completo'];
            echo "
                <script src='https://cdn.jsdelivr.net/npm/sweetalert2@11'></script>
                <script>
                    Swal.fire({
                        icon: 'warning',
                        title: 'Atención',
                        text: '¿Desea eliminar al usuario " . $nombreOrig . " con ID: " . $codigo . "?',
                        showCancelButton: true,
                        confirmButtonText: 'Sí, aplicar',
                        cancelButtonText: 'Cancelar'
                    }).then((result) => {
                        if (result.isConfirmed) {
                            // Redirigir a la acción de aplicar cambios en PHP
                            window.location.href = 'controller.php?action=apply_delete&user_id={$numeroCodigo}&user={$iduser}';
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
                        text: 'El usuario no existe'
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


//aplicar eliminar
if ($action === 'apply_delete') {
    echo "<h1></h1>";
    $iduser = $_GET['user'];

    $codigo = $_GET['user_id'] ?? '';

    $sql="UPDATE Usuario SET estado='0' WHERE id='$codigo'";
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