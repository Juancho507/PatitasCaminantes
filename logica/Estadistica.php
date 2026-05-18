<?php
require_once(__DIR__ . "/../persistencia/EstadisticaDAO.php");
require_once(__DIR__ . "/../persistencia/Conexion.php");

class Estadistica {
    public function promedioPreciosPorPeligrosidad() {
        $conexion = new Conexion();
        $dao = new EstadisticaDAO();
        $conexion->abrir();
        $conexion->ejecutar($dao->promedioPreciosPorPeligrosidad());
        $datos = [];
        while ($fila = $conexion->registro()) {
            $datos[] = $fila;
        }
        $conexion->cerrar();
        return $datos;
    }

    public function cantidadPerrosPorTamaño() {
        $conexion = new Conexion();
        $dao = new EstadisticaDAO();
        $conexion->abrir();
        $conexion->ejecutar($dao->cantidadPerrosPorTamaño());
        $datos = [];
        while ($fila = $conexion->registro()) {
            $datos[] = $fila;
        }
        $conexion->cerrar();
        return $datos;
    }
}
