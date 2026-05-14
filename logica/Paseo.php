<?php
require_once(__DIR__ . "/../persistencia/Conexion.php");
require_once(__DIR__ . "/../persistencia/PaseoDAO.php");


class Paseo {
    private $id;
    private $fechaInicio;
    private $fechaFin;
    private $paseador;
    private $estadoPaseo;
    private $nombrePerro;
    private $idEstadoPaseo;
    private $idPerro;
    private $precio;
    private $dueño;

public function getDueño() {
    return $this->dueño;
}
public function setDueño($dueño) {
    $this->dueño = $dueño;
}

public function setPaseador($paseador) {
    $this->paseador = $paseador;
}
    public function getId(){
        return $this -> id;
    }
    public function getFechaInicio(){
        return $this -> fechaInicio;
    }
    public function getFechaFin(){
        return $this -> fechaFin;
    }
    public function getPaseador(){
        return $this -> paseador;
    }
    public function getEstadoPaseo(){
        return $this -> estadoPaseo;
    }
    public function getNombrePerro(){
        return $this->nombrePerro;
    }
  
    public function getIdPerro() {
        return $this->idPerro;
    }
    public function getPrecio() {
        return $this->precio;
    }
    public function setPrecio($precio) {
        $this->precio = $precio;
    }
    
    public function setIdPerro($idPerro) {
        $this->idPerro = $idPerro;
    }

    public function setFechaInicio($fecha) {
        $this->fechaInicio = $fecha;
    }
    
    public function setFechaFin($hora) {
        $this->fechaFin = $hora;
    }
    
    public function setNombrePerro($nombres) {
        $this->nombrePerro = $nombres;
    }
    public function setEstadoPaseo($estado) {
        $this->estadoPaseo = $estado;
    }
    
    public function __construct($id = 0, $fechaInicio = "", $fechaFin = "", $paseador = "", $estadoPaseo = "", $nombrePerro = "", $idPerro = 0) {
        $this->id = $id;
        $this->fechaInicio = $fechaInicio;
        $this->fechaFin = $fechaFin;
        $this->paseador = $paseador;
        $this->estadoPaseo = $estadoPaseo;
        $this->nombrePerro = $nombrePerro;
        $this->idPerro = $idPerro;
    }
    
    public function insertar($idPerro) {
        $conexion = new Conexion();
        $conexion->abrir();
        $fechaInicioDT = new DateTime($this->fechaInicio);
        $fechaFinDT = clone $fechaInicioDT;
        $fechaFinDT->modify('+1 hour');
        $this->fechaFin = $fechaFinDT->format('Y-m-d H:i:s');
        
        $paseoDAO = new PaseoDAO(0, $this->fechaInicio, $this->fechaFin, $this->paseador, $this->estadoPaseo);
        $conexion->ejecutar($paseoDAO->insertarPaseo());
        
        if ($conexion->getResultado()) {
            $idInsertado = $conexion->obtenerId(); 
            $this->id = $idInsertado; 
            $conexion->ejecutar($paseoDAO->insertarPaseoPerro($idInsertado, $idPerro));
            $conexion->cerrar();
            return true;
        }
        
        $conexion->cerrar();
        return false;
    }
    public function asociarPerro($idPerro) {
        $conexion = new Conexion();
        $conexion->abrir();
        $paseoDAO = new PaseoDAO();
        $conexion->ejecutar($paseoDAO->insertarPaseoPerro($this->id, $idPerro));
        $conexion->cerrar();
        return true;
    }
public function buscarHistorial($rol, $idUsuario, $palabras) {
    $conexion = new Conexion();
    $conexion->abrir();
    $dao = new PaseoDAO();

    if ($rol === "dueño") {
        $sql = $dao->buscarHistorialDueño($idUsuario, $palabras);
    } else {
        $sql = $dao->buscarHistorialPaseador($idUsuario, $palabras);
    }

    $conexion->ejecutar($sql);

    $lista = [];
    while ($registro = $conexion->registro()) {
        $paseo = new Paseo(
            $registro[0],
            $registro[1],
            $registro[2],
            $rol === "dueño" ? $registro[3] : "",
            $registro[4],
            $registro[5],
            $registro[6]
        );
        $paseo->setPrecio($registro[7]);

        if ($rol === "paseador") {
            $paseo->setDueño($registro[3]);
        }

        $lista[] = $paseo;
    }

    $conexion->cerrar();
    return $lista;
}


