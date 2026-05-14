<?php
include(__DIR__ . "/../../presentacion/encabezadoD.php");
include(__DIR__ . "/../../presentacion/menuDueño.php");
require_once(__DIR__ . "/../../logica/Estadistica.php");

?>
<div class="container mt-4">
<h2 class="text-center">💲 Precios Promedio por Tamaño</h2>
<div class="container mt-4">
    <?php include(__DIR__ . "/../estadisticas/graficaPrecios.php");?>
</div>
</div>