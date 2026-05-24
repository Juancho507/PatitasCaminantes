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
    private $bozal;
    private $ultimoError = "";

public function getDueño() {
    return $this->dueño;
}
public function setDueño($dueño) {
    $this->dueño = $dueño;
}

public function getBozal() {
    return $this->bozal;
}
public function setBozal($bozal) {
    $this->bozal = $bozal;
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
    public function getIdEstadoPaseo() {
        return $this->idEstadoPaseo;
    }
    public function getUltimoError() {
        return $this->ultimoError;
    }
    public function setIdEstadoPaseo($id) {
        $this->idEstadoPaseo = $id;
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
    
    public function insertar($idPerros = []) {
        $conexion = new Conexion();
        $conexion->abrir();
        $fechaInicioDT = new DateTime($this->fechaInicio);
        $fechaFinDT = clone $fechaInicioDT;
        $fechaFinDT->modify('+1 hour');
        $this->fechaFin = $fechaFinDT->format('Y-m-d H:i:s');
        
        $idPerros = array_pad((array)$idPerros, 6, 0);
        
        $paseoDAO = new PaseoDAO(0, $this->fechaInicio, $this->fechaFin, $this->paseador, $this->estadoPaseo, $this->bozal, "", (int)$idPerros[0], (int)$idPerros[1], (int)$idPerros[2], (int)$idPerros[3], (int)$idPerros[4], (int)$idPerros[5]);
        $conexion->ejecutar($paseoDAO->insertarPaseo());
        
        if ($conexion->getResultado()) {
            $idInsertado = $conexion->obtenerId(); 
            $this->id = $idInsertado; 
            $conexion->cerrar();
            return true;
        }
        
        $this->ultimoError = $conexion->getError();
        $conexion->cerrar();
        return false;
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
        $paseo->setIdEstadoPaseo((int)$registro[8]);

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
    if ($resultado[0] >= 2) {
        $conexion->cerrar();
        return false;
    }
    $conexion->cerrar();

    $perrosPaseo = [];
    $conexion2 = new Conexion();
    $conexion2->abrir();
    $conexion2->ejecutar("SELECT perro_idPerro, perro_idPerro2, perro_idPerro3, perro_idPerro4, perro_idPerro5, perro_idPerro6 FROM Paseo WHERE idPaseo = $this->id");
    if ($fila = $conexion2->registro()) {
        for ($i = 0; $i <= 5; $i++) {
            $val = (int)$fila[$i];
            if ($val > 0) $perrosPaseo[] = $val;
        }
    }
    $conexion2->cerrar();

    $resultado = self::validarCapacidadConcurrente($idPaseador, $fechaInicio, $perrosPaseo, $this->id, false);
    return $resultado["valido"];
}

    
    public static function validarCapacidadConcurrente($idPaseador, $fechaInicio, $nuevosIdsPerros, $excluirIdPaseo = 0, $incluirPendientes = false) {
        $conexion = new Conexion();
        $conexion->abrir();

        $estados = $incluirPendientes ? "IN (1,2)" : "= 2";
        $excluir = $excluirIdPaseo > 0 ? "AND idPaseo != $excluirIdPaseo" : "";
        $conexion->ejecutar("SELECT perro_idPerro, perro_idPerro2, perro_idPerro3, perro_idPerro4, perro_idPerro5, perro_idPerro6
                             FROM Paseo
                             WHERE Paseador_idPaseador = $idPaseador
                               AND Estado_idEstado $estados
                               AND TIMESTAMPDIFF(MINUTE, '$fechaInicio', FechaInicio) BETWEEN -59 AND 59
                               $excluir");

        $todosIds = $nuevosIdsPerros;
        while ($fila = $conexion->registro()) {
            for ($i = 0; $i <= 5; $i++) {
                $val = (int)$fila[$i];
                if ($val > 0) $todosIds[] = $val;
            }
        }
        $conexion->cerrar();

        $total = count($todosIds);
        if ($total > 5) {
            return ["valido" => false, "mensaje" => "Límite absoluto de 5 perros excedido en este horario.", "total" => $total, "limite" => 5];
        }

        $idsUnicos = [];
        foreach ($todosIds as $v) {
            if ($v > 0) $idsUnicos[$v] = true;
        }
        $idsUnicos = array_keys($idsUnicos);


        $idsStr = implode(",", $idsUnicos);
        $conexion2 = new Conexion();
        $conexion2->abrir();
        $conexion2->ejecutar("SELECT DISTINCT pg.Nivel FROM Perro p
                              INNER JOIN Peligrosidad pg ON p.Peligrosidad_idPeligrosidad = pg.idPeligrosidad
                              WHERE p.idPerro IN ($idsStr)");

        $jerarquia = ["BAJO" => 1, "MEDIO" => 2, "ALTO" => 3, "PELIGROSO" => 4];
        $limiteMap = ["BAJO" => 5, "MEDIO" => 3, "ALTO" => 2, "PELIGROSO" => 1];
        $maxNivel = "BAJO";
        $maxInt = 0;
        while ($fila = $conexion2->registro()) {
            $nivel = strtoupper($fila[0]);
            if (($jerarquia[$nivel] ?? 0) > $maxInt) {
                $maxInt = $jerarquia[$nivel];
                $maxNivel = $nivel;
            }
        }
        $conexion2->cerrar();

        $limite = $limiteMap[$maxNivel];
        $nivelStr = ucfirst(strtolower($maxNivel));

        if ($total > $limite) {
            return ["valido" => false, "mensaje" => "Ya tienes paseos con nivel $nivelStr en este horario (máx. $limite perros, total $total).", "total" => $total, "limite" => $limite];
        }

        return ["valido" => true, "mensaje" => "Capacidad suficiente.", "total" => $total, "limite" => $limite];
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

