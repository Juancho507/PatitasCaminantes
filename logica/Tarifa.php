<?php
require_once ("persistencia/TarifaDAO.php");
require_once ("persistencia/Conexion.php");

class Tarifa {
    private $id;
    private $precioHora;
    private $paseadorIdPaseador;
    private $tamañoIdTamaño; 
    private $nombreTamaño; 
    private $fechaInicio;
    private $activa;
    
    public function __construct($id = "", $precioHora = "", $paseadorIdPaseador = "", $tamañoIdTamaño = "", $nombreTamaño = "", $fechaInicio = "", $activa = "") {
        $this->id = $id;
        $this->precioHora = $precioHora;
        $this->paseadorIdPaseador = $paseadorIdPaseador;
        $this->tamañoIdTamaño = $tamañoIdTamaño;
        $this->nombreTamaño = $nombreTamaño;
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
    
    public function getTamañoIdTamaño() {
        return $this->tamañoIdTamaño;
    }
    public function getNombreTamaño() { 
        return $this->nombreTamaño;
    }
    public function getFechaInicio() {
        return $this->fechaInicio;
    }
    public function getActiva() {
        return $this->activa;
    }
    public function desactivarAnterior() {
        $conexion = new Conexion();
        $tarifaDAO = new TarifaDAO("", "", $this->paseadorIdPaseador, $this->tamañoIdTamaño);
        $conexion->abrir();
        $conexion->ejecutar($tarifaDAO->desactivarAnterior());
        $conexion->cerrar();
    }
    
    public function insertarNueva() {
        $conexion = new Conexion();
        $tarifaDAO = new TarifaDAO("", $this->precioHora, $this->paseadorIdPaseador, $this->tamañoIdTamaño);
        $conexion->abrir();
        $conexion->ejecutar($tarifaDAO->insertarNueva());
        $conexion->cerrar();
    }
    
}
?>