<?php
require_once("basededatos/baseDeDatos.php");
require_once("alumno/alumno.php");
$db = new baseDeDatos();
$alumno = new Alumno();
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <link rel="shortcut icon" href="images/usuario.png">
  <link href="css/bootstrap.min.css" rel="stylesheet">
  <link href="css/style.css" rel="stylesheet">
  <title>Alumnos</title>

  <nav class="navbar bg-dark border-bottom border-body" data-bs-theme="dark">
    <div class="container-fluid">
      <img src="images/logo.png" alt="Logo de la escuela" class="img-fluid " width="75px">
      <ul class="nav">
        <!-- <li class="nav-item">
          <a class="nav-link" href="profesor/index_profesor.php">Profesores</a>
        </li> -->
        <li class="nav-item"> <!-- btn btn-secondary -->
          <a class="nav-link  disabled" aria-current="page" href="index.php">Alumnos</a>
        </li>
        <li class="nav-item">
          <a class="nav-link" aria-current="page" href="asistencia/index.php">Asistencia Tardía</a>
        </li>

        <li class="nav-item">
          <a class="nav-link " aria-current="page" data-bs-toggle="modal" data-bs-target="#modal_parametros">Parametros</a>
        </li>

        <li class="nav-item">
          <a class="nav-link " aria-current="page" href="reporte/index.php">Reporte</a>
        </li>

      </ul>
      <!-- Modal Parametros -->
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
                <form action="parametros/configurar_parametros.php" method="post" id="form_parametros">
                  <h5>Dias de clase</h5>
                  <input type="number" name="diasclase" id="diasclase" class="form-control;text-aling:center;" min="1" max="365" value="<?php print $parametros[0]['cant_dias'] ?>" required>
                  <h5>Porcentaje para promocion</h5>
                  <input type="number" name="promocion" id="promocion" class="form-control;text-aling:center;" min="1" max="100" value="<?php print $parametros[0]['porcentaje_promocion'] ?>" required>
                  <h5>Porcentaje para regular</h5>
                  <input type="number" name="regular" id="regular" class="form-control;text-aling:center;" min="1" max="100" value="<?php print $parametros[0]['porcentaje_regular'] ?>" required>
                  <br>
                  <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cerrar</button>
                    <button type="submit" class="btn btn-primary">Actualizar</button>
                  </div>
                </form>
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
    if (!$db->conectar()) { // Comprobar que se conecte con la base de datos 
      echo '<br><h6 style="text-align:center">Se produjo un error al conectar a la base de datos</h6><tr> ';
    } else {
    ?>
  <div class="container text-center">
    <br>
    <input class="form-label text-center" type="text" id="nombre" placeholder="Nombre">
    <br>
    <button class="form-label" id="buscar" name="buscar">Buscar</button>
    <div id="sinAlumnos"></div>
  </div>
  <div class="table-responsive " style="max-width: 1300px;margin: 0 auto;">
    <div id="error"></div>
    <table class="table table-dark " style="background-color:#33475b">
      <thead id="head_tabla">
        <thead>
          <tr>
            <th>Dni</th>
            <th>Apellido</th>
            <th>Nombre</th>
            <th class="col-1">Fecha Nacimiento</th>
            <th class="col-1">N°Asistencia</th>
            <th class="col-1">Promedio</th>
            <th class="col-2"> 
              <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#modalAgregarAlumno">
                Agregar Alumno
              </button>
              <!-- Modal para el formulario Agregar Alumno-->
              <div class="modal fade" id="modalAgregarAlumno" tabindex="-1" aria-labelledby="modalAgregarAlumnoLabel" aria-hidden="true">
                <div class="modal-dialog">
                  <div class="modal-content">
                    <div class="modal-header">
                      <h5 class="modal-title" style="color:black;text-align:center;" id="modalAgregarAlumnoLabel">Agregar Alumno</h5>
                      <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Cerrar"></button>
                    </div>
                    <div class="modal-body">
                      <div id="form_agregarAlumno">
                        <div class="mb-3">
                          <input type="number" id="agregar.dni_alumno" name="agregar.dni_alumno" class="form-control" placeholder="DNI" min=1 max=9999999999 required>
                        </div>
                        <div class="mb-3">
                          <input type="text" id="agregar.nombre" name="agregar.nombre" class="form-control" placeholder="NOMBRE" maxlenght="20" required>
                        </div>
                        <div class="mb-3">
                          <input type="text" id="agregar.apellido" name="agregar.apellido" class="form-control" placeholder="APELLIDO" maxlenght="20" required>
                        </div>
                        <div class="mb-3">
                          <input type="date" id="agregar.nacimiento" name="agregar.nacimiento" class="form-control" max="<?php echo date("Y-m-d") ?>" required>
                        </div>
                        <div class="modal-footer">
                          <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cerrar</button>
                          <button type="button" id="agregar" class="btn btn-primary" onclick="form_agregarAlumno()">Agregar Alumno</button>
                        </div>
                      </div>
                    </div>
                  </div>
                </div>
              </div>
            </th>
            <!-- Boton Agregar Asistencia -->
            <th class="col-2"></th>
            <!-- Boton Eliminar -->
            <th class="col-1"></th>
          </tr>
        </thead>
      </thead>
      <tbody id="body_tabla" class="">
        <!-- Listado de alumnos -->
      </tbody>
    </table>

    <!-- Modal Editar Alumno -->
    <div class="modal fade" id="exampleModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
      <div class="modal-dialog">
        <div class="modal-content">
          <div class="modal-header">
            <h5 class="modal-title" id="exampleModalLabel">Editar Alumno</h5>
            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
          </div>
          <div class="modal-body">
            <div id="formulario_editar"></div>
          </div>
        </div>
      </div>
    </div>
        <?php
        }
        ?>
  </div>
</body>
<script src="js/sweetalert2.all.min.js"></script>
<script src="alumno/listado_alumno.js"></script>
<script src="js/bootstrap.min.js"></script>

</html>