<?php
class RazaDAO {
    private $id;
    private $nombre;
    private $tamaño;
    
    
    public function __construct($id = 0, $nombre = "", $tamaño = ""){
        $this -> id = $id;
        $this -> nombre = $nombre;
        $this -> tamaño = $tamaño;
    }
    
    public function consultarTodasLasRazas() {
        return "SELECT idRaza, nombre, Tamaño_idTamaño FROM Raza";
    }

    public function insertarRazaSinTamaño() {
        return "INSERT INTO Raza (nombre, Tamaño_idTamaño) VALUES ('" . $this->nombre . "', 5)";
    }
    public function consultarRazasSinTamaño() {
        return "SELECT idRaza, nombre FROM Raza WHERE Tamaño_idTamaño IS NULL";
    }
    public function asignarTamaño($idTamaño) {
        return "UPDATE Raza SET Tamaño_idTamaño = $idTamaño WHERE idRaza = $this->id";
    }
    public function consultarRazasConTamaño() {
        return "SELECT R.idRaza, R.nombre, T.tipo
            FROM Raza R
            LEFT JOIN Tamaño T ON R.Tamaño_idTamaño = T.idTamaño
            ORDER BY R.Tamaño_idTamaño = 5 DESC, R.idRaza DESC";
    }
   
    
}
?>