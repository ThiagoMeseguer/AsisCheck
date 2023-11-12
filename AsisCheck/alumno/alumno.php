<?php

    class Alumno {

        public function getAlumno($db,$dni){
            $db -> conectar();
            $query= "SELECT * FROM alumnos WHERE dni_alumno= $dni ;";
            $stmt = $db -> conex -> prepare($query) ; //prepara la consulta
            $stmt -> execute(); // ejecuta la consulta
            $alumno= $stmt -> fetch(); // Devuelve los datos
            $db -> desconectar();
            return $alumno;
        }

        public function setAlumno($db,$dni,$nombre,$apellido,$nacimiento){
            $db -> conectar();
            $query= "INSERT INTO `alumnos`(`id_alumno`,`dni_alumno`, `nombre`, `apellido`, `nacimiento`) 
            VALUES ( NULL ,'$dni','$nombre','$apellido','$nacimiento')";
            $stmt = $db -> conex -> prepare($query) ; //prepara la consulta
            $stmt -> execute(); // ejecuta la consulta
            $alumno = $stmt -> fetchAll(); // Devuelve los datos
            $db -> desconectar();
            return $alumno;
        }

        public function editarAlumno($db,$id,$dni,$nombre,$apellido,$nacimiento){
            $db -> conectar();
            $query= "UPDATE `alumnos` 
            SET `dni_alumno`='$dni',`nombre`='$nombre',`apellido`='$apellido',`nacimiento`='$nacimiento' 
            WHERE `id_alumno`=$id";
            $stmt = $db -> conex -> prepare($query) ; //prepara la consulta
            $stmt -> execute(); // ejecuta la consulta
            $alumno = $stmt -> fetchAll(); // Devuelve los datos
            $db -> desconectar();
            return $alumno;
        }

        public function deleteAlumno($db,$dni){
            $db -> conectar();
            $query= "DELETE FROM `alumnos` WHERE `dni_alumno`= $dni ";
            $stmt = $db -> conex -> prepare($query) ; //prepara la consulta
            $stmt -> execute(); // ejecuta la consulta
            $stmt -> fetchAll();
            $db -> desconectar();
        }

        
        public function getListado($db){
            $db -> conectar();
                $query= "SELECT * FROM alumnos ORDER BY apellido ASC";
                $stmt = $db -> conex -> prepare($query) ; //prepara la consulta
                $stmt -> execute(); // ejecuta la consulta
                $alumno = $stmt -> fetchAll();
                return $alumno;
            $db -> desconectar();
        }
    }
?>