<?php 
require_once("persistencia/Conexion.php");
require_once("persistencia/PerroDAO.php");

class Perro{
    private $id;
    private $nombre;
    private $foto;
    private $raza;
    private $dueño;
    private $tamaño;
    
    
    public function getId(){
        return $this -> id;
    }
    
    public function getNombre(){
        return $this -> nombre;
    }
    public function getFoto(){
        return $this -> foto;
    }
    
    public function getRaza(){
        return $this -> raza;
    }
    public function getDueño(){
        return $this -> dueño;
    }
    public function getTamaño(){ 
        return $this -> tamaño;
    }
    public function __construct($id = 0, $nombre = "", $foto = "", $raza = "", $dueño = "",  $tamaño = ""){
        $this -> id = $id;
        $this -> nombre = $nombre;
        $this -> foto = $foto;
        $this -> raza = $raza;
        $this -> dueño = $dueño;
        $this -> tamaño = $tamaño; 
    }
    public function consultar($rol, $idUsuarioSesion) {
        $listaPerros = [];
        $perroDAO = new PerroDAO();
        
        if (strtolower($rol) === "dueño") {
            $idDueñoSanitizado = (int) $idUsuarioSesion;
            $sentencia = $perroDAO->consultarPerrosPorDueño($idDueñoSanitizado);
        } else { 
            $sentencia = $perroDAO->consultarTodosLosPerros();
        }
        
        $conexion = new Conexion();
        $conexion->abrir();
        $conexion->ejecutar($sentencia);
        
        if ($conexion->filas() > 0) {
            while ($registro = $conexion->registro()) {  $idPerro = $registro[0];
            
                $nombrePerro = $registro[1];
                $fotoPerro = $registro[2];
                $nombreRaza = $registro[3];
                $nombreDueñoCompleto = $registro[4];
                $nombreTamaño = $registro[5]; 
                $listaPerros[] = new Perro($idPerro, $nombrePerro, $fotoPerro, $nombreRaza, $nombreDueñoCompleto, $nombreTamaño);
            }
        }
        $conexion->cerrar();
        return $listaPerros;
    }
    
    public function consultarPerroPorId($idPerro) {
        $idPerroSanitizado = (int)$idPerro; 
        $perroDAO = new PerroDAO($idPerroSanitizado);
        $sentencia = $perroDAO->consultarPorId();
        
        $conexion = new Conexion();
        $conexion->abrir();
        $conexion->ejecutar($sentencia);
        
        $perroEncontrado = null;
        if ($conexion->filas() > 0) {
            $registro = $conexion->registro();
            $idPerro = $registro[0];
            $nombrePerro = $registro[1];
            $fotoPerro = $registro[2];
            $nombreRaza = $registro[3];
            $nombreDueñoCompleto = $registro[4];
            $nombreTamaño = $registro[5];
            
            $perroEncontrado = new Perro($idPerro, $nombrePerro, $fotoPerro, $nombreRaza, $nombreDueñoCompleto, $nombreTamaño);
        }
        $conexion->cerrar();
        return $perroEncontrado;
    }
    
    public function insertar (){
        $conexion = new Conexion();
        $conexion->abrir();
        $perroDAO = new PerroDAO(
            nombre: $this -> nombre,
            foto: $this -> foto,
            raza: $this -> raza -> getId(), 
            dueño: $this -> dueño -> getId() 
            );
        $conexion -> ejecutar($perroDAO -> insertar());
        $conexion->cerrar();
        return $conexion -> getResultado();
    }
    public function eliminar() {
        $perroDAO = new PerroDAO($this->id);
        $conexion = new Conexion();
        $conexion->abrir();
        $conexion->ejecutar($perroDAO->eliminar());
        $conexion->cerrar();
    }
    public function actualizar() {
        $conexion = new Conexion();
        $conexion->abrir();
        $perroDAO = new PerroDAO($this->id, $this->nombre, $this->foto, $this->raza, $this->dueño);
        $conexion->ejecutar($perroDAO->actualizar());
        $conexion->cerrar();
    }
    public function consultarPorDueño($idDueño) {
        $listaPerros = [];
        $perroDAO = new PerroDAO();
        $sentencia = $perroDAO->consultarPerrosPorDueño((int)$idDueño);
        
        $conexion = new Conexion();
        $conexion->abrir();
        $conexion->ejecutar($sentencia);
        
        if ($conexion->filas() > 0) {
            while ($registro = $conexion->registro()) {
                $idPerro = $registro[0];
                $nombrePerro = $registro[1];
                $fotoPerro = $registro[2];
                $raza = $registro[3];
                $dueño = $registro[4];
                $tamaño = $registro[5];
                
                $listaPerros[] = new Perro($idPerro, $nombrePerro, $fotoPerro, $raza, $dueño, $tamaño);
            }
        }
        
        $conexion->cerrar();
        return $listaPerros;
    }
    public function eliminarPaseos() {
        $conexion = new Conexion();
        $perroDAO = new perroDAO();
        $conexion->abrir();
        $conexion->ejecutar($perroDAO->eliminarPaseosPorPerro($this->id));
        $conexion->cerrar();
    }
    
    
    }

?>
