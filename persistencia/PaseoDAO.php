<?php
class PaseoDAO {
    private $id;
    private $fechaInicio;
    private $fechaFin;
    private $idPaseador; 
    private $idEstadoPaseo;
    private $bozal;
    private $observaciones;
    private $perro_idPerro;
    private $perro_idPerro2;
    private $perro_idPerro3;
    private $perro_idPerro4;
    private $perro_idPerro5;
    private $perro_idPerro6;
    
    public function __construct($id = 0, $fechaInicio = "", $fechaFin = "", $idPaseador = "", $idEstadoPaseo = "", $bozal = 0, $observaciones = "", $perro_idPerro = 0, $perro_idPerro2 = 0, $perro_idPerro3 = 0, $perro_idPerro4 = 0, $perro_idPerro5 = 0, $perro_idPerro6 = 0){
        $this -> id = $id;
        $this -> fechaInicio = $fechaInicio;
        $this -> fechaFin = $fechaFin;
        $this -> idPaseador = $idPaseador;
        $this -> idEstadoPaseo = $idEstadoPaseo;
        $this -> bozal = $bozal;
        $this -> observaciones = $observaciones;
        $this -> perro_idPerro = $perro_idPerro;
        $this -> perro_idPerro2 = $perro_idPerro2;
        $this -> perro_idPerro3 = $perro_idPerro3;
        $this -> perro_idPerro4 = $perro_idPerro4;
        $this -> perro_idPerro5 = $perro_idPerro5;
        $this -> perro_idPerro6 = $perro_idPerro6;
    }
    
    public function insertarPaseo() {
        $perro1 = $this->perro_idPerro > 0 ? $this->perro_idPerro : "NULL";
        $perro2 = $this->perro_idPerro2 > 0 ? $this->perro_idPerro2 : "NULL";
        $perro3 = $this->perro_idPerro3 > 0 ? $this->perro_idPerro3 : "NULL";
        $perro4 = $this->perro_idPerro4 > 0 ? $this->perro_idPerro4 : "NULL";
        $perro5 = $this->perro_idPerro5 > 0 ? $this->perro_idPerro5 : "NULL";
        $perro6 = $this->perro_idPerro6 > 0 ? $this->perro_idPerro6 : "NULL";
        return "INSERT INTO Paseo (FechaInicio, FechaFin, Bozal, Observaciones, Paseador_idPaseador, EstadoPaseo_idEstadoPaseo, perro_idPerro, perro_idPerro2, perro_idPerro3, perro_idPerro4, perro_idPerro5, perro_idPerro6)
                VALUES ('" . $this->fechaInicio . "', '" . $this->fechaFin . "', " . $this->bozal . ", '" . $this->observaciones . "', " . $this->idPaseador . ", " . $this->idEstadoPaseo . ", $perro1, $perro2, $perro3, $perro4, $perro5, $perro6)";
    }

