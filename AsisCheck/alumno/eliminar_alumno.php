<?php
require_once("../basededatos/baseDeDatos.php");
require_once("alumno.php");
require_once("../asistencia/asistencia.php");

$db = new baseDeDatos;
$alumno = new Alumno;
$asistencia = new Asistencia;

$dni = $_GET['dni_alumno'];

$array = $alumno -> getAlumno($db,$dni);

if ($array != "") {
    $alumno -> deleteAlumno($db,$dni);
    $asistencia -> deleteAsistencia($db, $dni);
    header("Location: ../index.php");
}else{
    ?> <script> window.alert("Error"); location.href ="../index.php"; </script> <?php
}
?>