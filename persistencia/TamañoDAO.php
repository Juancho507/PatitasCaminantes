<?php

class TamañoDAO {
    private $id;
    private $tipo;
    
    public function __construct($id = "", $tipo = "") {
        $this->id = $id;
        $this->tipo = $tipo;
    }
    public function consultar() {
        return "SELECT Tamaño FROM Tamaño WHERE idTamaño = " . $this->id;
    }
    public function consultarTodos() {
        return "SELECT idTamaño, Tamaño FROM Tamaño";
    }
}
?>