    public function buscarHistorialDueño($idDueño, $palabras) {
    $condiciones = array_map(function($palabra) {
        $p = addslashes($palabra);
        return "(per1.Nombre LIKE '%$p%' OR per2.Nombre LIKE '%$p%' OR per3.Nombre LIKE '%$p%' OR per4.Nombre LIKE '%$p%' OR per5.Nombre LIKE '%$p%' OR per6.Nombre LIKE '%$p%' OR ep.Estado LIKE '%$p%' OR p.FechaInicio LIKE '%$p%' OR p.FechaFin LIKE '%$p%' OR CONCAT(pas.Nombre, ' ', pas.Apellido) LIKE '%$p%' OR t.PrecioHora LIKE '%$p%')";
    }, $palabras);

    return "SELECT
                p.idPaseo,
                p.FechaInicio,
                p.FechaFin,
                CONCAT(pas.Nombre, ' ', pas.Apellido) AS paseador,
                ep.Estado,
                CONCAT_WS(', ', per1.Nombre, per2.Nombre, per3.Nombre, per4.Nombre, per5.Nombre, per6.Nombre) AS nombres_perros,
                per1.idPerro,
                t.PrecioHora,
                p.EstadoPaseo_idEstadoPaseo
            FROM Paseo p
            INNER JOIN Paseador pas ON p.Paseador_idPaseador = pas.idPaseador
            INNER JOIN EstadoPaseo ep ON p.EstadoPaseo_idEstadoPaseo = ep.idEstadoPaseo
            LEFT JOIN Perro per1 ON p.perro_idPerro = per1.idPerro
            LEFT JOIN Perro per2 ON p.perro_idPerro2 = per2.idPerro
            LEFT JOIN Perro per3 ON p.perro_idPerro3 = per3.idPerro
            LEFT JOIN Perro per4 ON p.perro_idPerro4 = per4.idPerro
            LEFT JOIN Perro per5 ON p.perro_idPerro5 = per5.idPerro
            LEFT JOIN Perro per6 ON p.perro_idPerro6 = per6.idPerro
            INNER JOIN Raza r ON per1.Raza_idRaza = r.idRaza
            INNER JOIN Tamaño tam ON r.Tamaño_idTamaño = tam.idTamaño
            INNER JOIN Tarifa t ON t.Paseador_idPaseador = pas.idPaseador AND t.Peligrosidad_idPeligrosidad = per1.Peligrosidad_idPeligrosidad AND t.Activa = 1
            WHERE per1.Dueño_idDueño = $idDueño " . 
            (!empty($condiciones) ? " AND (" . implode(" AND ", $condiciones) . ")" : "") . "
            ORDER BY p.FechaInicio DESC";
}

public function buscarHistorialPaseador($idPaseador, $palabras) {
    $condiciones = array_map(function($palabra) {
        $p = addslashes($palabra);
        return "(per1.Nombre LIKE '%$p%' OR per2.Nombre LIKE '%$p%' OR per3.Nombre LIKE '%$p%' OR per4.Nombre LIKE '%$p%' OR per5.Nombre LIKE '%$p%' OR per6.Nombre LIKE '%$p%' OR ep.Estado LIKE '%$p%' OR p.FechaInicio LIKE '%$p%' OR p.FechaFin LIKE '%$p%' OR CONCAT(due.Nombre, ' ', due.Apellido) LIKE '%$p%' OR t.PrecioHora LIKE '%$p%')";
    }, $palabras);

    return "SELECT
                p.idPaseo,
                p.FechaInicio,
                p.FechaFin,
                CONCAT(due.Nombre, ' ', due.Apellido) AS dueño,
                ep.Estado,
                CONCAT_WS(', ', per1.Nombre, per2.Nombre, per3.Nombre, per4.Nombre, per5.Nombre, per6.Nombre) AS nombres_perros,
                per1.idPerro,
                t.PrecioHora,
                p.EstadoPaseo_idEstadoPaseo
            FROM Paseo p
            INNER JOIN EstadoPaseo ep ON p.EstadoPaseo_idEstadoPaseo = ep.idEstadoPaseo
            INNER JOIN Paseador pas ON p.Paseador_idPaseador = pas.idPaseador
            LEFT JOIN Perro per1 ON p.perro_idPerro = per1.idPerro
            LEFT JOIN Perro per2 ON p.perro_idPerro2 = per2.idPerro
            LEFT JOIN Perro per3 ON p.perro_idPerro3 = per3.idPerro
            LEFT JOIN Perro per4 ON p.perro_idPerro4 = per4.idPerro
            LEFT JOIN Perro per5 ON p.perro_idPerro5 = per5.idPerro
            LEFT JOIN Perro per6 ON p.perro_idPerro6 = per6.idPerro
            INNER JOIN Dueño due ON per1.Dueño_idDueño = due.idDueño
            INNER JOIN Raza r ON per1.Raza_idRaza = r.idRaza
            INNER JOIN Tamaño tam ON r.Tamaño_idTamaño = tam.idTamaño
            INNER JOIN Tarifa t ON t.Paseador_idPaseador = pas.idPaseador AND t.Peligrosidad_idPeligrosidad = per1.Peligrosidad_idPeligrosidad AND t.Activa = 1
            WHERE p.Paseador_idPaseador = $idPaseador " .
            (!empty($condiciones) ? " AND (" . implode(" AND ", $condiciones) . ")" : "") . "
            ORDER BY p.FechaInicio DESC";
}


    public function consultarPaseosPorDueño($idDueño) {
    return "SELECT
        p.idPaseo,
        p.FechaInicio,
        p.FechaFin,
        CONCAT(pas.Nombre, ' ', pas.Apellido) AS paseador,
        ep.Estado,
        CONCAT_WS(', ', per1.Nombre, per2.Nombre, per3.Nombre, per4.Nombre, per5.Nombre, per6.Nombre) AS nombres_perros,
        per1.idPerro,
        t.PrecioHora
    FROM Paseo p
    INNER JOIN Paseador pas ON p.Paseador_idPaseador = pas.idPaseador
    INNER JOIN EstadoPaseo ep ON p.EstadoPaseo_idEstadoPaseo = ep.idEstadoPaseo
    LEFT JOIN Perro per1 ON p.perro_idPerro = per1.idPerro
    LEFT JOIN Perro per2 ON p.perro_idPerro2 = per2.idPerro
    LEFT JOIN Perro per3 ON p.perro_idPerro3 = per3.idPerro
    LEFT JOIN Perro per4 ON p.perro_idPerro4 = per4.idPerro
    LEFT JOIN Perro per5 ON p.perro_idPerro5 = per5.idPerro
    LEFT JOIN Perro per6 ON p.perro_idPerro6 = per6.idPerro
    INNER JOIN Raza r ON per1.Raza_idRaza = r.idRaza
    INNER JOIN Tamaño tam ON r.Tamaño_idTamaño = tam.idTamaño
    INNER JOIN Tarifa t ON t.Paseador_idPaseador = pas.idPaseador AND t.Peligrosidad_idPeligrosidad = per1.Peligrosidad_idPeligrosidad AND t.Activa = 1
    WHERE per1.Dueño_idDueño = $idDueño
    ORDER BY p.FechaInicio DESC";
}

