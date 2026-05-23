<?php
require_once(__DIR__ . "/../persistencia/Conexion.php");
require_once(__DIR__ . "/../persistencia/PerroDAO.php");

class Perro{
    private $id;
    private $nombre;
    private $peso;
    private $recomendacion;
    private $activo;
    private $foto;
    private $raza;
    private $dueño;
    private $peligrosidad;
    private $tamaño;

    public function getId(){
        return $this->id;
    }

    public function getNombre(){
        return $this->nombre;
    }

    public function getPeso(){
        return $this->peso;
    }

    public function getRecomendacion(){
        return $this->recomendacion;
    }

    public function getActivo(){
        return $this->activo;
    }

    public function getFoto(){
        return $this->foto;
    }

    public function getRaza(){
        return $this->raza;
    }

    public function getDueño(){
        return $this->dueño;
    }

    public function getPeligrosidad(){
        return $this->peligrosidad;
    }

    public function getTamaño(){
        return $this->tamaño;
    }

    public function setId($id){
        $this->id = $id;
    }

    public function setNombre($nombre){
        $this->nombre = $nombre;
    }

    public function setPeso($peso){
        $this->peso = $peso;
    }

    public function setRecomendacion($recomendacion){
        $this->recomendacion = $recomendacion;
    }

    public function setActivo($activo){
        $this->activo = $activo;
    }

    public function setFoto($foto){
        $this->foto = $foto;
    }

    public function setRaza($raza){
        $this->raza = $raza;
    }

    public function setDueño($dueño){
        $this->dueño = $dueño;
    }

    public function setPeligrosidad($peligrosidad){
        $this->peligrosidad = $peligrosidad;
    }

    public function __construct($id = 0, $nombre = "", $peso = 0, $recomendacion = "", $activo = 1, $foto = "", $raza = null, $dueño = null, $peligrosidad = 0, $tamaño = ""){
        $this->id = $id;
        $this->nombre = $nombre;
        $this->peso = $peso;
        $this->recomendacion = $recomendacion;
        $this->activo = $activo;
        $this->foto = $foto;
        $this->raza = $raza;
        $this->dueño = $dueño;
        $this->peligrosidad = $peligrosidad;
        $this->tamaño = $tamaño;
    }

    public function consultar($rol, $idUsuarioSesion) {
        $listaPerros = [];
        $perroDAO = new PerroDAO();

        if (strtolower($rol) === "dueño") {
            $idDueñoSanitizado = (int) $idUsuarioSesion;
            $sentencia = $perroDAO->consultarPerrosPorDueño($idDueñoSanitizado);
        } else {
            $sentencia = $perroDAO->consultarTodosLosPerros();
        }

        $conexion = new Conexion();
        $conexion->abrir();
        $conexion->ejecutar($sentencia);

        if ($conexion->filas() > 0) {
            while ($registro = $conexion->registro()) {
                $idPerro = $registro[0];
                $nombrePerro = $registro[1];
                $fotoPerro = $registro[2];
                $nombreRaza = $registro[3];
                $nombreDueñoCompleto = $registro[4];
                $nombreTamaño = $registro[5];
                $peso = $registro[6];
                $recomendacion = $registro[7];
                $peligrosidad = $registro[8];
                $activo = $registro[9];
                $listaPerros[] = new Perro($idPerro, $nombrePerro, $peso, $recomendacion, $activo, $fotoPerro, $nombreRaza, $nombreDueñoCompleto, $peligrosidad, $nombreTamaño);
            }
        }
        $conexion->cerrar();
        return $listaPerros;
    }

    public function consultarPerroPorId($idPerro) {
        $idPerroSanitizado = (int)$idPerro;
        $perroDAO = new PerroDAO($idPerroSanitizado);
        $sentencia = $perroDAO->consultarPorId();

        $conexion = new Conexion();
        $conexion->abrir();
        $conexion->ejecutar($sentencia);

        $perroEncontrado = null;
        if ($conexion->filas() > 0) {
            $registro = $conexion->registro();
            $idPerro = $registro[0];
            $nombrePerro = $registro[1];
            $fotoPerro = $registro[2];
            $nombreRaza = $registro[3];
            $nombreDueñoCompleto = $registro[4];
            $nombreTamaño = $registro[5];
            $peso = $registro[6];
            $recomendacion = $registro[7];
            $peligrosidad = $registro[8];
            $activo = $registro[9];

            $perroEncontrado = new Perro($idPerro, $nombrePerro, $peso, $recomendacion, $activo, $fotoPerro, $nombreRaza, $nombreDueñoCompleto, $peligrosidad, $nombreTamaño);
        }
        $conexion->cerrar();
        return $perroEncontrado;
    }

