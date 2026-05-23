<?php
require_once(__DIR__ . "/Persona.php");
require_once(__DIR__ . "/../persistencia/DueñoDAO.php");
require_once(__DIR__ . "/../persistencia/Conexion.php");

class Dueño extends Persona{
    private $contacto;
    private $foto;
    private $nroDocumento;
    private $direccion;
    private $localidadId;
    private $localidadNombre;
    private $ciudadNombre;
    
    public function getContacto(){
        return $this -> contacto;
    }
    
    public function getFoto(){
        return $this -> foto;
    }
    public function getNroDocumento(){
        return $this->nroDocumento;
    }
    public function getDireccion(){
        return $this->direccion;
    }
    public function getLocalidadId(){
        return $this->localidadId;
    }
    public function getLocalidadNombre(){
        return $this->localidadNombre;
    }
    public function getCiudadNombre(){
        return $this->ciudadNombre;
    }
    public function setLocalidadId($v) { $this->localidadId = $v; }
    public function setDireccion($v) { $this->direccion = $v; }
    public function __construct($id = "", $nombre = "", $apellido = "", $correo = "", $clave = "", $contacto = "", $foto = "", $nroDocumento = "", $direccion = "", $localidad = 0) {
        parent::__construct($id, $nombre, $apellido, $correo, $clave);
        $this->contacto = $contacto;
        $this->foto = $foto;
        $this->nroDocumento = $nroDocumento;
        $this->direccion = $direccion;
        $this->localidadId = $localidad;
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
            foto: $this -> foto,
            nroDocumento: $this -> nroDocumento,
            direccion: $this -> direccion,
            localidad: $this->localidad,
            adminId: 1
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
    
    public function actualizar($localidadId = 0, $direccion = "") {
        $conexion = new Conexion();
        $conexion->abrir();
        $lid = $localidadId > 0 ? $localidadId : $this->localidadId;
        $dir = $direccion !== "" ? $direccion : $this->direccion;
        $dueñoDAO = new DueñoDAO($this->id, $this->nombre, $this->apellido, $this->correo, $this->clave, $this->contacto, $this->foto, $this->nroDocumento, $dir, $lid);
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
            $this->nroDocumento = $datos[6] ?? "";
            $this->direccion = $datos[7] ?? "";
            $this->localidadId = $datos[8] ?? 0;
            $this->localidadNombre = $datos[9] ?? "";
            $this->ciudadNombre = $datos[10] ?? "";
        }
        $conexion->cerrar();
    } 
}