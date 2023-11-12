<?php
require_once '../basededatos/baseDeDatos.php';
require_once('alumno.php');
$db = new baseDeDatos;
$alumno = new Alumno;
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");
    if (isset($_GET['nombre'])) {
        // si encuentra json - verifica si hay alguien - nadie
        $listado = $db -> ejecutar("SELECT * FROM `alumnos` WHERE nombre LIKE '%".$_GET['nombre']."%'");
        if ($listado !== []) {
            echo json_encode($listado); // Si encuentra
            exit;
        }else if ($listado !==  $db -> ejecutar('SELECT * FROM alumnos ORDER BY apellido ASC') ) {
            $listado = [];
            echo json_encode($listado); // Si no encuentra pero hay alumnos
            exit;
        }
        else{
            $listado =  $db -> ejecutar('SELECT * FROM alumnos ORDER BY apellido ASC');
            echo json_encode($listado);
        }
    }
    if (isset($_GET['dni'])){
        echo json_encode($alumno -> getAlumno($db,$_GET['dni']));
    }
