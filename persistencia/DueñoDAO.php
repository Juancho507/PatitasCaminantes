<?php
class DueñoDAO{
    private $id;
    private $nombre;
    private $apellido;
    private $correo;
    private $clave;
    private $contacto;
    private $foto;

    public function __construct($id = "" , $nombre = "", $apellido = "", $correo = "", $clave = "", $contacto = "", $foto = "") {
    $this->id = $id;
    $this->nombre = $nombre;
    $this->apellido = $apellido;
    $this->correo = $correo;
    $this->clave = $clave;
    $this->contacto = $contacto;
    $this->foto = $foto;
}


    public function autenticarse() {
        return "SELECT idDueño
                FROM Dueño
                WHERE correo = '" . $this -> correo . "' AND clave = '" . md5($this -> clave) . "'";
    }

    public function registrar() {
        return "INSERT INTO Dueño (Nombre, Apellido, Correo, Clave, Contacto, Foto)
                VALUES ('" . $this->nombre . "','" . $this->apellido . "','" . $this->correo . "', '" . $this->clave . "','" . $this->contacto . "','" . $this->foto . "')";
    }
    public function actualizar(){
        return "UPDATE Dueño SET
                Nombre = '" . $this->nombre . "',
                Apellido = '" . $this->apellido . "',
                Correo = '" . $this->correo . "',
                Clave = '" . $this->clave . "',
                Contacto = '" . $this->contacto . "',
                Foto = '" . $this->foto . "'
            WHERE idDueño = " . $this->id;
    }
    
    public function eliminar() {
        return "DELETE FROM Dueño WHERE idDueño = " . $this->id;
    }
    public function eliminarPerros() {
        return "DELETE FROM Perro WHERE Dueño_idDueño = " . $this->id;
    }
    

public function correoExiste() {
    return "SELECT idDueño FROM dueño WHERE Correo = '{$this->correo}'";
}

public function consultar() {
    return "SELECT nombre, apellido, correo, clave, contacto, foto
            FROM Dueño
            WHERE idDueño = " . $this->id;
}

} 