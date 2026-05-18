<?php
require_once(__DIR__ . "/../logica/Disponibilidad.php");

header('Content-Type: application/json');

$id = isset($_POST["id"]) ? (int)$_POST["id"] : 0;

if ($id <= 0) {
    echo json_encode(["exito" => false, "mensaje" => "ID inválido."]);
    exit;
}

$d = new Disponibilidad($id);
$d->eliminar();

echo json_encode(["exito" => true, "mensaje" => "Disponibilidad eliminada."]);
