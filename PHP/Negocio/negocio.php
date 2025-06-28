<?php
include_once '../Conexion/conexion.php';

class Negocio
{

    function lisAlu(){
        $sql = "select AlumnoID, CONCAT(Nombres, ' ', Apellidos) as NombreCompleto from `alumnos`";
        $obj = new Conexion();
        $res = mysqli_query($obj->conecta(), $sql) or
            die(mysqli_error($obj->conecta()));
        $vec = array();
        while($fila = mysqli_fetch_array($res)){
            $vec[] = $fila;
        }
        return $vec;
    }

    function lisSec(){
        $sql = "select SeccionID, Nombre_Seccion from secciones";
        $obj = new Conexion();
        $res = mysqli_query($obj->conecta(), $sql) or
            die(mysqli_error($obj->conecta()));
        $vec = array();
        while($fila = mysqli_fetch_array($res)){
            $vec[] = $fila;
        }
        return $vec;
    }

    function lisMatri(){
        $sql = "select MatriculaID, a.nombres, s.nombre_seccion, Periodo_Inicio, Periodo_Fin, Estado FROM matriculas m JOIN alumnos a ON m.AlumnoID = a.AlumnoID JOIN secciones s ON m.SeccionID = s.SeccionID";
        $obj = new Conexion();
        $res = mysqli_query($obj->conecta(), $sql) or
            die(mysqli_error($obj->conecta()));
        $vec = array();
        while($fila = mysqli_fetch_array($res)){
            $vec[] = $fila;
        }
        return $vec;
    
    }

    function addMatri($AlumnoID,$SeccionID,$Periodo_Inicio,$Periodo_Fin,$Estado){
        $sql = "insert into `matriculas`(`AlumnoID`, `SeccionID`, `Periodo_Inicio`, `Periodo_Fin`, `Estado`) VALUES ($AlumnoID,$SeccionID,'$Periodo_Inicio','$Periodo_Fin','$Estado')";
        $obj = new Conexion();
        $conn = $obj->conecta();
        $res = mysqli_query($conn, $sql);

        return $res;
    }

    function deleteMatri($MatriculaID){
        $sql = "delete from matriculas where MatriculaID = $MatriculaID";
        $obj = new Conexion();
        $conn = $obj->conecta();
        $res = mysqli_query($conn, $sql);

        return $res;
    }
}
?>
