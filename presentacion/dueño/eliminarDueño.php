<?php
if (!isset($_SESSION)) {
    session_start();
}
if (!isset($_SESSION["rol"]) || $_SESSION["rol"] != "dueño") {
    header("Location: ?pid=" . base64_encode("presentacion/noAutorizado.php"));
    exit();
}
$id = $_SESSION["id"];
$dueño = new Dueño($id);
$dueño->consultar();
if ($dueño->getFoto() != "") {
    $rutaFoto = __DIR__ . "/../../" . $dueño->getFoto();
    if (file_exists($rutaFoto)) {
        unlink($rutaFoto);
    }
}
$perro = new Perro();
$perrosDelDueño = $perro->consultarPorDueño($id);
foreach ($perrosDelDueño as $p) {
    $p->eliminarPaseos();
    if ($p->getFoto() != "") {
        $rutaFotoPerro = __DIR__ . "/../../" . $p->getFoto();
        if (file_exists($rutaFotoPerro)) {
            unlink($rutaFotoPerro);
        }
    }
}

$dueño->eliminar();
session_destroy();
header("Location: ?pid=" . base64_encode("presentacion/autenticarse.php") . "&eliminado=1");
exit();
?>
