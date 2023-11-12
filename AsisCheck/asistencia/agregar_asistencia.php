<?php
    require_once('../basededatos/baseDeDatos.php');
    require_once('asistencia.php');
    $db = new baseDeDatos;
    $asistencia = new Asistencia;

        // Verificar si la solicitud es de tipo POST
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        // Recuperar los datos enviados en la solicitud POST
        $asistieron = isset($_POST['asistieron']) ? $_POST['asistieron'] : [];
        $fecha = isset($_POST['fecha']) ? $_POST['fecha'] : '';

        // Aquí puedes realizar las operaciones necesarias con los datos, como insertarlos en una base de datos
        // Por ejemplo, insertar los datos en una tabla de asistencias en una base de datos
        // Asegúrate de manejar errores y excepciones si es necesario
        $agregar = explode(',', $asistieron);
        // Enviar una respuesta JSON al cliente
        $asistencia -> setAsistencia($db,$agregar,$fecha);
    } else {
        // Si la solicitud no es de tipo POST, devolver un mensaje de error
        echo json_encode(['error' => 'Solicitud no valida']);
    }

    if ($_SERVER["REQUEST_METHOD"] == "GET") { // boton de agregar asistencia de index Alumno
        $alumno = array($_GET['dni']);
        date_default_timezone_set('America/Argentina/Buenos_Aires');
        $fecha= date('Y-m-d H:i:s');

        if ($asistencia -> verificarAsistencia($db,$_GET['dni'],$fecha) == 0) {
            $asistencia -> setAsistencia($db,$alumno,$fecha);
        }
        // Hacer alerta
        ?>
            <script>location.href = "../index.php";</script>
        <?php
    }


    
    





    // if ($_SERVER["REQUEST_METHOD"] == "POST") { // formulario index de asistencia
    //     $fecha = $_POST['fecha'];
    //     $presentes = $_POST['asistieron'];
    //     $consulta = [];
    //     //print_r($presentes);
    //     if (($fecha)&&($presentes)){
    //         foreach ($presentes as $alumno){
    //             if (($asistencia -> verificarAsistencia($db,$alumno,$fecha)) == 0){
    //                 array_push($consulta,$alumno);
    //             }
    //         }
    //         if (count($consulta)>0) {
    //             $asistencia -> setAsistencia($db,$consulta,$fecha);
    //         }else{ print 'No se agrego a nadie'; }
    //         ---
    //         <!-- <script>location.href = "index.php";</script> -->
    //         ---
    //     }
    // }
?>