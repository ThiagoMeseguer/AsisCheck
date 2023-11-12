<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Reporte</title>
    
    <link href="../css/bootstrap.min.css" rel="stylesheet">
    <link href="../css/style.css" rel="stylesheet">
    <style>
      * {
        text-align: center;
      }
      #listado {
        color: white;
      }
      #listado li {
        padding-left: 40%;
        text-align: left;
      }
    </style>
    <nav class="navbar bg-dark border-bottom border-body" data-bs-theme="dark">
      <div class="container-fluid">
      <img src="../images/logo.png" alt="Logo de la escuela" class="img-fluid " width="75px">
      <ul class="nav">
        <li class="nav-item"> 
        <a class="nav-link  " aria-current="page" href="../index.php">Alumnos</a>
      </li>
        <li class="nav-item">
          <a class="nav-link" aria-current="page" href="../asistencia/index.php">Asistencia Tardía</a>
        </li>
        <li class="nav-item">
          <a class="nav-link disabled" aria-current="page" href="reporte/index.php">Reporte</a>
        </li>
      </ul>
    </div>
  </nav>
</head>
<body>
  <?php
    require_once("../basededatos/baseDeDatos.php");
    $db = new baseDeDatos;
    if ($db -> conectar()){
        if (isset($_GET['fecha'])) {
          $fecha = $_GET['fecha'];
          $listado = $db -> ejecutar("SELECT dni_alumno,nombre,apellido FROM alumnos WHERE alumnos.dni_alumno IN (SELECT dni FROM asistencias WHERE DATE(fecha) = '$fecha' )");
          $i = 0;
            if (count($listado) > 0) {
              ?>
              <div id="listado"><h5 style="color:white;">Listado Fecha: <?php print date('d-m-Y', strtotime($_GET['fecha'])) ?></h5>
              <?php
              foreach ($listado as $alumno){
                $i = $i+1;
                print ('<li id="alumno'.$i.'">'.$alumno['apellido'].', '.$alumno['nombre'].'</li><br>');
              } ?> 
              <input type="button" class="btn btn-success" id="descargapdf" value="Descargar Asistencia">
              <a class="btn btn-success" href="index.php">Otra consulta</a>
              </div> 
              <?php
            }else{ 
              print ' <h5>No hay asistencia </h5><a class="btn btn-success" href="index.php">Otra consulta</a>';
            }
          
        }else{ ?>
          <form action="index.php" method="get"> <br>
            <input type="date" id="fecha" name="fecha"> <br> <br>
            <input type="submit" class="btn btn-success btn-sm" id="descarga" name="descarga" value="Consultar">
          </form>
          <?php }
    }else{
        print '<br><h6>Hubo un error al conectar a la base de datos </h6>';
      }
    ?>
</body>
<script>
  document.getElementById("descargapdf").addEventListener("click", function () {
    const doc = new jsPDF();
    doc.text("Alumnos Presentes fecha: <?php echo date('d-m-Y') ?> ", 10, 10);
    <?php
    $i = 0;
    foreach ($listado as $alumno) {
      $i = $i + 1;
      echo 'doc.text("' . $alumno['nombre'] . ', ' . $alumno['apellido'] . '", 10, ' . (20 + $i * 10) . ');';
    }
    ?>
    // Guarda el PDF con el nombre "asistencias.pdf"
    doc.save("asistencia<?php echo date('d-m-Y') ?>.pdf");
  });

</script>
<script type="text/javascript" src="../js/jspdf.min.js"></script>
</html>