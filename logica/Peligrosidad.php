<?php
require_once(__DIR__ . "/../persistencia/Conexion.php");
require_once(__DIR__ . "/../persistencia/PeligrosidadDAO.php");

class Peligrosidad {
    private $id;
    private $nivel;

    public function getId() {
        return $this->id;
    }

    public function getNivel() {
        return $this->nivel;
    }

    public function __construct($id = 0, $nivel = "") {
        $this->id = $id;
        $this->nivel = $nivel;
    }

    public function consultarTodos() {
        $conexion = new Conexion();
        $dao = new PeligrosidadDAO();
        $conexion->abrir();
        $conexion->ejecutar($dao->consultarTodos());
        $resultados = [];
        while ($fila = $conexion->registro()) {
            $resultados[] = new Peligrosidad($fila[0], $fila[1]);
        }
        $conexion->cerrar();
        return $resultados;
    }
}
?>