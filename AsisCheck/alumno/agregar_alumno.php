<?php

require_once("alumno.php");
require_once("../basededatos/baseDeDatos.php");
$alumno = new Alumno;
$db = new baseDeDatos;
$dni = $_POST['dni_alumno'];
$nombre = $_POST['nombre'];
$apellido = $_POST['apellido'];
$nacimiento = $_POST['nacimiento'];

$alumno -> setAlumno($db,$dni,$nombre,$apellido,$nacimiento);

?>