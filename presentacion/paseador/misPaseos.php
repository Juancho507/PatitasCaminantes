<?php
$id = $_SESSION["id"];
$rol = $_SESSION["rol"];
?>
<body>
<?php
include("presentacion/encabezadoP.php");
include("presentacion/menu" . ucfirst($rol) . ".php");

$paseo = new Paseo();
$misPaseos = $paseo->consultarRealizadosPorPaseador($id);
?>

<div class="container mt-4">
  <h2>Paseos Completados</h2>

  <?php if (empty($misPaseos)) { ?>
    <div class="alert alert-info">No has realizado ningún paseo todavía.</div>
  <?php } else { ?>
    <table class="table table-bordered table-striped">
      <thead class="table-success">
        <tr>
          <th>Fecha de inicio</th>
          <th>Fecha de fin</th>
          <th>Perro(s)</th> </tr>
      </thead>
      <tbody>
        <?php foreach ($misPaseos as $p) { ?>
          <tr>
            <td><?php echo $p["fechaInicio"]; ?></td>
            <td><?php echo $p["fechaFin"]; ?></td>
            <td><?php echo $p["perros"]; ?></td> </tr>
        <?php } ?>
      </tbody>
    </table>
  <?php } ?>
</div>