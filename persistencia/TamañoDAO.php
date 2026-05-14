<?php

class TamañoDAO {
    private $id;
    private $tipo;
    
    public function __construct($id = "", $tipo = "") {
        $this->id = $id;
        $this->tipo = $tipo;
    }
    public function consultar() {
        return "SELECT Tipo FROM Tamaño WHERE idTamaño = " . $this->id;
    }
    public function consultarTodos() {
        return "SELECT idTamaño, Tipo FROM Tamaño";
    }
}
?>