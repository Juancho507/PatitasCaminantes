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
    private $estadoId;
    private $nroDocumento;
    private $fechaNacimiento;
    private $localidad_id;
    private $hojaDeVida;
    private $certificado;

    public function __construct($id = "", $nombre = "", $apellido = "", $correo = "", $clave = "", $contacto = "", $foto = "", $informacion = "", $estadoId = 2, $nroDocumento = "", $fechaNacimiento = "", $localidad_id = 0, $hojaDeVida = "", $certificado = ""){
        $this->id = $id;
        $this->nombre = $nombre;
        $this->apellido = $apellido;
        $this->correo = $correo;
        $this->clave = $clave;
        $this->contacto = $contacto;
        $this->foto = $foto;
        $this->informacion = $informacion;
        $this->estadoId = $estadoId;
        $this->nroDocumento = $nroDocumento;
        $this->fechaNacimiento = $fechaNacimiento;
        $this->localidad_id = $localidad_id;
        $this->hojaDeVida = $hojaDeVida;
        $this->certificado = $certificado;
    }

   public function autenticarse(){
    return "SELECT idPaseador
            FROM paseador 
            WHERE Correo = '" . $this->correo . "' 
              AND Clave = '" . md5($this->clave) . "'";
}

public function registrar() {
        $nroDoc = $this->nroDocumento !== "" ? "'" . $this->nroDocumento . "'" : "NULL";
        $fechaNac = $this->fechaNacimiento !== "" ? "'" . $this->fechaNacimiento . "'" : "NULL";
        $localidad = $this->localidad_id > 0 ? $this->localidad_id : "NULL";
        $hv = $this->hojaDeVida !== "" ? "'" . $this->hojaDeVida . "'" : "NULL";
        $cert = $this->certificado !== "" ? "'" . $this->certificado . "'" : "NULL";
        $estado = (int)$this->estadoId;

        return "INSERT INTO Paseador (Nombre, Apellido, NroDocumento, FechaNacimiento, Correo, Clave, Contacto, Foto, Informacion, Estado_idEstado, Admin_idAdmin, Localidad_idLocalidad, HojaDeVida, Certificados)
                VALUES (
                    '" . $this->nombre . "', '" . $this->apellido . "', $nroDoc, $fechaNac, '" . $this->correo . "', '" . $this->clave . "',
                    '" . $this->contacto . "', '" . $this->foto . "', '" . $this->informacion . "', $estado, 1, $localidad, $hv, $cert
                )";
    }
public function consultar(){
    return "SELECT Nombre, Apellido, Correo, Clave, Contacto, Foto, Informacion, Estado_idEstado, NroDocumento, FechaNacimiento
            FROM Paseador
            WHERE idPaseador = '" . $this->id . "'";
}

public function consultarTodos() {
    return "SELECT idPaseador, nombre, apellido, correo, contacto, Estado_idEstado FROM Paseador";
}

public function consultarActivos() {
    return "SELECT idPaseador, Nombre, Apellido, Correo, Contacto, Foto, Informacion, Estado_idEstado
            FROM Paseador
            WHERE Estado_idEstado = 2";
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
    return "UPDATE Paseador SET Estado_idEstado = " . $this->estadoId . " WHERE idPaseador = " . $this->id;
}

public function correoExiste() {
    return "SELECT idPaseador FROM Paseador WHERE Correo = '" . $this->correo . "'";
}

public function documentoExiste() {
    return "SELECT idPaseador FROM Paseador WHERE NroDocumento = '" . $this->nroDocumento . "'";
}

}
?>
