<?php
require_once(__DIR__ . "/Persona.php");
require_once(__DIR__ . "/../persistencia/PaseadorDAO.php");
require_once(__DIR__ . "/../persistencia/Conexion.php");
require_once(__DIR__ . "/../persistencia/TarifaDAO.php");
require_once(__DIR__ . "/Tarifa.php");

class Paseador extends Persona{
    private $contacto;
    private $foto;
    private $informacion;
    private $tarifas = [];
    private $estadoId;
    private $nroDocumento;
    private $fechaNacimiento;
    private $localidadId;
    private $localidadNombre;
    private $ciudadNombre;
    private $certificados = "";
    private $hojaDeVida = "";
    private $aprobadoPeligroso = 0;
    
    public function getContacto(){
        return $this -> contacto;
    }
    
    public function getFoto(){
        return $this -> foto;
    }
    public function getInformacion() {
        return $this->informacion;
    }
    public function getTarifas() {
        return $this->tarifas;
    }
    public function getEstadoId() {
        return $this->estadoId;
    }
    public function getNroDocumento() {
        return $this->nroDocumento;
    }
    public function getFechaNacimiento() {
        return $this->fechaNacimiento;
    }
    public function getLocalidadId() {
        return $this->localidadId;
    }
    public function getLocalidadNombre() {
        return $this->localidadNombre;
    }
    public function getCiudadNombre() {
        return $this->ciudadNombre;
    }
    public function getCertificados() {
        return $this->certificados;
    }
    public function getAprobadoPeligroso() { return $this->aprobadoPeligroso; }
    public function setAprobadoPeligroso($v) { $this->aprobadoPeligroso = $v; }
    public function setLocalidadId($v) { $this->localidadId = $v; }
    public function setCertificados($v) { $this->certificados = $v; }
    public function __construct($id = "", $nombre = "", $apellido = "", $correo = "", $clave = "", $contacto = "", $foto = "", $informacion = "", $estadoId = 1) {
        parent::__construct($id, $nombre, $apellido, $correo, $clave, $contacto, $foto);
        $this->contacto = $contacto;
        $this->foto = $foto;
        $this->informacion = $informacion;
        $this->estadoId = $estadoId;
    }
    public function cerrar_sesion() {
        session_destroy();
    }
    public function autenticarse() {
    $conexion = new Conexion();
    $paseadorDAO = new PaseadorDAO("", "", "", $this->correo, $this->clave);
    $conexion->abrir();
    $conexion->ejecutar($paseadorDAO->autenticarse());

    if ($conexion->filas() == 1) {
        $this->id = $conexion->registro()[0];

        $conexion->ejecutar((new PaseadorDAO($this->id))->consultar());
        $datos = $conexion->registro();
        $this->estadoId = $datos[7];

        if ($this->estadoId != 2) {
            $conexion->cerrar();
            return false;
        }

        $conexion->cerrar();
        return true;
    }

    $conexion->cerrar();
    return false;
}
public function consultarActivos($localidadId = 0, $soloPeligroso = false) {
    $conexion = new Conexion();
    $conexion->abrir();
    $paseadorDAO = new PaseadorDAO();
    $conexion->ejecutar($paseadorDAO->consultarActivos($localidadId, $soloPeligroso));

    $paseadores = [];
    while ($registro = $conexion->registro()) {
        $temp = new Paseador(
            $registro[0], $registro[1], $registro[2], $registro[3],
            "", $registro[4], $registro[5], $registro[6], $registro[7]
        );
        $temp->setAprobadoPeligroso($registro[8] ?? 0);
        $temp->consultarTarifas();
        $paseadores[] = $temp;
    }

    $conexion->cerrar();
    return $paseadores;
}

    public function registrar($activoValor = 1) {
        $conexion = new Conexion();
        $conexion->abrir();
        $claveMd5 = md5($this->clave);
        $paseadorDAO = new PaseadorDAO(
            "", $this->nombre, $this->apellido, $this->correo, $claveMd5,
            $this->contacto, $this->foto, $this->informacion, $activoValor,
            $this->nroDocumento, $this->fechaNacimiento
        );
        $conexion->ejecutar($paseadorDAO->registrar());
        $conexion->cerrar();
        return $conexion->getResultado();
    }

