<?php
class PaseoDAO {
    private $id;
    private $fechaInicio;
    private $fechaFin;
    private $idPaseador; 
    private $idEstadoPaseo; 
    
    public function __construct($id = 0, $fechaInicio = "", $fechaFin = "", $idPaseador = "", $idEstadoPaseo = ""){
        $this -> id = $id;
        $this -> fechaInicio = $fechaInicio;
        $this -> fechaFin = $fechaFin;
        $this -> idPaseador = $idPaseador;
        $this -> idEstadoPaseo = $idEstadoPaseo;
    }
    
    public function insertarPaseo() {
        return "INSERT INTO Paseo (FechaInicio, FechaFin, Paseador_idPaseador, EstadoPaseo_idEstadoPaseo)
                VALUES ('" . $this->fechaInicio . "', '" . $this->fechaFin . "', " . $this->idPaseador . ", " . $this->idEstadoPaseo . ")";
    }

    public function insertarPaseoPerro($idPaseo, $idPerro) {
        return "INSERT INTO PaseoPerro (Paseo_idPaseo, Perro_idPerro)
                VALUES (" . $idPaseo . ", " . $idPerro . ")";
    }
public function buscarHistorialDueño($idDueño, $palabras) {
    $condiciones = array_map(function($palabra) {
        $p = addslashes($palabra);
        return "(per.Nombre LIKE '%$p%' OR ep.Valor LIKE '%$p%' OR p.FechaInicio LIKE '%$p%' OR p.FechaFin LIKE '%$p%' OR CONCAT(pas.Nombre, ' ', pas.Apellido) LIKE '%$p%' OR t.PrecioHora LIKE '%$p%')";
    }, $palabras);

    return "SELECT
                p.idPaseo,
                p.FechaInicio,
                p.FechaFin,
                CONCAT(pas.Nombre, ' ', pas.Apellido) AS paseador,
                ep.Valor,
                per.Nombre,
                per.idPerro,
                t.PrecioHora
            FROM Paseo p
            INNER JOIN Paseador pas ON p.Paseador_idPaseador = pas.idPaseador
            INNER JOIN EstadoPaseo ep ON p.EstadoPaseo_idEstadoPaseo = ep.idEstadoPaseo
            INNER JOIN PaseoPerro pp ON p.idPaseo = pp.Paseo_idPaseo
            INNER JOIN Perro per ON pp.Perro_idPerro = per.idPerro
            INNER JOIN Raza r ON per.Raza_idRaza = r.idRaza
            INNER JOIN Tamaño tam ON r.Tamaño_idTamaño = tam.idTamaño
            INNER JOIN Tarifa t ON t.Paseador_idPaseador = pas.idPaseador AND t.Tamaño_idTamaño = tam.idTamaño
            WHERE per.Dueño_idDueño = $idDueño " . 
            (!empty($condiciones) ? " AND (" . implode(" AND ", $condiciones) . ")" : "") . "
            ORDER BY p.FechaInicio DESC";
}

public function buscarHistorialPaseador($idPaseador, $palabras) {
    $condiciones = array_map(function($palabra) {
        $p = addslashes($palabra);
        return "(per.Nombre LIKE '%$p%' OR ep.Valor LIKE '%$p%' OR p.FechaInicio LIKE '%$p%' OR p.FechaFin LIKE '%$p%' OR CONCAT(due.Nombre, ' ', due.Apellido) LIKE '%$p%' OR t.PrecioHora LIKE '%$p%')";
    }, $palabras);

    return "SELECT
                p.idPaseo,
                p.FechaInicio,
                p.FechaFin,
                CONCAT(due.Nombre, ' ', due.Apellido) AS dueño,
                ep.Valor,
                per.Nombre,
                per.idPerro,
                t.PrecioHora
            FROM Paseo p
            INNER JOIN EstadoPaseo ep ON p.EstadoPaseo_idEstadoPaseo = ep.idEstadoPaseo
            INNER JOIN Paseador pas ON p.Paseador_idPaseador = pas.idPaseador
            INNER JOIN PaseoPerro pp ON p.idPaseo = pp.Paseo_idPaseo
            INNER JOIN Perro per ON pp.Perro_idPerro = per.idPerro
            INNER JOIN Dueño due ON per.Dueño_idDueño = due.idDueño
            INNER JOIN Raza r ON per.Raza_idRaza = r.idRaza
            INNER JOIN Tamaño tam ON r.Tamaño_idTamaño = tam.idTamaño
            INNER JOIN Tarifa t ON t.Paseador_idPaseador = pas.idPaseador AND t.Tamaño_idTamaño = tam.idTamaño
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
        ep.Valor,
        per.Nombre,
        per.idPerro,
        t.PrecioHora
    FROM Paseo p
    INNER JOIN Paseador pas ON p.Paseador_idPaseador = pas.idPaseador
    INNER JOIN EstadoPaseo ep ON p.EstadoPaseo_idEstadoPaseo = ep.idEstadoPaseo
    INNER JOIN PaseoPerro pp ON p.idPaseo = pp.Paseo_idPaseo
    INNER JOIN Perro per ON pp.Perro_idPerro = per.idPerro
    INNER JOIN Raza r ON per.Raza_idRaza = r.idRaza
    INNER JOIN Tamaño tam ON r.Tamaño_idTamaño = tam.idTamaño
    INNER JOIN Tarifa t ON t.Paseador_idPaseador = pas.idPaseador AND t.Tamaño_idTamaño = tam.idTamaño
    WHERE per.Dueño_idDueño = $idDueño
    ORDER BY p.FechaInicio DESC";
}

