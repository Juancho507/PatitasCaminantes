<?php
session_start();
require ("logica/Administrador.php");
require ("logica/Paseador.php");
require ("logica/Dueño.php");
require ("logica/Perro.php");
require ("logica/Tamaño.php");
require ("logica/Raza.php");
require ("logica/Paseo.php");
require ("logica/EstadoPaseo.php");
require ("logica/Tarifa.php");

?>
<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>DoggyToons</title>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
<link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet">
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/jquery@3.7.1/dist/jquery.min.js"></script>
<link href="https://use.fontawesome.com/releases/v5.11.1/css/all.css" rel="stylesheet" />
<script src="https://kit.fontawesome.com/14596e32cc.js" crossorigin="anonymous"></script>
<script type="text/javascript" src="https://www.gstatic.com/charts/loader.js"></script>
<link rel="shortcut icon" href="img/Logo.png" type="image/x-icon">
</head>

<?php
$paginas_sin_autenticacion = array(
    "presentacion/autenticarse.php",
    "presentacion/dueño/nuevodueño.php",
    );

$paginas_con_autenticacion = array(
    "presentacion/estadisticas.php",
    "presentacion/paseador/gestionarPaseadores.php",
    "presentacion/paseador/graficaTamaños.php",
    "presentacion/dueño/graficaPrecios.php",
    "presentacion/paseador/registrarPaseador.php",
    "presentacion/sesionAdministrador.php",
    "presentacion/sesionPaseador.php",
    "presentacion/sesionDueño.php",
    "presentacion/perro/consultarPerros.php",
    "presentacion/paseo/historialPaseosd.php",
    "presentacion/paseo/historialPaseosp.php",
    "presentacion/perro/registrarPerro.php",
    "presentacion/perro/eliminarPerro.php",
    "presentacion/perro/editarPerro.php",
    "presentacion/dueño/editarDueño.php",
    "presentacion/editarAdministrador.php",
    "presentacion/paseo/solicitarPaseo.php",
    "presentacion/dueño/eliminarDueño.php",
    "presentacion/paseador/consultarPaseadores.php",
    "presentacion/paseador/editarPaseador.php",
    "presentacion/paseador/establecerTarifa.php",
    "presentacion/paseador/misPaseos.php",
    "presentacion/paseo/paseosPendientes.php",
    "presentacion/paseo/facturasPaseo.php",
    "presentacion/paseo/verFacturas.php",
    "presentacion/administrador/aceptarTamañosPerritos.php",
);

if (!isset($_GET["pid"])) {
    include("presentacion/autenticarse.php"); 
} else {
    $pid = base64_decode($_GET["pid"]);
    
    if (in_array($pid, $paginas_sin_autenticacion)) {
        include $pid;
    } else if (in_array($pid, $paginas_con_autenticacion)) {
        if (!isset($_SESSION["id"])) {
            include "presentacion/autenticarse.php";
        } else {
            include $pid;
        }
    } else {
        echo "<div class='container mt-5'><h1>Error 404 - Página no encontrada</h1></div>";
    }
}
?>
</html>
