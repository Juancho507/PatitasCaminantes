<?php
class PerroDAO {
    private $id;
    private $nombre;
    private $foto;
    private $raza; 
    private $dueño; 
    
    public function __construct($id = 0, $nombre = "", $foto = "", $raza = "", $dueño = ""){
        $this -> id = $id;
        $this -> nombre = $nombre;
        $this -> foto = $foto;
        $this -> raza = $raza;
        $this -> dueño = $dueño;
    }
    public function consultarTodosLosPerros() {
        return "SELECT p.idPerro, p.nombre, p.foto, r.nombre, CONCAT(d.nombre, ' ', d.apellido) AS nombreCompletoDueño, t.Tipo AS tipo
                FROM Perro p
                INNER JOIN Raza r ON p.Raza_idRaza = r.idRaza     
                INNER JOIN Dueño d ON p.Dueño_idDueño = d.idDueño; 
                INNER JOIN Tamaño t ON r.Tamaño_idTamaño = t.idTamaño";
    }
    public function consultarPerrosPorDueño($idDueño) {
        return "SELECT p.idPerro, p.nombre, p.foto, r.nombre, CONCAT(d.nombre, ' ', d.apellido) AS nombreCompletoDueño, t.Tipo AS tipo
                FROM Perro p
                INNER JOIN Raza r ON p.Raza_idRaza = r.idRaza      
                INNER JOIN Dueño d ON p.Dueño_idDueño = d.idDueño  
                INNER JOIN Tamaño t ON r.Tamaño_idTamaño = t.idTamaño
                WHERE p.Dueño_idDueño = " . $idDueño;             
    }
    public function consultarPorId() {
        return "SELECT p.idPerro, p.nombre, p.foto, r.nombre, CONCAT(d.nombre, ' ', d.apellido) AS nombreCompletoDueño, t.Tipo AS tipo
                FROM Perro p
                INNER JOIN Raza r ON p.Raza_idRaza = r.idRaza      
                INNER JOIN Dueño d ON p.Dueño_idDueño = d.idDueño 
                INNER JOIN Tamaño t ON r.Tamaño_idTamaño = t.idTamaño
                WHERE p.idPerro = " . $this->id;
    }
    public function insertar(){
        return "INSERT INTO Perro (Nombre, Foto, Raza_idRaza, Dueño_idDueño)
                VALUES ('" . $this->nombre . "', '" . $this->foto . "', " . $this->raza . ", " . $this->dueño . ")";
    }
    
    public function actualizar(){
        return "UPDATE Perro SET
                Nombre = '" . $this->nombre . "',
                Foto = '" . $this->foto . "',
                Raza_idRaza = " . $this->raza . ",
                Dueño_idDueño = " . $this->dueño . "
            WHERE idPerro = " . $this->id;
    }
    
    public function eliminar() {
        return "DELETE FROM Perro WHERE idPerro = " . $this->id;
    }
    public function eliminarPaseosPorPerro($idPerro) {
        return "DELETE FROM PaseoPerro WHERE Perro_idPerro = $idPerro";
    }
}
?>