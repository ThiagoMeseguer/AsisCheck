<?php

    class baseDeDatos {

        public $conex;
        private $host='localhost';
        private $dbname='sistema';
        private $username='root';
        private $password='';

        public function conectar() {
            try{
                $conex= new PDO('mysql:host='. $this -> host.';dbname='. $this -> dbname , $this -> username , $this -> password);
            }catch (Exception){
                return false; // Retorna falso en caso de error
            }
            return $this -> conex = $conex; 
        }

        public function desconectar() {
            $this -> conex = null;
        }

        public function ejecutar($query) {
            try {
                $this->conectar();
                $stmt = $this->conex->prepare($query); // Preparar la consulta
                $stmt->execute(); // Ejecutar la consulta
                $datos = $stmt->fetchAll(); // Obtener los datos
                $this->desconectar();
                return $datos;
            } catch (PDOException $e) {
                // En caso de un error, capturamos la excepción y manejamos el error
                throw new Exception("Error en la consulta: " . $e->getMessage());
            } finally {
                $this->desconectar(); // Asegurarse de desconectar incluso en caso de excepción
            }
        }
        
    }

?>