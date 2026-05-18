<?php
class EstadoDAO {
    private $id;
    private $nombre;

    public function __construct($id = 0, $nombre = "") {
        $this->id = $id;
        $this->nombre = $nombre;
    }

    public function consultarTodos() {
        return "SELECT idEstado, Nombre FROM estado ORDER BY idEstado ASC";
    }

    public function consultar() {
        return "SELECT Nombre FROM estado WHERE idEstado = " . $this->id;
    }
}
?>
