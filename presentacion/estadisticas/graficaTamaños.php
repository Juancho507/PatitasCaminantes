<?php
require_once(__DIR__ . "/../../logica/Estadistica.php");
$estadistica = new Estadistica();
$datos = $estadistica->cantidadPerrosPorTamaño();
?>

<script type="text/javascript" src="https://www.gstatic.com/charts/loader.js"></script>
<script type="text/javascript">
google.charts.load('current', { packages: ['corechart'] });
google.charts.setOnLoadCallback(drawChart);

function drawChart() {
    const data = google.visualization.arrayToDataTable([
        ['Tamaño', 'Cantidad'],
        <?php foreach ($datos as $fila): ?>
            ['<?= $fila[0] ?>', <?= $fila[1] ?>],
        <?php endforeach; ?>
    ]);

    const options = {
        title: 'Cantidad de Perros Registrados por Tamaño',
        pieHole: 0.4,
        colors: ['#f39c12', '#3498db', '#2ecc71', '#e74c3c'],
        chartArea: { width: '90%', height: '80%' }
    };

    const chart = new google.visualization.PieChart(document.getElementById('graficaTamanos'));
    chart.draw(data, options);
}
</script>

<div id="graficaTamanos" style="width: 100%; height: 400px;"></div>
