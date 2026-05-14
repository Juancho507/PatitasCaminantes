<?php

class TarifaDAO {
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
    public function consultarPorPaseador($idPaseador) {
        return "SELECT
            t.idTarifa,
            t.PrecioHora,
            t.Paseador_idPaseador,
            t.Tamaño_idTamaño,
            tam.Tipo,
            t.FechaInicio,
            t.Activa
        FROM Tarifa t
        INNER JOIN Tamaño tam ON t.Tamaño_idTamaño = tam.idTamaño
        WHERE t.Paseador_idPaseador = '" . $idPaseador . "'
          AND t.Activa = 1
        ORDER BY tam.Tipo ASC";
    }
    
    public function desactivarAnterior() {
        return "UPDATE Tarifa
            SET Activa = 0
            WHERE Paseador_idPaseador = $this->paseadorIdPaseador
              AND Tamaño_idTamaño = $this->tamañoIdTamaño
              AND Activa = 1";
    }
    
    public function insertarNueva() {
        return "INSERT INTO Tarifa (PrecioHora, FechaInicio, Paseador_idPaseador, Tamaño_idTamaño, Activa)
            VALUES ($this->precioHora, CURDATE(), $this->paseadorIdPaseador, $this->tamañoIdTamaño, 1)";
    }
    
}
?>