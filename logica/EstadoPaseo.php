<?php
require_once(__DIR__ . "/../persistencia/Conexion.php");
require_once(__DIR__ . "/../persistencia/EstadoPaseoDAO.php");

class EstadoPaseo {
    private $id;
    private $valor;
    
    public function getId(){
        return $this -> id;
    }
    
    public function getValor(){
        return $this -> valor;
    }
    
    public function __construct($id = 0, $valor = ""){
        $this -> id = $id;
        $this -> valor = $valor;
    }
    
    public function consultarTodos() {
        $estadoPaseoDAO = new EstadoPaseoDAO();
        $sentencia = $estadoPaseoDAO->consultarTodosLosEstados();
        $conexion = new Conexion();
        $conexion->abrir();
        $conexion->ejecutar($sentencia);
        $listaEstados = [];
        if ($conexion->filas() > 0) {
            while ($registro = $conexion->registro()) {
                $idEstado = $registro[0];
                $valorEstado = $registro[1];
                $listaEstados[] = new EstadoPaseo($idEstado, $valorEstado);
            }
        }
        $conexion->cerrar();
        return $listaEstados;
    }
}
?>