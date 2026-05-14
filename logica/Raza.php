<?php
require_once(__DIR__ . "/../persistencia/Conexion.php");
require_once(__DIR__ . "/../persistencia/RazaDAO.php");

class Raza{
    private $id;
    private $nombre;
    private $tamaño;
    
    public function getId(){
        return $this -> id;
    }
    
    public function getNombre(){
        return $this -> nombre;
    }
    public function getTamaño(){
        return $this -> tamaño;
    }
    public function __construct($id = 0, $nombre = "", $tamaño = ""){
        $this -> id = $id;
        $this -> nombre = $nombre;
        $this -> tamaño = $tamaño;
    }
    public function consultarTodos() {
        $razaDAO = new RazaDAO();
        $sentencia = $razaDAO->consultarTodasLasRazas();
        
        $conexion = new Conexion();
        $conexion->abrir();
        $conexion->ejecutar($sentencia);
        
        $listaRazas = [];
        if ($conexion->filas() > 0) {
            while ($registro = $conexion->registro()) {
                $idRaza = $registro[0];
                $nombreRaza = $registro[1];
                $tamañoId = $registro[2];
                $listaRazas[] = new Raza($idRaza, $nombreRaza, $tamañoId);
                
            }
        }
        $conexion->cerrar();
        return $listaRazas;
    }
    public function insertarSinTamaño() {
        $razaDAO = new RazaDAO(0, $this->nombre, null);
        $sentencia = $razaDAO->insertarRazaSinTamaño();
        
        $conexion = new Conexion();
        $conexion->abrir();
        $conexion->ejecutar($sentencia);
        $exito = $conexion->afectadas() > 0;
        $conexion->cerrar();
        return $exito;
    }
    public function consultarRazasSinTamaño() {
        $razaDAO = new RazaDAO();
        $sentencia = $razaDAO->consultarRazasSinTamaño();
        
        $conexion = new Conexion();
        $conexion->abrir();
        $conexion->ejecutar($sentencia);
        
        $razas = [];
        while ($registro = $conexion->registro()) {
            $razas[] = new Raza($registro[0], $registro[1]);
        }
        
        $conexion->cerrar();
        return $razas;
    }
    public function asignarTamaño($idTamaño) {
        $razaDAO = new RazaDAO($this->id);
        $sentencia = $razaDAO->asignarTamaño($idTamaño);
        
        $conexion = new Conexion();
        $conexion->abrir();
        $conexion->ejecutar($sentencia);
        $conexion->cerrar();
    }
    public function consultarTodosConTamaño() {
        $razaDAO = new RazaDAO();
        $sentencia = $razaDAO->consultarRazasConTamaño();
        
        $conexion = new Conexion();
        $conexion->abrir();
        $conexion->ejecutar($sentencia);
        
        $listaRazas = [];
        while ($registro = $conexion->registro()) {
            $listaRazas[] = new Raza($registro[0], $registro[1], $registro[2]); 
        }
        
        $conexion->cerrar();
        return $listaRazas;
    }
    
}
?>
