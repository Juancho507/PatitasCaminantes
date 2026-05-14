<?php
require_once(__DIR__ . "/../logica/Paseo.php");
require_once(__DIR__ . "/../logica/EstadoPaseo.php");

$id = $_GET["id"];
$nuevoEstado = $_GET["estado"];

$paseo = new Paseo($id);

// ✅ Lógica para prevenir más de 2 paseos aceptados en 1 hora
if ($nuevoEstado == 2 && !$paseo->puedeAceptarPaseo()) {
    echo "⚠️ Ya hay 2 perros agendados en esta hora.";
    exit;
}

if ($paseo->actualizarEstado($nuevoEstado)) {
    $estado = new EstadoPaseo($nuevoEstado);
    $estado->consultar(); // Este método debe existir
    echo $estado->getValor();
} else {
    echo "ERROR";
}
?>