    public function consultarPaseosPorPaseador($idPaseador) {
    return "SELECT
        p.idPaseo,
        p.FechaInicio,
        p.FechaFin,
        CONCAT(due.Nombre, ' ', due.Apellido) AS dueño,
        ep.Estado,
        CONCAT_WS(', ', per1.Nombre, per2.Nombre, per3.Nombre, per4.Nombre, per5.Nombre, per6.Nombre) AS nombres_perros,
        per1.idPerro,
        t.PrecioHora
    FROM Paseo p
    INNER JOIN EstadoPaseo ep ON p.EstadoPaseo_idEstadoPaseo = ep.idEstadoPaseo
    INNER JOIN Paseador pas ON p.Paseador_idPaseador = pas.idPaseador
    LEFT JOIN Perro per1 ON p.perro_idPerro = per1.idPerro
    LEFT JOIN Perro per2 ON p.perro_idPerro2 = per2.idPerro
    LEFT JOIN Perro per3 ON p.perro_idPerro3 = per3.idPerro
    LEFT JOIN Perro per4 ON p.perro_idPerro4 = per4.idPerro
    LEFT JOIN Perro per5 ON p.perro_idPerro5 = per5.idPerro
    LEFT JOIN Perro per6 ON p.perro_idPerro6 = per6.idPerro
    INNER JOIN Dueño due ON per1.Dueño_idDueño = due.idDueño
    INNER JOIN Raza r ON per1.Raza_idRaza = r.idRaza
    INNER JOIN Tamaño tam ON r.Tamaño_idTamaño = tam.idTamaño
    INNER JOIN Tarifa t ON t.Paseador_idPaseador = pas.idPaseador AND t.Peligrosidad_idPeligrosidad = per1.Peligrosidad_idPeligrosidad AND t.Activa = 1
    WHERE p.Paseador_idPaseador = $idPaseador
    ORDER BY p.FechaInicio DESC";
}


    
    public function consultarTodosLosPaseos() {
        return "SELECT
                    p.idPaseo,
                    p.FechaInicio,
                    p.FechaFin,
                    CONCAT(pas.Nombre, ' ', pas.Apellido) AS paseador,
                    ep.Estado,
                    CONCAT_WS(', ', per1.Nombre, per2.Nombre, per3.Nombre, per4.Nombre, per5.Nombre, per6.Nombre) AS nombres_perros,
                    per1.idPerro,
                    t.PrecioHora
                FROM Paseo p
                INNER JOIN Paseador pas ON p.Paseador_idPaseador = pas.idPaseador
                INNER JOIN EstadoPaseo ep ON p.EstadoPaseo_idEstadoPaseo = ep.idEstadoPaseo
                LEFT JOIN Perro per1 ON p.perro_idPerro = per1.idPerro
                LEFT JOIN Perro per2 ON p.perro_idPerro2 = per2.idPerro
                LEFT JOIN Perro per3 ON p.perro_idPerro3 = per3.idPerro
                LEFT JOIN Perro per4 ON p.perro_idPerro4 = per4.idPerro
                LEFT JOIN Perro per5 ON p.perro_idPerro5 = per5.idPerro
                LEFT JOIN Perro per6 ON p.perro_idPerro6 = per6.idPerro
                LEFT JOIN Raza r ON per1.Raza_idRaza = r.idRaza
                LEFT JOIN Tarifa t ON t.Paseador_idPaseador = pas.idPaseador AND t.Peligrosidad_idPeligrosidad = per1.Peligrosidad_idPeligrosidad AND t.Activa = 1
                ORDER BY p.FechaInicio DESC";
    }
    public function consultarRealizadosPorPaseador($idPaseador) {
        return "SELECT
            p.idPaseo,
            p.FechaInicio,
            p.FechaFin,
            CONCAT_WS(', ', per1.Nombre, per2.Nombre, per3.Nombre, per4.Nombre, per5.Nombre, per6.Nombre) AS nombres_perros,
            per1.idPerro 
        FROM Paseo p
        LEFT JOIN Perro per1 ON p.perro_idPerro = per1.idPerro
        LEFT JOIN Perro per2 ON p.perro_idPerro2 = per2.idPerro
        LEFT JOIN Perro per3 ON p.perro_idPerro3 = per3.idPerro
        LEFT JOIN Perro per4 ON p.perro_idPerro4 = per4.idPerro
        LEFT JOIN Perro per5 ON p.perro_idPerro5 = per5.idPerro
        LEFT JOIN Perro per6 ON p.perro_idPerro6 = per6.idPerro
        JOIN EstadoPaseo ep ON p.EstadoPaseo_idEstadoPaseo = ep.idEstadoPaseo
        WHERE p.Paseador_idPaseador = $idPaseador
          AND ep.Estado = 'Completado'
        ORDER BY p.FechaInicio DESC";
    }
    public function actualizarEstado($nuevoEstado) {
        return "UPDATE Paseo
            SET EstadoPaseo_idEstadoPaseo = $nuevoEstado
            WHERE idPaseo = $this->id";
    }
    
