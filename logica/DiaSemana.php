<?php
require_once(__DIR__ . "/../persistencia/Conexion.php");
require_once(__DIR__ . "/../persistencia/DiaSemanaDAO.php");

class DiaSemana {
    private $id;
    private $nombre;

    public function getId() { return $this->id; }
    public function getNombre() { return $this->nombre; }

    public function __construct($id = 0, $nombre = "") {
        $this->id = $id;
        $this->nombre = $nombre;
    }

    public function consultarTodos() {
        $conexion = new Conexion();
        $dao = new DiaSemanaDAO();
        $conexion->abrir();
        $conexion->ejecutar($dao->consultarTodos());
        $resultados = [];
        while ($fila = $conexion->registro()) {
            $resultados[] = new DiaSemana($fila[0], $fila[1]);
        }
        $conexion->cerrar();
        return $resultados;
    }

    public function consultar() {
        $conexion = new Conexion();
        $dao = new DiaSemanaDAO($this->id);
        $conexion->abrir();
        $conexion->ejecutar($dao->consultar());
        if ($fila = $conexion->registro()) {
            $this->nombre = $fila[0];
        }
        $conexion->cerrar();
    }
}
?>
