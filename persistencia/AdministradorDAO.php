<?php
class AdministradorDAO{
    private String $id;
    private String $nombre;
    private String $apellido;
    private String $correo;
    private String $clave;

    public function __construct(String $id = "", String $nombre = "", String $apellido = "", String $correo = "", String $clave = "", String $codigoRecuperacion = "", String $fechaExpiracion = ""){
        $this->id = $id;
        $this->nombre = $nombre;
        $this->apellido = $apellido;
        $this->correo = $correo;
        $this->clave = $clave;
    }

   public function autenticarse(){
    return "SELECT idAdmin
            FROM admin 
            WHERE Correo = '" . $this->correo . "' 
              AND Clave = '" . md5($this->clave) . "'";
}

public function consultar(){
    return "SELECT Nombre, Apellido, Correo
            FROM admin
            WHERE idAdmin = '" . $this->id . "'";
}
public function actualizar(){
    return "UPDATE admin SET
            Nombre = '" . $this->nombre . "',
            Apellido = '" . $this->apellido . "',
            Correo = '" . $this->correo . "',
            Clave = '" . $this->clave . "'
            WHERE idAdmin = " . $this->id;
}
} 