<?php
include(__DIR__ . "/../../presentacion/encabezadoP.php");
include(__DIR__ . "/../../presentacion/menuPaseador.php");
require_once(__DIR__ . "/../../logica/Estadistica.php");

?>
<div class="container mt-4">
<h2 class="text-center">🐕 Tamaños de Perros Registrados</h2>
<div class="container mt-5">
    <?php include(__DIR__ . "/../estadisticas/graficaTamaños.php");?>
</div>
</div>
