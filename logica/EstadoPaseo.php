<?php
require_once(__DIR__ . "/../persistencia/Conexion.php");
require_once(__DIR__ . "/../persistencia/EstadoPaseoDAO.php");

class EstadoPaseo {
    private $id;
    private $estado;
    
    public function getId(){
        return $this -> id;
    }
    
    public function getEstado(){
        return $this -> estado;
    }
    
    public function __construct($id = 0, $estado = ""){
        $this -> id = $id;
        $this -> estado = $estado;
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

    public function consultar() {
        $estadoPaseoDAO = new EstadoPaseoDAO($this->id);
        $sentencia = $estadoPaseoDAO->consultarPorId();
        $conexion = new Conexion();
        $conexion->abrir();
        $conexion->ejecutar($sentencia);
        if ($conexion->filas() > 0) {
            $registro = $conexion->registro();
            $this->id = $registro[0];
            $this->estado = $registro[1];
        }
        $conexion->cerrar();
    }
}
?>