<?php
require_once(__DIR__ . "/../../logica/Estadistica.php");
$estadistica = new Estadistica();
$datos = $estadistica->promedioPreciosPorTamaño();
?>

<script type="text/javascript" src="https://www.gstatic.com/charts/loader.js"></script>
<script>
google.charts.load('current', {'packages':['corechart']});
google.charts.setOnLoadCallback(drawChart);
function drawChart() {
    var data = google.visualization.arrayToDataTable([
        ['Tamaño', 'Precio Promedio'],
        <?php foreach ($datos as $d): ?>
            ['<?= $d[0] ?>', <?= $d[1] ?>],
        <?php endforeach; ?>
    ]);

    var options = {
        title: 'Precios promedio por tamaño',
        curveType: 'function',
        legend: { position: 'bottom' }
    };

    var chart = new google.visualization.LineChart(document.getElementById('graficaPrecios'));
    chart.draw(data, options);
}
</script>

<div id="graficaPrecios" style="width: 100%; height: 400px;"></div>
