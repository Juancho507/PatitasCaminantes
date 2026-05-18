<?php
class CiudadDAO {
    private $id;
    private $nombre;

    public function __construct($id = 0, $nombre = "") {
        $this->id = $id;
        $this->nombre = $nombre;
    }

    public function consultarTodos() {
        return "SELECT idCiudad, Ciudad FROM ciudad ORDER BY Ciudad ASC";
    }

    public function consultar() {
        return "SELECT Ciudad FROM ciudad WHERE idCiudad = " . $this->id;
    }
}
?>
