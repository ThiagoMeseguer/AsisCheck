<?php 
    require_once('../basededatos/baseDeDatos.php');
    require_once('asistencia.php');
    $db = new baseDeDatos;
    $asistencia = new Asistencia;
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Agregar Asistencia</title>
    <link rel="shortcut icon" href="../images/evaluacion.png">
    <link href="../css/bootstrap.min.css" rel="stylesheet">
    <link href="../css/style.css" rel="stylesheet">

    <nav class="navbar bg-dark border-bottom border-body" data-bs-theme="dark">
        <div class="container-fluid">
        <img src="../images/logo.png" alt="Logo de la escuela" class="img-fluid " width="75px">
        <ul class="nav">
            <!-- <li class="nav-item">
            <a class="nav-link" href="../profesor/index_profesor.php">Profesores</a>
            </li> -->
            <li class="nav-item"> <!-- btn btn-secondary -->
            <a class="nav-link " aria-current="page" href="../index.php">Alumnos</a>
            </li>
            <li class="nav-item">
            <a class="nav-link disabled" aria-current="page" href="asistencia/index.php">Asistencia Tardía</a>
            </li>

            <li class="nav-item">
            <a class="nav-link " aria-current="page" data-bs-toggle="modal" data-bs-target="#modal_parametros">Parametros</a>
            </li>
        </ul>
        <div class="modal fade" id="modal_parametros" tabindex="-1" aria-labelledby="modal_parametros" aria-hidden="true">
        <div class="modal-dialog">
          <div class="modal-content">
            <div class="modal-header">
              <h5 class="modal-title" style="color:white;text-align:center;" id="modal_parametros">Parametros</h5>
              <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Cerrar"></button>
            </div>
            <div class="modal-body" style="color:white;text-align:center;">
              <?php if (!$db->conectar()) { // Comprobar que se conecte con la base de datos 
                echo "<h6>Se produjo un error al conectar a la base de datos</h6> ";
              } else {
                $parametros = $db->ejecutar("SELECT * FROM parametros");
              ?>
                <div id="form_parametros">
                  <h5>Dias de clase</h5>
                  <input type="number" name="diasclase" id="diasclase" class="form-control;text-aling:center;" min="1" max="365" value="<?php print $parametros[0]['cant_dias'] ?>" required>
                  <h5>Porcentaje para promocion</h5>
                  <input type="number" name="promocion" id="promocion" class="form-control;text-aling:center;" min="1" max="100" value="<?php print $parametros[0]['porcentaje_promocion'] ?>" required>
                  <h5>Porcentaje para regular</h5>
                  <input type="number" name="regular" id="regular" class="form-control;text-aling:center;" min="1" max="100" value="<?php print $parametros[0]['porcentaje_regular'] ?>" required>
                  <br>
                  <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cerrar</button>
                    <button type="button" class="btn btn-primary" onclick="formParametros()">Actualizar</button>
                  </div>
                </div>
              <?php
              } // Cerrar IF 
              ?>
            </div>
          </div>
        </div>
      </div>
        </div>
  </nav>
</head>
<body>
    <?php 
      if (!$db -> conectar()) {
        print '<br><h6 style="text-align: center;">Hubo un error al conectar a la base de datos</h6>';
      }else{
        $cant_alumnos = $db -> ejecutar("SELECT dni_alumno FROM alumnos");
        if (count($cant_alumnos)>0) { ?>
          <div class="container" style="text-align:center">
              <br>
              <h5 style="color:white;">Seleccione una fecha</h5>
              <div id="formularioAsistencia">
                  <input type="datetime-local" name="fecha" id="fecha">
                  <br>
                  <div class="mx-auto" style="max-width: 700px;">
                  <br>
                      <!-- Listado de alumnos -->
                      <table id="listado" class="table table-dark" style="text-align:center;" >
                          <thead></thead>
                          <tbody id="body_tabla"></tbody>
                      </table>
                  </div>
                  <div id="btn-registrar" ></div>
              </form>
          </div> <?php
        }else{
          print '<br><h5 style="text-align: center;color:white;">Sin alumnos registrados</h5>';
        }
    ?>
    <?php } ?>
</body>
<script src="../js/sweetalert2.all.min.js"></script>
<script src="listado_asistencia.js"></script>
<script src="../js/bootstrap.min.js"></script>
</html>
