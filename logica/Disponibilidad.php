<?php
require_once(__DIR__ . "/../persistencia/Conexion.php");
require_once(__DIR__ . "/../persistencia/DisponibilidadDAO.php");

class Disponibilidad {
    private $id;
    private $horaInicio;
    private $horaFin;
    private $idPaseador;
    private $idDiaSemana;
    private $nombreDia;

    public function getId() { return $this->id; }
    public function getHoraInicio() { return $this->horaInicio; }
    public function getHoraFin() { return $this->horaFin; }
    public function getIdPaseador() { return $this->idPaseador; }
    public function getIdDiaSemana() { return $this->idDiaSemana; }
    public function getNombreDia() { return $this->nombreDia; }

    public function __construct($id = 0, $horaInicio = "", $horaFin = "", $idPaseador = 0, $idDiaSemana = 0, $nombreDia = "") {
        $this->id = $id;
        $this->horaInicio = $horaInicio;
        $this->horaFin = $horaFin;
        $this->idPaseador = $idPaseador;
        $this->idDiaSemana = $idDiaSemana;
        $this->nombreDia = $nombreDia;
    }

    public function consultarPorPaseador($idPaseador) {
        $conexion = new Conexion();
        $dao = new DisponibilidadDAO(0, "", "", $idPaseador);
        $conexion->abrir();
        $conexion->ejecutar($dao->consultarPorPaseador());
        $resultados = [];
        while ($fila = $conexion->registro()) {
            $resultados[] = new Disponibilidad($fila[0], $fila[1], $fila[2], $idPaseador, $fila[3], $fila[4]);
        }
        $conexion->cerrar();
        return $resultados;
    }

    public function insertar() {
        $conexion = new Conexion();
        $dao = new DisponibilidadDAO(0, $this->horaInicio, $this->horaFin, $this->idPaseador, $this->idDiaSemana);
        $conexion->abrir();
        $conexion->ejecutar($dao->insertar());
        $conexion->cerrar();
    }

    public function eliminar() {
        $conexion = new Conexion();
        $dao = new DisponibilidadDAO($this->id);
        $conexion->abrir();
        $conexion->ejecutar($dao->eliminar());
        $conexion->cerrar();
    }

    public function eliminarPorPaseador($idPaseador) {
        $conexion = new Conexion();
        $dao = new DisponibilidadDAO(0, "", "", $idPaseador);
        $conexion->abrir();
        $conexion->ejecutar($dao->eliminarPorPaseador());
        $conexion->cerrar();
    }
}
?>
