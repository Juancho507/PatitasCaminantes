<?php
class LocalidadDAO {
    private $id;
    private $nombre;
    private $idCiudad;

    public function __construct($id = 0, $nombre = "", $idCiudad = 0) {
        $this->id = $id;
        $this->nombre = $nombre;
        $this->idCiudad = $idCiudad;
    }

    public function consultarPorCiudad() {
        return "SELECT idLocalidad, Localidad FROM localidad WHERE Ciudad_idCiudad = " . $this->idCiudad . " ORDER BY Localidad ASC";
    }

    public function consultarTodos() {
        return "SELECT l.idLocalidad, l.Localidad, c.Ciudad
                FROM localidad l
                INNER JOIN ciudad c ON l.Ciudad_idCiudad = c.idCiudad
                ORDER BY c.Ciudad ASC, l.Localidad ASC";
    }

    public function consultar() {
        return "SELECT Localidad FROM localidad WHERE idLocalidad = " . $this->id;
    }
}
?>
