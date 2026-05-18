<?php
class DiaSemanaDAO {
    private $id;
    private $nombre;

    public function __construct($id = 0, $nombre = "") {
        $this->id = $id;
        $this->nombre = $nombre;
    }

    public function consultarTodos() {
        return "SELECT idDiaSemana, Dia FROM diasemana ORDER BY idDiaSemana ASC";
    }

    public function consultar() {
        return "SELECT Dia FROM diasemana WHERE idDiaSemana = " . $this->id;
    }
}
?>
