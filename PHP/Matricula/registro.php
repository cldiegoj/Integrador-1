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
    <?php
    include '../Negocio/negocio.php';
    $obj = new Negocio();
    $vec = $obj->lisAlu();
    $vec2 = $obj->lisSec();
    $vec3 = $obj->lisMatri();
    ?>

    <!-- Navbar -->
    <nav class="navbar navbar-expand-lg navbar-light custom-navbar">
        <div class="container-fluid">
            <a class="navbar-brand" href="#">
                <img src="../../src/images/logo.jpg" alt="Logo SnowBox" class="logo-img">
            </a>
            <span class="navbar-text text-white">
                Administrador
            </span>
            <a href="../../index.html" class="text-white"><i class="bi bi-box-arrow-left"></i></a>
        </div>
    </nav>

    <div class="container-fluid">
        <div class="row">
            <!-- Sidebar -->
            <div class="col-2 custom-sidebar">
                <div class="nav flex-column">

                    <a href="../Inventario/dash.php?user=1" class="nav-link"><i class="bi bi-box-seam"></i> Estudiantes</a>
                    <a href="registro.php" class="nav-link active"><i class="bi bi-truck"></i> Matricula</a>
                    <a href="../Proveedores/dash.php?user=1" class="nav-link"><i class="bi bi-globe"></i> Aulas</a>
                    <a href="dash.php?user=1" class="nav-link"><i class="bi bi-clipboard-data"></i> Reportes</a>
                    <a href="../Configuracion/dash.php?user=1" class="nav-link"><i class="bi bi-gear"></i> Configuración</a>
                </div>
            </div>

            <!-- Main Content -->
            <div class="col-10 mt-3">
                <!-- Formulario de Matrícula -->
                <form action="../Configuracion/controller.php" method="POST">
                    <div class="row">
                        <div class="col">
                            <div class="card">
                                <div class="card-body row g-3">
                                    <input type="hidden" name="registroID" value="">

                                    <!-- Alumno -->
                                    <div class="col-md-6">
                                        <label for="alumnoID" class="form-label">Alumno</label>
                                        <select class="form-select" id="alumnoID" name="alumnoID" required>
                                            <option selected disabled>Seleccione un alumno</option>
                                            <?php
                                            foreach ($vec as $d) {
                                            ?>
                                                <option id="AlumnoID" name="AlumnoID" value="<?= $d[0] ?>"><?= $d[1] ?></option>
                                            <?php
                                            }
                                            ?>
                                        </select>

                                    </div>

                                    <!-- Sección -->
                                    <div class="col-md-6">
                                        <label for="seccionID" class="form-label">Sección</label>
                                        <select class="form-select" id="seccionID" name="seccionID" required>
                                            <option selected disabled>Seleccione una sección</option>
                                            <!-- Opciones dinámicas -->
                                            <?php
                                            foreach ($vec2 as $s) {
                                            ?>
                                                <option id="SeccionID" name="SeccionID" value="<?= $s[0] ?>"><?= $s[1] ?></option>
                                            <?php
                                            }
                                            ?>
                                        </select>
                                    </div>

                                    <!-- Periodo Inicio -->
                                    <div class="col-md-6">
                                        <label for="periodoInicio" class="form-label">Periodo de Inicio</label>
                                        <input type="date" class="form-control" id="periodoInicio" name="periodoInicio" required>
                                    </div>

                                    <!-- Periodo Fin -->
                                    <div class="col-md-6">
                                        <label for="periodoFin" class="form-label">Periodo de Fin</label>
                                        <input type="date" class="form-control" id="periodoFin" name="periodoFin" required>
                                    </div>

                                    <!-- Estado -->
                                    <div class="col-md-6">
                                        <label for="estado" class="form-label">Estado</label>
                                        <select class="form-select" id="estado" name="estado" required>
                                            <option selected disabled>Seleccione estado</option>
                                            <option value="Activo">Activo</option>
                                            <option value="Retirado">Retirado</option>
                                            <option value="Suspendido">Suspendido</option>
                                        </select>
                                    </div>

                                    <?php

                                    ?>

                                    <!-- Botón -->
                                    <div class="col-md-6 d-flex align-items-end">
                                        <button id="guardarMatricula" name="guardarMatricula" class="btn btn-primary w-100">Registrar Matrícula</button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </form>

                <!-- Tabla de Matrículas -->
                <form action="../Reportes/generar_pdf.php" method="POST">
                    <div class="row mt-4">
                        <div class="col" data-simplebar style="max-height: 420px;">
                            <table class="table table-hover" id="matricula-table">
                                <thead>
                                    <tr>
                                        <th scope="col">Alumno</th>
                                        <th scope="col">Sección</th>
                                        <th scope="col">Periodo Inicio</th>
                                        <th scope="col">Periodo Fin</th>
                                        <th scope="col">Estado</th>
                                        <!--<th scope="col">Editar</th>-->
                                        <th scope="col">Eliminar</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <!-- Ejemplo fila -->
                                    <?php
                                    foreach ($vec3 as $m) {
                                    ?>
                                        <tr>
                                            <td><?= $m[1] ?></td>
                                            <td><?= $m[2] ?></td>
                                            <td><?= $m[3] ?></td>
                                            <td><?= $m[4] ?></td>
                                            <td><?= $m[5] ?></td>
                                            <!--<td><a href="#"><img style="width: 25px; height: 25px;" src="../../src/images/edit.png" alt="Logo Editar" class="logo-img"></a></td>-->
                                            <td><a href="../Configuracion/controller.php?action=delete&MatriculaID=<?= $m[0] ?>"><img style="width: 25px; height: 25px;" src="../../src/images/Eliminar.png" alt="Logo Eliminar" class="logo-img"></a></td>
                                        <?php
                                    }
                                        ?>
                                        </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                    <div class="row mt-5">
                        <div class="d-flex justify-content-center">
                            <td><button id="generarReporte" name="generarReporte" class="btn btn-success w-25 ">Generar reporte</button></td>
                        </div>
                    </div>
                </form>
            </div>

        </div>
    </div>


    <!--Contenedor Modal para editar matriculas-->
    <div class="modal fade" id="modelo">
        <div class="modal-header">
            <h2 class="modal-title">Actualizar Matricula</h2>
            <form action="control/input.php" method="post">
                <label for="alumnoID-edit" class="form-label">Alumno</label>
                <select class="form-select w-25" id="alumnoID-edit" name="alumnoID-edit" required>
                    <?php
                    foreach ($vec as $d) {
                    ?>
                        <option id="AlumnoID-edit" name="AlumnoID-edit" value="<?= $d[0] ?>"><?= $d[1] ?></option>
                    <?php
                    }
                    ?>
                </select>
                <br>
                <label for="seccionID-edit" class="form-label">Sección</label>
                <select class="form-select w-25" id="seccionID-edit" name="seccionID-edit" required>
                    <option selected disabled>Seleccione una sección</option>
                    <!-- Opciones dinámicas -->
                    <?php
                    foreach ($vec2 as $s) {
                    ?>
                        <option id="SeccionID-Edit" name="SeccionID-Edit" value="<?= $s[0] ?>"><?= $s[1] ?></option>
                    <?php
                    }
                    ?>
                </select>
                <br>
                <label for="Periodo-Inicio-Edit" class="form-label">Periodo Inicio:</label>
                <input class="form-select w-25" type="date" id="Periodo-Inicio-Edit" name="Periodo-Inicio-Edit" required>
                <br>
                <label for="Periodo-Fin-Edit" class="form-label">Periodo Fin:</label>
                <input class="form-select w-25" type="date" id="Periodo-Fin-Edit" name="Periodo-Fin-Edit" required>
                <br>
                <label for="estado-edit" class="form-label">Estado</label>
                <select class="form-select w-25" id="estado-edit" name="estado-edit" required>
                    <option value="Activo">Activo</option>
                    <option value="Retirado">Retirado</option>
                    <option value="Suspendido">Suspendido</option>
                </select>
                <center>
                    <div class="boton-cerrar">
                        <button class="btn btn-primary" id="boton-guardar" name="boton-guardar">Guardar</button>
                    </div>
                </center>
            </form>
        </div>
    </div>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>