    public function insertar(){
        $conexion = new Conexion();
        $conexion->abrir();
        $razaId = ($this->raza instanceof Raza) ? $this->raza->getId() : (int)$this->raza;
        $dueñoId = ($this->dueño instanceof Dueño) ? $this->dueño->getId() : (int)$this->dueño;
        $perroDAO = new PerroDAO(
            id: 0,
            nombre: $this->nombre,
            peso: $this->peso,
            recomendacion: $this->recomendacion,
            activo: $this->activo,
            foto: $this->foto,
            raza: $razaId,
            dueño: $dueñoId,
            peligrosidad: $this->peligrosidad
        );
        $conexion->ejecutar($perroDAO->insertar());
        $conexion->cerrar();
        return $conexion->getResultado();
    }

    public function eliminar() {
        $perroDAO = new PerroDAO($this->id);
        $conexion = new Conexion();
        $conexion->abrir();
        $conexion->ejecutar($perroDAO->eliminar());
        $conexion->cerrar();
    }

    public function actualizar() {
        $conexion = new Conexion();
        $conexion->abrir();
        $razaId = ($this->raza instanceof Raza) ? $this->raza->getId() : (int)$this->raza;
        $dueñoId = ($this->dueño instanceof Dueño) ? $this->dueño->getId() : (int)$this->dueño;
        $perroDAO = new PerroDAO($this->id, $this->nombre, $this->peso, $this->recomendacion, $this->activo, $this->foto, $razaId, $dueñoId, $this->peligrosidad);
        $conexion->ejecutar($perroDAO->actualizar());
        $conexion->cerrar();
    }

    public function consultarPorDueño($idDueño) {
        $listaPerros = [];
        $perroDAO = new PerroDAO();
        $sentencia = $perroDAO->consultarPerrosPorDueño((int)$idDueño);

        $conexion = new Conexion();
        $conexion->abrir();
        $conexion->ejecutar($sentencia);

        if ($conexion->filas() > 0) {
            while ($registro = $conexion->registro()) {
                $idPerro = $registro[0];
                $nombrePerro = $registro[1];
                $fotoPerro = $registro[2];
                $raza = $registro[3];
                $dueño = $registro[4];
                $tamaño = $registro[5];
                $peso = $registro[6];
                $recomendacion = $registro[7];
                $peligrosidad = $registro[8];
                $activo = $registro[9];

                $listaPerros[] = new Perro($idPerro, $nombrePerro, $peso, $recomendacion, $activo, $fotoPerro, $raza, $dueño, $peligrosidad, $tamaño);
            }
        }

        $conexion->cerrar();
        return $listaPerros;
    }

    public function eliminarPaseos() {
        $conexion = new Conexion();
        $conexion->abrir();
        $id = (int)$this->id;
        $conexion->ejecutar("DELETE FROM Paseo WHERE perro_idPerro = $id OR perro_idPerro2 = $id OR perro_idPerro3 = $id OR perro_idPerro4 = $id OR perro_idPerro5 = $id OR perro_idPerro6 = $id");
        $conexion->cerrar();
    }

    public function tienePaseosActivos() {
        $conexion = new Conexion();
        $conexion->abrir();
        $id = (int)$this->id;
        $conexion->ejecutar("SELECT COUNT(*) FROM Paseo p
                             WHERE (p.perro_idPerro = $id OR p.perro_idPerro2 = $id OR p.perro_idPerro3 = $id OR p.perro_idPerro4 = $id OR p.perro_idPerro5 = $id OR p.perro_idPerro6 = $id)
                             AND p.Estado_idEstado IN (1,2,5)
                             AND p.FechaInicio > NOW()");
        $fila = $conexion->registro();
        $tiene = $fila && $fila[0] > 0;
        $conexion->cerrar();
        return $tiene;
    }
}
?>
