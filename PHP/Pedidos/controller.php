<?php
require '../Conexión/conexion.php';

//Capturamos la acción
$action = $_REQUEST['action'];

//Registrar
if ($action === 'register') {
    $iduser = $_POST['user'];
    $cantidad = $_POST['cantidad']; 
    $descripcion = $_POST['descripcion'];

    //echo fantasma para que funcione el script de sweetalert2
    echo "<h1></h1>";

    $sql3="SELECT COALESCE(MAX(id), 0) + 1 AS next_id FROM Solicitud";
    $result = mysqli_query($conn, $sql3);
    $row = mysqli_fetch_assoc($result);
    $nextIdP = $row['next_id'];

    $fechaActual = date('Y-m-d');

    //Comprobación de variables
    if (empty($descripcion)) {
        echo "
        <script src='https://cdn.jsdelivr.net/npm/sweetalert2@11'></script>
        <script>
            Swal.fire({
                    icon: 'error',
                    title: 'Error',
                    text: 'Debe ingresar la descripción de la solicitud'
                    }).then((result) => {
                        if (result.isConfirmed || result.isDismissed) {
                            window.location.href = 'dash.php?user=$iduser';
                        }
                    });
        </script>";
        exit;
    } else {
        if (empty($cantidad) || $cantidad == 0) {
            echo "
            <script src='https://cdn.jsdelivr.net/npm/sweetalert2@11'></script>
            <script>
                Swal.fire({
                        icon: 'error',
                        title: 'Error',
                        text: 'Debe ingresar la cantidad de productos en la solicitud'
                        }).then((result) => {
                            if (result.isConfirmed || result.isDismissed) {
                                window.location.href = 'dash.php?user=$iduser';
                            }
                        });
            </script>";
            exit;
        } else {
            if (isset($_POST['producto']) && $_POST['producto'] != '0') {
                $producto = $_POST['producto'];
                
                if (isset($_POST['tipo']) && $_POST['tipo'] != '0') {
                    $tipo = $_POST['tipo'];

                    //Registrar Pedido
                    if ($tipo == 1) {
                        $sql="INSERT INTO Solicitud (id,Usuario_id,Producto_id,Tipo,Descripcion,cantidad,fecha,estado) VALUES ('$nextIdP','$iduser','$producto','Pedido','$descripcion','$cantidad','$fechaActual','En espera')";
                        mysqli_query($conn,$sql);
                        echo "
                            <script src='https://cdn.jsdelivr.net/npm/sweetalert2@11'></script>
                            <script>
                                Swal.fire({
                                    icon: 'success',
                                    title: 'Listo',
                                    text: 'Se registró el pedido con el ID: " . sprintf("EV%05d", $nextIdP) . "'
                                }).then((result) => {
                                    if (result.isConfirmed || result.isDismissed) {
                                        window.location.href = 'dash.php?user=$iduser';
                                    }
                                });
                            </script>";
                        exit; // Termina el script aquí
                    } else {
                        //Registrar Devolucion
                        

                        $sql="SELECT Stock FROM Producto p WHERE p.id='$producto'";
                        $result = mysqli_query($conn, $sql);
                        $row = mysqli_fetch_assoc($result);
                        $stock = $row['Stock'];

                        if($stock-$cantidad>=0){
                            $stock=$stock-$cantidad;

                            $sql="UPDATE Producto SET Stock='$stock' WHERE id='$producto'";
                            mysqli_query($conn,$sql);

                            $sql="INSERT INTO Solicitud (id,Usuario_id,Producto_id,Tipo,Descripcion,cantidad,fecha,estado) VALUES ('$nextIdP','$iduser','$producto','Devolución','$descripcion','$cantidad','$fechaActual','En espera')";
                            mysqli_query($conn,$sql);
                            echo "
                                <script src='https://cdn.jsdelivr.net/npm/sweetalert2@11'></script>
                                <script>
                                    Swal.fire({
                                        icon: 'success',
                                        title: 'Listo',
                                        text: 'Se registró la devolución del producto con el ID: " . sprintf("EV%05d", $nextIdP) . "'
                                    }).then((result) => {
                                        if (result.isConfirmed || result.isDismissed) {
                                            window.location.href = 'dash.php?user=$iduser';
                                        }
                                    });
                                </script>";
                            exit; // Termina el script aquí
                        }else{
                            echo "
                                <script src='https://cdn.jsdelivr.net/npm/sweetalert2@11'></script>
                                <script>
                                    Swal.fire({
                                            icon: 'error',
                                            title: 'Error',
                                            text: 'La cantidad a devolver del producto es mayor al stock actual'
                                            }).then((result) => {
                                                if (result.isConfirmed || result.isDismissed) {
                                                    window.location.href = 'dash.php?user=$iduser';
                                                }
                                            });
                                </script>";
                                exit;
                        }
                    }
                } else {
                    echo "
                    <script src='https://cdn.jsdelivr.net/npm/sweetalert2@11'></script>
                    <script>
                        Swal.fire({
                                icon: 'error',
                                title: 'Error',
                                text: 'Debe ingresar el tipo de solicitud'
                                }).then((result) => {
                                    if (result.isConfirmed || result.isDismissed) {
                                        window.location.href = 'dash.php?user=$iduser';
                                    }
                                });
                    </script>";
                    exit;
                }
            }else {
                echo "
                <script src='https://cdn.jsdelivr.net/npm/sweetalert2@11'></script>
                <script>
                    Swal.fire({
                            icon: 'error',
                            title: 'Error',
                            text: 'Debe ingresar el producto para la solicitud'
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



?>