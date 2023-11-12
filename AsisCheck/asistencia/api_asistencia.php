<?php
require_once '../basededatos/baseDeDatos.php';
$db = new baseDeDatos;
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");
    switch ($_GET['tipo']) {
        case 'listado':
            $listado = $db -> ejecutar("SELECT dni_alumno,nombre,apellido FROM alumnos");
            echo json_encode($listado);
            break;
        case 'registrar':
            $fecha = $_GET['fecha'];
            $fecha = date(("Y-m-d"),strtotime($fecha));
            $listado = $db -> ejecutar("SELECT dni_alumno,nombre,apellido FROM alumnos WHERE alumnos.dni_alumno NOT IN (SELECT dni FROM asistencias WHERE DATE(fecha) = '$fecha' )");
            echo json_encode($listado);
        break;
        case 'cantasistencia':
            $dni = $_GET['dni'];
            $listado = $db -> ejecutar("SELECT count(dni) FROM asistencias WHERE dni = $dni");
            echo json_encode($listado);
        break;
        case 'diasclase':
            $listado = $db -> ejecutar("SELECT cant_dias FROM parametros");
            echo json_encode($listado);
        break;
        case 'asistiohoy':
            date_default_timezone_set('America/Argentina/Buenos_Aires');
            $fecha= date('Y-m-d');
            $dni = $_GET['dni'];
            $listado = $db -> ejecutar("SELECT count(*) FROM asistencias WHERE date(fecha) = '$fecha' AND dni=$dni");
            echo json_encode($listado);
        break;
        case 'porcentajes':
            $listado = $db -> ejecutar("SELECT porcentaje_promocion,porcentaje_regular FROM parametros");
            echo json_encode($listado);
        break;
        case 'parametros':
            $listado = $db -> ejecutar("SELECT * FROM parametros");
            echo json_encode($listado);
        break;
    }
?>