    public function consultarPaseosPorPaseador($idPaseador) {
    return "SELECT
        p.idPaseo,
        p.FechaInicio,
        p.FechaFin,
        CONCAT(due.Nombre, ' ', due.Apellido) AS dueño,
        ep.Valor,
        per.Nombre,
        per.idPerro,
        t.PrecioHora
    FROM Paseo p
    INNER JOIN EstadoPaseo ep ON p.EstadoPaseo_idEstadoPaseo = ep.idEstadoPaseo
    INNER JOIN Paseador pas ON p.Paseador_idPaseador = pas.idPaseador
    INNER JOIN PaseoPerro pp ON p.idPaseo = pp.Paseo_idPaseo
    INNER JOIN Perro per ON pp.Perro_idPerro = per.idPerro
    INNER JOIN Dueño due ON per.Dueño_idDueño = due.idDueño
    INNER JOIN Raza r ON per.Raza_idRaza = r.idRaza
    INNER JOIN Tamaño tam ON r.Tamaño_idTamaño = tam.idTamaño
    INNER JOIN Tarifa t ON t.Paseador_idPaseador = pas.idPaseador AND t.Tamaño_idTamaño = tam.idTamaño
    WHERE p.Paseador_idPaseador = $idPaseador
    ORDER BY p.FechaInicio DESC";
}


    
    public function consultarTodosLosPaseos() {
        return "SELECT
                    p.idPaseo,
                    p.FechaInicio,
                    p.FechaFin,
                    CONCAT(pas.Nombre, ' ', pas.Apellido) AS paseador,
                    ep.Valor AS EstadoPaseoValor
                FROM Paseo p
                INNER JOIN Paseador pas ON p.Paseador_idPaseador = pas.idPaseador
                INNER JOIN EstadoPaseo ep ON p.EstadoPaseo_idEstadoPaseo = ep.idEstadoPaseo
                ORDER BY p.FechaInicio DESC";
    }
    public function consultarRealizadosPorPaseador($idPaseador) {
        return "SELECT
            p.idPaseo,
            p.FechaInicio,
            p.FechaFin,
            per.Nombre AS nombres_perros,
            per.idPerro 
        FROM Paseo p
        JOIN PaseoPerro pp ON p.idPaseo = pp.Paseo_idPaseo
        JOIN Perro per ON pp.Perro_idPerro = per.idPerro
        JOIN EstadoPaseo ep ON p.EstadoPaseo_idEstadoPaseo = ep.idEstadoPaseo
        WHERE p.Paseador_idPaseador = $idPaseador
          AND ep.Valor = 'Completado'
        -- <<< ELIMINAR GROUP BY para que cada perro aparezca en su propia fila
        ORDER BY p.FechaInicio DESC";
    }
    public function actualizarEstado($nuevoEstado) {
        return "UPDATE paseo
            SET EstadoPaseo_idEstadoPaseo = $nuevoEstado
            WHERE idPaseo = $this->id";
    }
    
   public function consultarPendientesPorPaseador($idPaseador) {
    return "
        SELECT
            p.idPaseo,
            p.FechaInicio,
            p.FechaFin,
            GROUP_CONCAT(per.Nombre SEPARATOR ', ') AS nombres_perros,
            ep.Valor AS EstadoPaseo
        FROM Paseo p
        JOIN PaseoPerro pp ON p.idPaseo = pp.Paseo_idPaseo
        JOIN Perro per ON pp.Perro_idPerro = per.idPerro
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
          AND EstadoPaseo_idEstadoPaseo = 2 -- Aceptado
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
            ep.Valor AS estado,
            per.Nombre AS nombrePerro,
            t.PrecioHora AS precio
        FROM Paseo p
        INNER JOIN EstadoPaseo ep ON p.EstadoPaseo_idEstadoPaseo = ep.idEstadoPaseo
        INNER JOIN Paseador pa ON p.Paseador_idPaseador = pa.idPaseador
        INNER JOIN PaseoPerro pp ON p.idPaseo = pp.Paseo_idPaseo
        INNER JOIN Perro per ON pp.Perro_idPerro = per.idPerro
        INNER JOIN Raza r ON per.Raza_idRaza = r.idRaza
        INNER JOIN Tamaño tam ON r.Tamaño_idTamaño = tam.idTamaño
        INNER JOIN Tarifa t ON t.Paseador_idPaseador = pa.idPaseador AND t.Tamaño_idTamaño = tam.idTamaño
        WHERE ep.Valor = 'Completado' AND per.idPerro = $idPerro
        ORDER BY p.FechaInicio DESC
        LIMIT 1
    ";
    }
    
}
?>