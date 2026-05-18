<?php
require_once(__DIR__ . "/../persistencia/Conexion.php");
require_once(__DIR__ . "/../persistencia/LocalidadDAO.php");

class Localidad {
    private $id;
    private $nombre;
    private $idCiudad;
    private $nombreCiudad;

    public function getId() { return $this->id; }
    public function getNombre() { return $this->nombre; }
    public function getIdCiudad() { return $this->idCiudad; }
    public function getNombreCiudad() { return $this->nombreCiudad; }

    public function __construct($id = 0, $nombre = "", $idCiudad = 0, $nombreCiudad = "") {
        $this->id = $id;
        $this->nombre = $nombre;
        $this->idCiudad = $idCiudad;
        $this->nombreCiudad = $nombreCiudad;
    }

    public function consultarPorCiudad($idCiudad) {
        $conexion = new Conexion();
        $dao = new LocalidadDAO(0, "", $idCiudad);
        $conexion->abrir();
        $conexion->ejecutar($dao->consultarPorCiudad());
        $resultados = [];
        while ($fila = $conexion->registro()) {
            $resultados[] = new Localidad($fila[0], $fila[1], $idCiudad);
        }
        $conexion->cerrar();
        return $resultados;
    }

    public function consultarTodos() {
        $conexion = new Conexion();
        $dao = new LocalidadDAO();
        $conexion->abrir();
        $conexion->ejecutar($dao->consultarTodos());
        $resultados = [];
        while ($fila = $conexion->registro()) {
            $resultados[] = new Localidad($fila[0], $fila[1], 0, $fila[2]);
        }
        $conexion->cerrar();
        return $resultados;
    }

    public function consultar() {
        $conexion = new Conexion();
        $dao = new LocalidadDAO($this->id);
        $conexion->abrir();
        $conexion->ejecutar($dao->consultar());
        if ($fila = $conexion->registro()) {
            $this->nombre = $fila[0];
        }
        $conexion->cerrar();
    }
}
?>
