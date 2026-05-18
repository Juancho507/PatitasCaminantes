<?php
class DisponibilidadDAO {
    private $id;
    private $horaInicio;
    private $horaFin;
    private $idPaseador;
    private $idDiaSemana;

    public function __construct($id = 0, $horaInicio = "", $horaFin = "", $idPaseador = 0, $idDiaSemana = 0) {
        $this->id = $id;
        $this->horaInicio = $horaInicio;
        $this->horaFin = $horaFin;
        $this->idPaseador = $idPaseador;
        $this->idDiaSemana = $idDiaSemana;
    }

    public function consultarPorPaseador() {
        return "SELECT d.idDisponibilidad, d.HoraInicio, d.HoraFin, d.DiaSemana_idDiaSemana, ds.Dia
                FROM disponibilidad d
                INNER JOIN diasemana ds ON d.DiaSemana_idDiaSemana = ds.idDiaSemana
                WHERE d.paseador_idPaseador = " . $this->idPaseador . "
                ORDER BY ds.idDiaSemana ASC, d.HoraInicio ASC";
    }

    public function insertar() {
        return "INSERT INTO disponibilidad (HoraInicio, HoraFin, paseador_idPaseador, DiaSemana_idDiaSemana)
                VALUES ('" . $this->horaInicio . "', '" . $this->horaFin . "', " . $this->idPaseador . ", " . $this->idDiaSemana . ")";
    }

    public function eliminar() {
        return "DELETE FROM disponibilidad WHERE idDisponibilidad = " . $this->id;
    }

    public function eliminarPorPaseador() {
        return "DELETE FROM disponibilidad WHERE paseador_idPaseador = " . $this->idPaseador;
    }

    public function consultarDisponibilidadPorDiaYHora($idPaseador, $diaSemana, $hora) {
        return "SELECT idDisponibilidad FROM disponibilidad
                WHERE paseador_idPaseador = $idPaseador
                AND DiaSemana_idDiaSemana = $diaSemana
                AND HoraInicio <= '$hora'
                AND HoraFin > '$hora'";
    }
}
?>
