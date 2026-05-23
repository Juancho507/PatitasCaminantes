<?php
class PerroDAO {
    private $id;
    private $nombre;
    private $peso;
    private $recomendacion;
    private $activo;
    private $foto;
    private $raza;
    private $dueño;
    private $peligrosidad;

    public function __construct($id = 0, $nombre = "", $peso = 0, $recomendacion = "", $activo = 1, $foto = "", $raza = "", $dueño = "", $peligrosidad = 0){
        $this->id = $id;
        $this->nombre = $nombre;
        $this->peso = $peso;
        $this->recomendacion = $recomendacion;
        $this->activo = $activo;
        $this->foto = $foto;
        $this->raza = $raza;
        $this->dueño = $dueño;
        $this->peligrosidad = $peligrosidad;
    }

    public function consultarTodosLosPerros() {
        return "SELECT p.idPerro, p.Nombre, p.Foto, r.Raza, CONCAT(d.Nombre, ' ', d.Apellido) AS nombreCompletoDueño, t.Tamaño, p.Peso, p.Recomendacion, p.Peligrosidad_idPeligrosidad, p.Estado_idEstado
                FROM Perro p
                INNER JOIN Raza r ON p.Raza_idRaza = r.idRaza
                INNER JOIN Dueño d ON p.Dueño_idDueño = d.idDueño
                INNER JOIN Tamaño t ON r.Tamaño_idTamaño = t.idTamaño";
    }

    public function consultarPerrosPorDueño($idDueño) {
        return "SELECT p.idPerro, p.Nombre, p.Foto, r.Raza, CONCAT(d.Nombre, ' ', d.Apellido) AS nombreCompletoDueño, t.Tamaño, p.Peso, p.Recomendacion, p.Peligrosidad_idPeligrosidad, p.Estado_idEstado
                FROM Perro p
                INNER JOIN Raza r ON p.Raza_idRaza = r.idRaza
                INNER JOIN Dueño d ON p.Dueño_idDueño = d.idDueño
                INNER JOIN Tamaño t ON r.Tamaño_idTamaño = t.idTamaño
                WHERE p.Dueño_idDueño = " . $idDueño;
    }

    public function consultarPorId() {
        return "SELECT p.idPerro, p.Nombre, p.Foto, r.Raza, CONCAT(d.Nombre, ' ', d.Apellido) AS nombreCompletoDueño, t.Tamaño, p.Peso, p.Recomendacion, p.Peligrosidad_idPeligrosidad, p.Estado_idEstado
                FROM Perro p
                INNER JOIN Raza r ON p.Raza_idRaza = r.idRaza
                INNER JOIN Dueño d ON p.Dueño_idDueño = d.idDueño
                INNER JOIN Tamaño t ON r.Tamaño_idTamaño = t.idTamaño
                WHERE p.idPerro = " . $this->id;
    }

    public function insertar(){
        return "INSERT INTO Perro (Nombre, Peso, Recomendacion, Estado_idEstado, Foto, Raza_idRaza, Dueño_idDueño, Peligrosidad_idPeligrosidad)
                VALUES ('" . $this->nombre . "', " . $this->peso . ", '" . $this->recomendacion . "', " . $this->activo . ", '" . $this->foto . "', " . $this->raza . ", " . $this->dueño . ", " . $this->peligrosidad . ")";
    }

    public function actualizar(){
        return "UPDATE Perro SET
                Nombre = '" . $this->nombre . "',
                Peso = " . $this->peso . ",
                Recomendacion = '" . $this->recomendacion . "',
                Estado_idEstado = " . $this->activo . ",
                Foto = '" . $this->foto . "',
                Raza_idRaza = " . $this->raza . ",
                Dueño_idDueño = " . $this->dueño . ",
                Peligrosidad_idPeligrosidad = " . $this->peligrosidad . "
                WHERE idPerro = " . $this->id;
    }

    public function eliminar() {
        return "DELETE FROM Perro WHERE idPerro = " . $this->id;
    }

}
?>
