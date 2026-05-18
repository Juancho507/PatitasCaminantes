<?php
class DueñoDAO{
    private $id;
    private $nombre;
    private $apellido;
    private $correo;
    private $clave;
    private $contacto;
    private $foto;
    private $nroDocumento;
    private $direccion;
    private $localidad;
    private $adminId;

    public function __construct($id = "" , $nombre = "", $apellido = "", $correo = "", $clave = "", $contacto = "", $foto = "", $nroDocumento = "", $direccion = "", $localidad = 0, $adminId = 1) {
    $this->id = $id;
    $this->nombre = $nombre;
    $this->apellido = $apellido;
    $this->correo = $correo;
    $this->clave = $clave;
    $this->contacto = $contacto;
    $this->foto = $foto;
    $this->nroDocumento = $nroDocumento;
    $this->direccion = $direccion;
    $this->localidad = $localidad;
    $this->adminId = $adminId;
}


    public function autenticarse() {
        return "SELECT idDueño
                FROM Dueño
                WHERE Correo = '" . $this -> correo . "' AND Clave = '" . md5($this -> clave) . "' AND Activo = 1";
    }

    public function registrar() {
        return "INSERT INTO Dueño (NroDocumento, Nombre, Apellido, Correo, Clave, Contacto, Activo, Direccion, Foto, Localidad_idLocalidad, admin_idAdmin)
                VALUES ('" . $this->nroDocumento . "','" . $this->nombre . "','" . $this->apellido . "','" . $this->correo . "', '" . $this->clave . "','" . $this->contacto . "', 1,'" . $this->direccion . "','" . $this->foto . "', " . $this->localidad . ", " . $this->adminId . ")";
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
    return "SELECT idDueño FROM Dueño WHERE Correo = '" . $this->correo . "'";
}

public function consultar() {
    return "SELECT Nombre, Apellido, Correo, Clave, Contacto, Foto
            FROM Dueño
            WHERE idDueño = " . $this->id;
}

} 