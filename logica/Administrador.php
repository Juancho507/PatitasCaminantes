<?php
require_once ("logica/Persona.php");
require_once ("persistencia/AdministradorDAO.php");
require_once ("persistencia/Conexion.php");

class Administrador extends Persona{
    public function __construct($id = "", $nombre = "", $apellido = "", $correo = "", $clave = "" ) {
        parent::__construct($id, $nombre, $apellido, $correo, $clave);
    }
    
    public function autenticarse() {
        $conexion = new Conexion();
        $administradorDAO = new AdministradorDAO("","","", $this -> correo, $this -> clave);
        $conexion -> abrir();
        $conexion -> ejecutar($administradorDAO -> autenticarse());
        if($conexion -> filas() == 1){            
            $this -> id = $conexion -> registro()[0];
            $conexion->cerrar();
            return true;
        }else{
            $conexion->cerrar();
            return false;
        }

    }
    public function consultar(){
        $conexion = new Conexion();
        $administradorDAO = new AdministradorDAO($this -> id);
        $conexion -> abrir();
        $conexion -> ejecutar($administradorDAO -> consultar());
        $datos = $conexion -> registro();
        $this -> nombre = $datos[0];
        $this -> apellido = $datos[1];
        $this -> correo = $datos[2];
        $conexion->cerrar();
    }

    public function actualizar() {
        $conexion = new Conexion();
        $conexion->abrir();
        $administradorDAO = new AdministradorDAO(
            $this->id,
            $this->nombre,
            $this->apellido,
            $this->correo,
            $this->clave,
            );$conexion->ejecutar($administradorDAO->actualizar());
        $conexion->cerrar();
    }
}