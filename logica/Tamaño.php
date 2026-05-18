<?php
require_once(__DIR__ . "/../persistencia/TamañoDAO.php");
require_once(__DIR__ . "/../persistencia/Conexion.php");

class Tamaño{
    private $id;
    private $nombre;
    
    public function getId(){
        return $this -> id;
    }
    
    public function getTamaño(){
        return $this -> nombre;
    }
    public function __construct($id = 0, $nombre = ""){
        $this -> id = $id;
        $this -> nombre = $nombre;
    }
    public function consultar() {
        $conexion = new Conexion();
        $tamañoDAO = new TamañoDAO($this->id, $this->nombre);
        $conexion->abrir();
        $conexion->ejecutar($tamañoDAO->consultar());
        if (($fila = $conexion->registro()) != null) {
            $this->nombre = $fila[0];
            $conexion->cerrar();
            return true;
        }
        $conexion->cerrar();
        return false;
    }
    public function consultarTodos() {
        $conexion = new Conexion();
        $tamañoDAO = new TamañoDAO();
        
        $conexion->abrir();
        $conexion->ejecutar($tamañoDAO->consultarTodos());
        $resultados = [];
        while (($fila = $conexion->registro()) != null) {
            $tamaño = new Tamaño($fila[0], $fila[1]);
            $resultados[] = $tamaño;
        }
        $conexion->cerrar();
        return $resultados;
    }
}
?>