   public function consultarPendientesPorPaseador($idPaseador) {
    return "
        SELECT
            p.idPaseo,
            p.FechaInicio,
            p.FechaFin,
            CONCAT_WS(', ', per1.Nombre, per2.Nombre, per3.Nombre, per4.Nombre, per5.Nombre, per6.Nombre) AS nombres_perros,
            ep.Estado AS EstadoPaseo
        FROM Paseo p
        LEFT JOIN Perro per1 ON p.perro_idPerro = per1.idPerro
        LEFT JOIN Perro per2 ON p.perro_idPerro2 = per2.idPerro
        LEFT JOIN Perro per3 ON p.perro_idPerro3 = per3.idPerro
        LEFT JOIN Perro per4 ON p.perro_idPerro4 = per4.idPerro
        LEFT JOIN Perro per5 ON p.perro_idPerro5 = per5.idPerro
        LEFT JOIN Perro per6 ON p.perro_idPerro6 = per6.idPerro
        JOIN EstadoPaseo ep ON p.EstadoPaseo_idEstadoPaseo = ep.idEstadoPaseo
        WHERE p.Paseador_idPaseador = $idPaseador
          AND p.EstadoPaseo_idEstadoPaseo = 1
        GROUP BY p.idPaseo
        ORDER BY p.FechaInicio DESC
    ";
}
public function contarAceptadosEnRango($idPaseador, $fechaInicio) {
    return "
        SELECT COUNT(*) 
        FROM Paseo 
        WHERE Paseador_idPaseador = $idPaseador
          AND EstadoPaseo_idEstadoPaseo = 2
          AND TIMESTAMPDIFF(MINUTE, '$fechaInicio', FechaInicio) BETWEEN -59 AND 59
    ";
}

    public function consultarPaseosCompletadosPorPerro($idPerro) {
        return "
        SELECT
            p.idPaseo,
            p.FechaInicio,
            p.FechaFin,
            CONCAT(pa.Nombre, ' ', pa.Apellido) AS paseador,
            ep.Estado AS estado,
            CONCAT_WS(', ', per1.Nombre, per2.Nombre, per3.Nombre, per4.Nombre, per5.Nombre, per6.Nombre) AS nombrePerro,
            t.PrecioHora AS precio
        FROM Paseo p
        INNER JOIN EstadoPaseo ep ON p.EstadoPaseo_idEstadoPaseo = ep.idEstadoPaseo
        INNER JOIN Paseador pa ON p.Paseador_idPaseador = pa.idPaseador
        LEFT JOIN Perro per1 ON p.perro_idPerro = per1.idPerro
        LEFT JOIN Perro per2 ON p.perro_idPerro2 = per2.idPerro
        LEFT JOIN Perro per3 ON p.perro_idPerro3 = per3.idPerro
        LEFT JOIN Perro per4 ON p.perro_idPerro4 = per4.idPerro
        LEFT JOIN Perro per5 ON p.perro_idPerro5 = per5.idPerro
        LEFT JOIN Perro per6 ON p.perro_idPerro6 = per6.idPerro
        LEFT JOIN Raza r ON per1.Raza_idRaza = r.idRaza
        LEFT JOIN Tamaño tam ON r.Tamaño_idTamaño = tam.idTamaño
        LEFT JOIN Tarifa t ON t.Paseador_idPaseador = pa.idPaseador AND t.Peligrosidad_idPeligrosidad = per1.Peligrosidad_idPeligrosidad AND t.Activa = 1
        WHERE ep.Estado = 'Completado'
          AND (p.perro_idPerro = $idPerro OR p.perro_idPerro2 = $idPerro OR p.perro_idPerro3 = $idPerro OR p.perro_idPerro4 = $idPerro OR p.perro_idPerro5 = $idPerro OR p.perro_idPerro6 = $idPerro)
        ORDER BY p.FechaInicio DESC
        LIMIT 1
    ";
    }
    
}
?>
