<?php
require_once(__DIR__ . "/../persistencia/TarifaDAO.php");
require_once(__DIR__ . "/../persistencia/Conexion.php");

class Tarifa {
    private $id;
    private $precioHora;
    private $paseadorIdPaseador;
    private $peligrosidadIdPeligrosidad; 
    private $nombrePeligrosidad; 
    private $fechaInicio;
    private $activa;
    
    public function __construct($id = "", $precioHora = "", $paseadorIdPaseador = "", $peligrosidadIdPeligrosidad = "", $nombrePeligrosidad = "", $fechaInicio = "", $activa = "") {
        $this->id = $id;
        $this->precioHora = $precioHora;
        $this->paseadorIdPaseador = $paseadorIdPaseador;
        $this->peligrosidadIdPeligrosidad = $peligrosidadIdPeligrosidad;
        $this->nombrePeligrosidad = $nombrePeligrosidad;
        $this->fechaInicio = $fechaInicio;
        $this->activa = $activa;
        
    }
    
    public function getId() {
        return $this->id;
    }
    
    public function getPrecioHora() {
        return $this->precioHora;
    }
    
    public function getPaseadorIdPaseador() {
        return $this->paseadorIdPaseador;
    }
    
    public function getPeligrosidadIdPeligrosidad() {
        return $this->peligrosidadIdPeligrosidad;
    }
    public function getNombrePeligrosidad() { 
        return $this->nombrePeligrosidad;
    }
    public function getFechaInicio() {
        return $this->fechaInicio;
    }
    public function getActiva() {
        return $this->activa;
    }
    public function desactivarAnterior() {
        $conexion = new Conexion();
        $tarifaDAO = new TarifaDAO("", "", $this->paseadorIdPaseador, $this->peligrosidadIdPeligrosidad);
        $conexion->abrir();
        $conexion->ejecutar($tarifaDAO->desactivarAnterior());
        $conexion->cerrar();
    }
    
    public function insertarNueva() {
        $conexion = new Conexion();
        $tarifaDAO = new TarifaDAO("", $this->precioHora, $this->paseadorIdPaseador, $this->peligrosidadIdPeligrosidad);
        $conexion->abrir();
        $conexion->ejecutar($tarifaDAO->insertarNueva());
        $conexion->cerrar();
    }
    
}
?>