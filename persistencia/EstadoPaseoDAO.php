<?php
class EstadoPaseoDAO {
    private $id;
    private $valor; 
    
    public function __construct($id = 0, $valor = ""){
        $this -> id = $id;
        $this -> valor = $valor;
    }
    
    public function consultarTodosLosEstados() {
        return "SELECT idEstadoPaseo, valor FROM EstadoPaseo";
    }
}
?>