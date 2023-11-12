<?php
    class Asistencia {
        
        public function setAsistencia($db,$presentes,$fecha){
            $db -> conectar();
            var_dump($presentes);
            $query= "INSERT INTO `asistencias` (`id`, `dni`, `fecha`) VALUES (null, :dni, '$fecha')";
            $stmt = $db -> conex -> prepare($query) ; 
            foreach ($presentes as $alumno) {
                try{
                    $stmt -> bindParam(":dni",$alumno);
                    $stmt->execute();
                }catch (PDOException $e) {
                    echo "Error al ejecutar la consulta: " . $e->getMessage() . "<br>";
                }
            }
            $db -> desconectar();
        }

        public function deleteAsistencia($db,$dni){
            $db -> conectar();
            $query = "DELETE FROM asistencias WHERE `asistencias`.`dni` = $dni";
            $db -> ejecutar($query);
            $db -> desconectar();
        }

        public function updateAsistencia($db,$dniViejo,$dniNuevo){
            $db -> conectar();
            $query = "UPDATE asistencias
            SET dni = $dniNuevo
            WHERE dni = $dniViejo";
            $db -> ejecutar($query);
            $db -> desconectar();
        }
        public function getListado($db) {
            $db -> conectar();
            $query = "SELECT * FROM `asistencias`";
            $datos = $db -> ejecutar($query);
            $db -> desconectar();
            return $datos;
        }
        
        public function verificarAsistencia($db,$dni,$fecha){
            $fecha = date(("Y-m-d"),strtotime($fecha));
            $db -> conectar();
                try{
                    $query= "SELECT count(dni) FROM `asistencias` WHERE dni = '$dni' AND date(fecha) = '$fecha';";
                    $stmt = $db -> conex -> prepare($query) ;
                    $stmt->execute();
                    $db -> desconectar();
                    $aux = $stmt -> fetch();
                    return $aux[0];//Cantidad de asistencia en esa fecha puede ser 0 o 1
                }catch (PDOException $e) {
                    echo "Error al ejecutar la consulta: " . $e->getMessage() . "<br>";
                }
        }
    }
?>