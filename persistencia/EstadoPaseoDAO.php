<?php
class EstadoPaseoDAO {
    private $id;
    private $nombre; 
    
    public function __construct($id = 0, $nombre = ""){
        $this->id = $id;
        $this->nombre = $nombre;
    }
    
    public function consultarTodosLosEstados() {
        return "SELECT idEstado, Nombre FROM estado";
    }
    public function consultarPorId() {
        return "SELECT idEstado, Nombre FROM estado WHERE idEstado = " . $this->id;
    }
}
?>