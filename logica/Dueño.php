<?php
require_once ("logica/Persona.php");
require_once ("persistencia/DueñoDAO.php");
require_once ("persistencia/Conexion.php");

class Dueño extends Persona{
    private $contacto;
    private $foto;
    
    public function getContacto(){
        return $this -> contacto;
    }
    
    public function getFoto(){
        return $this -> foto;
    }
    public function __construct($id = "", $nombre = "", $apellido = "", $correo = "", $clave = "", $contacto = "", $foto = "") {
        parent::__construct($id, $nombre, $apellido, $correo, $clave);
        $this->contacto = $contacto;
        $this->foto = $foto;
    }
    
    
    public function registrar (){
        $conexion = new Conexion();
        $conexion->abrir();
        $claveMd5 = md5($this->clave);
        $dueñoDAO = new DueñoDAO(
            nombre: $this -> nombre,
            apellido: $this -> apellido,
            correo: $this -> correo,
            clave: $claveMd5,
            contacto: $this -> contacto,
            foto: $this -> foto
            );
        $conexion -> ejecutar($dueñoDAO -> registrar());
        $conexion->cerrar();
        return $conexion -> getResultado();
    }

    public function correoExiste() {
        $conexion = new Conexion();
        $dueñoDAO = new DueñoDAO("", "", "", $this->correo);
        $conexion->abrir();
        $conexion->ejecutar($dueñoDAO->correoExiste());
        $existe = $conexion->filas() > 0;
        $conexion->cerrar();
        return $existe;
    }
    public function eliminar() {
        $conexion = new Conexion();
        $conexion->abrir();
        $dueñoDAO = new DueñoDAO($this->id);
        $conexion->ejecutar($dueñoDAO->eliminarPerros());
        $conexion->ejecutar($dueñoDAO->eliminar());
        $conexion->cerrar();
    }
    
    public function actualizar() {
        $conexion = new Conexion();
        $conexion->abrir();
        $dueñoDAO = new DueñoDAO($this->id, $this->nombre, $this->apellido, $this->correo, $this->clave, $this->contacto, $this->foto);
        $conexion->ejecutar($dueñoDAO->actualizar());
        $conexion->cerrar();
    }


    public function autenticarse() {
        $conexion = new Conexion();
        $dueñoDAO = new DueñoDAO("","","", $this -> correo, $this -> clave);
        $conexion -> abrir();
        $conexion -> ejecutar($dueñoDAO -> autenticarse());
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
        $dueñoDAO = new DueñoDAO($this -> id);
        $conexion -> abrir();
        $conexion -> ejecutar($dueñoDAO -> consultar());
        $datos = $conexion -> registro();
        if ($datos) {
            $this->nombre = $datos[0];
            $this->apellido = $datos[1];
            $this->correo = $datos[2];
            $this->clave = $datos[3];
            $this->contacto = $datos[4];
            $this->foto = $datos[5];
        }
        $conexion->cerrar();
    } 
}