    public function consultarHistorial($rol, $idUsuarioSesion) {
    $listaPaseos = [];
    $paseoDAO = new PaseoDAO();
    $sentencia = "";

    $idUsuarioSanitizado = (int)$idUsuarioSesion;

    switch (strtolower($rol)) {
        case "dueño":
            $sentencia = $paseoDAO->consultarPaseosPorDueño($idUsuarioSanitizado);
            break;
        case "paseador":
            $sentencia = $paseoDAO->consultarPaseosPorPaseador($idUsuarioSanitizado);
            break;
        case "administrador":
            $sentencia = $paseoDAO->consultarTodosLosPaseos();
            break;
        default:
            return [];
    }

    if (empty($sentencia)) {
        return [];
    }

    $conexion = new Conexion();
    $conexion->abrir();
    $conexion->ejecutar($sentencia);

    if ($conexion->filas() > 0) {
        while ($registro = $conexion->registro()) {
            $idPaseo = $registro[0];
            $fechaInicio = $registro[1];
            $fechaFin = $registro[2];
            $tercero = $registro[3];
            $estadoPaseo = $registro[4];
            $nombrePerro = $registro[5];
            $idPerro = $registro[6];
            $precio = $registro[7];

            $p = new Paseo($idPaseo, $fechaInicio, $fechaFin, "", $estadoPaseo, $nombrePerro, $idPerro);
            $p->setPrecio($precio);

            if (strtolower($rol) === "dueño") {
                $p->setPaseador($tercero);
            } elseif (strtolower($rol) === "paseador") {
                $p->setDueño($tercero);
            }

            $listaPaseos[] = $p;
        }
    }

    $conexion->cerrar();
    return $listaPaseos;
}


    
    public function asignarPerroAPaseo($Paseo, $idPerro) {
        $conexion = new Conexion();
        $conexion->abrir();
        $paseoDAO = new PaseoDAO();
        $conexion->ejecutar($paseoDAO->insertarPaseoPerro($Paseo, $idPerro));
        $conexion->cerrar();
    }

    public function consultarRealizadosPorPaseador($idPaseador) {
        $conexion = new Conexion();
        $paseoDAO = new PaseoDAO();
        $conexion->abrir();
        $conexion->ejecutar($paseoDAO->consultarRealizadosPorPaseador($idPaseador));
        
        $resultados = [];
        while ($registro = $conexion->registro()) {
            $resultados[] = [
                "fechaInicio" => $registro[1],
                "fechaFin" => $registro[2],
                "perros" => $registro[3],
                "idPerro" => $registro[4]
            ];
        }
        
        $conexion->cerrar();
        return $resultados;
    }
    public function actualizarEstado($nuevoEstado) {
    if ($nuevoEstado == 2) {
        if (!$this->puedeAceptarPaseo()) {
            return false;
        }
    }

    $conexion = new Conexion();
    $conexion->abrir();
    $paseoDAO = new PaseoDAO($this->id);
    $conexion->ejecutar($paseoDAO->actualizarEstado($nuevoEstado));
    $exito = $conexion->afectadas() > 0;
    $conexion->cerrar();
    return $exito;
}

    public function consultarPendientesPorPaseador($idPaseador) {
    $conexion = new Conexion();
    $paseoDAO = new PaseoDAO();
    $conexion->abrir();
    $conexion->ejecutar($paseoDAO->consultarPendientesPorPaseador($idPaseador));

    $paseos = [];
    while ($registro = $conexion->registro()) {
        $paseo = new Paseo($registro[0]);
        $paseo->setFechaInicio($registro[1]);
        $paseo->setFechaFin($registro[2]);
        $paseo->setNombrePerro($registro[3]);
        $paseo->setEstadoPaseo($registro[4]);
        $paseos[] = $paseo;
    }

    $conexion->cerrar();
    return $paseos;
}
public function puedeAceptarPaseo() {
    $conexion = new Conexion();
    $conexion->abrir();

    $paseoDAO = new PaseoDAO($this->id);
    $conexion->ejecutar("SELECT FechaInicio, Paseador_idPaseador FROM Paseo WHERE idPaseo = $this->id");
    $registro = $conexion->registro();
    
    if (!$registro) {
        $conexion->cerrar();
        return false;
    }

    $fechaInicio = $registro[0];
    $idPaseador = $registro[1];
    $conexion->ejecutar($paseoDAO->contarAceptadosEnRango($idPaseador, $fechaInicio));
    $resultado = $conexion->registro();
    $conexion->cerrar();

    return $resultado[0] < 2;
}

    
    public function consultarPaseosCompletadosPorPerro($idPerro) {
        $paseos = [];
        
        $paseoDAO = new PaseoDAO();
        $sentencia = $paseoDAO->consultarPaseosCompletadosPorPerro($idPerro);
        
        $conexion = new Conexion();
        $conexion->abrir();
        $conexion->ejecutar($sentencia);
        
        if ($conexion->filas() > 0) {
            while ($registro = $conexion->registro()) {
                $idPaseo = $registro[0];
                $fechaInicio = $registro[1];
                $fechaFin = $registro[2];
                $paseador = $registro[3];
                $estadoPaseo = $registro[4];
                $nombrePerro = $registro[5];
                $precio = $registro[6];
                
                $paseo = new Paseo(
                    $idPaseo,
                    $fechaInicio,
                    $fechaFin,
                    $paseador,
                    $estadoPaseo,
                    $nombrePerro,
                    $idPerro
                    );
                $paseo->setPrecio($precio);
                $paseos[] = $paseo; 
            }
        }
        
        $conexion->cerrar();
        return $paseos;
    }
    
    
    
}

?>