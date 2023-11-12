<?php 
require_once('../basededatos/baseDeDatos.php');
$db = new baseDeDatos;
?>
<html>
    <script src="../js/sweetalert2.all.min.js"></script>
</html>
<?php
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $cant_clase = $_POST['diasclase'];
    $promocion = $_POST['promocion'];
    $regular = $_POST['regular'];
    // Las otras validaciones se hacen en el input PREGUNTAR
    if ($promocion > $regular) {
        $db->ejecutar("UPDATE parametros SET cant_dias = $cant_clase, porcentaje_promocion = $promocion, porcentaje_regular = $regular;");
        header("Location: " . $_SERVER['HTTP_REFERER']); // Volver a la pagina que manda el formulario
    } else {
        ?>
        <script>
            Swal.fire({
                icon: 'error',
                title: 'Error',
                showConfirmButton: false,
                text: 'El valor de promoción debe ser mayor que el valor para regular'
            });
            setTimeout(function() {
                window.location.href = '../index.php';
            }, 2500);
        </script>
        <?php
    }
}
?>