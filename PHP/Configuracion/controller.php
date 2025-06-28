<?php
include '../Negocio/negocio.php';

//Registrar Matricula
if (isset($_POST["guardarMatricula"])) {
    $obj = new Negocio();
    $matricula = $obj->addMatri($_POST["alumnoID"], $_POST["seccionID"], $_POST["periodoInicio"], $_POST["periodoFin"], $_POST["estado"]);
    if (!$matricula) {
    } else {
        header("location: ../Matricula/registro.php");
    }
}

//Boton Eliminar
if (isset($_GET['action']) && $_GET['action'] === 'delete' && isset($_GET['MatriculaID'])) {
    $MatriculaID = $_GET['MatriculaID'];
    $negocio = new Negocio();
    $resultado = $negocio->deleteMatri($MatriculaID);

    if (!$resultado) {
    } else {
        header("location: ../Matricula/registro.php");
    }
}

//Boton Editar
/*
if (isset($_POST["botoneditar"])) {
    $objeto = new Negocio();
    $id_alumno = $_POST["id_alumno"];
    $dni = $_POST["dni"];
    $nombre = $_POST["nombres"];
    $apellido = $_POST["apellidos"];
    $aula = $_POST["id_aula"];
    $lenguaje = $_POST["lenguaje"];
    $mate = $_POST["matematica"];
    $edad = $_POST["edad"];

    $edit = $objeto->editMatri($id_alumno, $dni, $nombre, $apellido, $aula, $lenguaje, $mate, $edad);
    if (!$edit) {
    } else {
        header("location: ../dashboard.php");
    }
}*/
?>