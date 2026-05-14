<?php
class PaseadorDAO{
    private $id;
    private $nombre;
    private $apellido;
    private $correo;
    private $clave;
    private $contacto;
    private $foto;
    private $informacion;
    private $activo; 
    
    public function __construct($id = "", $nombre = "", $apellido = "", $correo = "", $clave = "", $contacto = "", $foto = "", $informacion = "", $activo = ""){
        $this->id = $id;
        $this->nombre = $nombre;
        $this->apellido = $apellido;
        $this->correo = $correo;
        $this->clave = $clave;
        $this->contacto = $contacto;
        $this->foto = $foto;
        $this->informacion = $informacion;
        $this->activo = $activo;
    }

   public function autenticarse(){
    return "SELECT idPaseador
            FROM paseador 
            WHERE Correo = '" . $this->correo . "' 
              AND Clave = '" . md5($this->clave) . "'";
}

public function registrar() {
        return "INSERT INTO Paseador (Nombre, Apellido, Correo, Clave, Contacto, Foto, Informacion, Activo, Admin_idAdmin)
                VALUES (
                    '{$this->nombre}', '{$this->apellido}', '{$this->correo}', '{$this->clave}',
                    '{$this->contacto}', '{$this->foto}', '{$this->informacion}', 1, '{$_SESSION['id']}'
                )";
    }
public function consultar(){
    return "SELECT Nombre, Apellido, Correo, Clave, Contacto, Foto, Informacion, Activo
            FROM Paseador
            WHERE idPaseador = '" . $this->id . "'";
}

public function consultarTodos() {
    return "SELECT idPaseador, nombre, apellido, correo, contacto, activo FROM Paseador";
}

public function consultarActivos() {
    return "SELECT idPaseador, Nombre, Apellido, Correo, Contacto, Foto, Informacion, Activo
            FROM Paseador
            WHERE Activo = 1";
}

public function actualizar(){
    return "UPDATE Paseador SET
        Nombre = '" . $this->nombre . "',
        Apellido = '" . $this->apellido . "',
        Correo = '" . $this->correo . "',
        Clave = '" . $this->clave . "',
        Contacto = '" . $this->contacto . "',
        Foto = '" . $this->foto . "',
        Informacion = '" . $this->informacion . "'
        WHERE idPaseador = " . $this->id;
}

public function actualizarEstado() {
    return "UPDATE Paseador SET Activo = " . $this->activo . " WHERE idPaseador = " . $this->id;
}


public function correoExiste() {
    return "SELECT idPaseador FROM Paseador WHERE Correo = '" . $this->correo . "'";
}
    
} 