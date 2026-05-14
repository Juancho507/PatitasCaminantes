<?php
require_once ("persistencia/TamañoDAO.php");
require_once ("persistencia/Conexion.php");

class Tamaño{
    private $id;
    private $tipo;
    
    
    public function getId(){
        return $this -> id;
    }
    
    public function getTipo(){
        return $this -> tipo;
    }
    public function __construct($id = 0, $tipo = ""){
        $this -> id = $id;
        $this -> tipo = $tipo;
    }
    public function consultar() {
        $conexion = new Conexion();
        $tamañoDAO = new TamañoDAO($this->id, $this->tipo);
        $conexion->abrir();
        $conexion->ejecutar($tamañoDAO->consultar());
        if (($fila = $conexion->registro()) != null) {
            $this->tipo = $fila[0];
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