<?php
    require_once("../basededatos/baseDeDatos.php");
    require_once("alumno.php");
    require_once("../asistencia/asistencia.php");
    $db = new baseDeDatos;
    $alumnos = new Alumno;
    $asistencia = new Asistencia;

    $alumnos -> editarAlumno($db,$_POST['id_alumno'],$_POST['dni_alumno'],$_POST['nombre'],$_POST['apellido'],$_POST['nacimiento']);
    if ($_POST['dni_alumno'] != $_POST['dni_viejo']) {
        $asistencia -> updateAsistencia($db,$_POST['dni_viejo'],$_POST['dni_alumno']);
    }
    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        ?>  <script> window.alert("Editado con exito"); location.href ="../index.php"; </script> <?php
    }
?>