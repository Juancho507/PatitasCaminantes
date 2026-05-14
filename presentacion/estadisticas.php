<?php
include("encabezadoA.php");
include("menuAdministrador.php");
?>

<div class="container mt-4">
  <h2 class="text-center">📊 Estadísticas Generales</h2>

  <h4 class="mt-4">💲 Precios Promedio por Tamaño</h4>
  <?php include("estadisticas/graficaPrecios.php"); ?>

  <h4 class="mt-5">🐕 Tamaños de Perros Registrados</h4>
  <?php include("estadisticas/graficaTamaños.php"); ?>
</div>
