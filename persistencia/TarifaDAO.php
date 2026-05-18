<?php

class TarifaDAO {
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
    public function consultarPorPaseador($idPaseador) {
        return "SELECT
            t.idTarifa,
            t.PrecioHora,
            t.Paseador_idPaseador,
            t.Peligrosidad_idPeligrosidad,
            p.Nivel,
            t.FechaInicio,
            t.Activa
        FROM Tarifa t
        INNER JOIN Peligrosidad p ON t.Peligrosidad_idPeligrosidad = p.idPeligrosidad
        WHERE t.Paseador_idPaseador = '" . $idPaseador . "'
          AND t.Activa = 1
        ORDER BY p.idPeligrosidad ASC";
    }
    
    public function desactivarAnterior() {
        return "UPDATE Tarifa
            SET Activa = 0
            WHERE Paseador_idPaseador = $this->paseadorIdPaseador
              AND Peligrosidad_idPeligrosidad = $this->peligrosidadIdPeligrosidad
              AND Activa = 1";
    }
    
    public function insertarNueva() {
        return "INSERT INTO Tarifa (PrecioHora, FechaInicio, Paseador_idPaseador, Peligrosidad_idPeligrosidad, Activa)
            VALUES ($this->precioHora, CURDATE(), $this->paseadorIdPaseador, $this->peligrosidadIdPeligrosidad, 1)";
    }
    
}
?>