    public function actualizar() {
        $conexion = new Conexion();
        $conexion->abrir();
        $paseadorDAO = new PaseadorDAO(
            $this->id,
            $this->nombre,
            $this->apellido,
            $this->correo,
            $this->clave,
            $this->contacto,
            $this->foto,
            $this->informacion,
            1,
            "",
            "",
            $this->localidadId,
            "",
            $this->certificados,
            $this->aprobadoPeligroso
            );$conexion->ejecutar($paseadorDAO->actualizar());
        $conexion->cerrar();
    }

    public function actualizarAprobacionPeligroso() {
        $conexion = new Conexion();
        $conexion->abrir();
        $paseadorDAO = new PaseadorDAO($this->id, "", "", "", "", "", "", "", 1, "", "", 0, "", "", $this->aprobadoPeligroso);
        $conexion->ejecutar($paseadorDAO->actualizarAprobacionPeligroso());
        $conexion->cerrar();
    }
    public function actualizarEstado() {
    $conexion = new Conexion();
    $conexion->abrir();
    $paseadorDAO = new PaseadorDAO($this->id, "", "", "", "", "", "", "", $this->estadoId);
    $conexion->ejecutar($paseadorDAO->actualizarEstado());
    $conexion->cerrar();
}

    public function consultar(){
        $conexion = new Conexion();
        $paseadorDAO = new PaseadorDAO($this->id);
        $conexion->abrir();
        $conexion->ejecutar($paseadorDAO->consultar());
        
        if ($conexion->filas() > 0) {
            $datos = $conexion->registro();
            if ($datos !== null) {
                $this->nombre = $datos[0];
                $this->apellido = $datos[1];
                $this->correo = $datos[2];
                $this->clave = $datos[3]; 
                $this->contacto = $datos[4];
                $this->foto = $datos[5];
                $this->informacion = $datos[6];
                $this->estadoId = $datos[7];
                $this->nroDocumento = $datos[8] ?? "";
                $this->fechaNacimiento = $datos[9] ?? "";
                $this->localidadId = $datos[10] ?? 0;
                $this->localidadNombre = $datos[11] ?? "";
                $this->ciudadNombre = $datos[12] ?? "";
                $this->hojaDeVida = $datos[13] ?? "";
                $this->certificados = $datos[14] ?? "";
                $this->aprobadoPeligroso = $datos[15] ?? 0;
                $this->consultarTarifas();
            }
        } else {
            $this->nombre = "";
            $this->apellido = "";
            $this->correo = "";
            $this->clave = "";
            $this->contacto = "";
            $this->foto = "";
            $this->informacion = "";
            $this->estadoId = "";
            $this->nroDocumento = "";
            $this->fechaNacimiento = "";
            $this->localidadId = 0;
            $this->localidadNombre = "";
            $this->ciudadNombre = "";
            $this->hojaDeVida = "";
            $this->certificados = "";
            $this->aprobadoPeligroso = 0;
            $this->tarifas = [];
        }
        
        $conexion->cerrar();
    }
     public function consultarTodos() {
    $conexion = new Conexion();
    $paseadorDAO = new PaseadorDAO();

    $conexion->abrir();
    $conexion->ejecutar($paseadorDAO->consultarTodos());

    $resultado = [];
    while (($registro = $conexion->registro())) {
        $resultado[] = new Paseador(
            $registro[0],
            $registro[1],
            $registro[2],
            $registro[3],
            "",
            $registro[4],
            "",
            "",
            $registro[5]
        );
    }

    $conexion->cerrar();
    return $resultado;
}

    public function consultarTarifas() {
        
        $conexion = new Conexion();
        $tarifaDAO = new TarifaDAO();
        $conexion->abrir();
        $conexion->ejecutar($tarifaDAO->consultarPorPaseador($this->id));
        
        $this->tarifas = []; 
        while ($registroTarifa = $conexion->registro()) {
            if ($registroTarifa !== null) {
                $this->tarifas[] = new Tarifa(
                    $registroTarifa[0], 
                    $registroTarifa[1], 
                    $registroTarifa[2], 
                    $registroTarifa[3], 
                    $registroTarifa[4], 
                    $registroTarifa[5],
                    $registroTarifa[6]
                    );
            }
        }
        $conexion->cerrar();
    }
    public function correoExiste() {
    $conexion = new Conexion();
    $paseadorDAO = new PaseadorDAO("", "", "", $this->correo);
    $conexion->abrir();
    $conexion->ejecutar($paseadorDAO->correoExiste());
    $existe = $conexion->filas() > 0;
    $conexion->cerrar();
    return $existe;
}

    
}