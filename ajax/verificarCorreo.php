<?php
header('Content-Type: application/json');
require_once(__DIR__ . "/../logica/Paseador.php");
require_once(__DIR__ . "/../logica/Dueño.php");

$correo = isset($_GET["correo"]) ? trim($_GET["correo"]) : "";
$tipo = isset($_GET["tipo"]) ? trim($_GET["tipo"]) : "";

if (empty($correo) || empty($tipo)) {
    echo json_encode(["existe" => false]);
    exit;
}

$existe = false;

if ($tipo === "dueño") {
    $dueño = new Dueño("", "", "", $correo);
    $existe = $dueño->correoExiste();
} elseif ($tipo === "paseador") {
    $paseador = new Paseador("", "", "", $correo);
    $existe = $paseador->correoExiste();
}

echo json_encode(["existe" => $existe]);
