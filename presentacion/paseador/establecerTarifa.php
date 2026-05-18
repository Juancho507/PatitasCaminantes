<?php
$id = $_SESSION["id"];
$rol = $_SESSION["rol"];
?>
<body>
<?php
include("presentacion/encabezadoP.php");
include("presentacion/menu" . ucfirst($rol) . ".php");

$paseador = new Paseador($id);
$paseador->consultar();
$tarifasExistentes = $paseador->getTarifas();
$tarifasMap = [];
foreach ($tarifasExistentes as $tarifa) {
    $tarifasMap[$tarifa->getPeligrosidadIdPeligrosidad()] = $tarifa;
}
$peligrosidadObj = new Peligrosidad(); 
$todosNiveles = $peligrosidadObj->consultarTodos(); 
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST["nuevoPrecio"])) {
    $tarifasActualizadas = false;
    foreach ($todosNiveles as $nivel) {
        $idNivel = $nivel->getId(); 
        $nuevoPrecio = isset($_POST["nuevoPrecio"][$idNivel]) ? intval($_POST["nuevoPrecio"][$idNivel]) : 0;
        $precioActual = 0; 
        if (isset($tarifasMap[$idNivel])) {
            $precioActual = intval($tarifasMap[$idNivel]->getPrecioHora());
        }
        if ($nuevoPrecio > 0 && $nuevoPrecio != $precioActual) {
            $tarifa = new Tarifa("", $nuevoPrecio, $id, $idNivel);
            $tarifa->desactivarAnterior();
            $tarifa->insertarNueva();

            $tarifasActualizadas = true; 
        }
    }
    $paseador->consultar();
    $tarifasExistentes = $paseador->getTarifas();
    $tarifasMap = [];
    foreach ($tarifasExistentes as $tarifa) {
        $tarifasMap[$tarifa->getPeligrosidadIdPeligrosidad()] = $tarifa;
    }
    if ($tarifasActualizadas) {
        echo "<div class='alert alert-success text-center mt-4'>¡Tarifas actualizadas correctamente!</div>";
    }
}
?>

<div class="container mt-4">
  <h2>Mis Tarifas por Nivel de Peligrosidad</h2>
  <p class="text-muted">Si deseas modificar alguna tarifa, escribe el nuevo precio y pulsa <strong>Actualizar tarifas</strong>.</p>

  <form method="post">
    <table class="table table-bordered table-hover">
      <thead class="table-info">
        <tr>
          <th>Nivel de Peligrosidad</th>
          <th>Precio actual (COP)</th>
          <th>Nuevo precio</th>
        </tr>
      </thead>
      <tbody>
        <?php
        foreach ($todosNiveles as $nivel) {
            $idNivel = $nivel->getId(); 
            $nombreNivel = $nivel->getNivel(); 
            $precioActual = 0; 
            if (isset($tarifasMap[$idNivel])) {
                $precioActual = $tarifasMap[$idNivel]->getPrecioHora();
            }
        ?>
            <tr>
              <td><?php echo $nombreNivel; ?></td>
              <td>
                <?php
                if ($precioActual > 0) { 
                    echo '$' . number_format($precioActual, 0, ',', '.');
                } else { 
                    echo 'Aún no asignado';
                }
                ?>
              </td>
              <td>
                <input type="number" class="form-control" name="nuevoPrecio[<?php echo $idNivel; ?>]" min="0" placeholder="Ej: 8000">
                <input type="hidden" name="precioActual[<?php echo $idNivel; ?>]" value="<?php echo $precioActual; ?>">
              </td>
            </tr>
        <?php }  ?>
      </tbody>
    </table>
    <div class="text-center">
      <button type="submit" class="btn btn-success">Actualizar tarifas</button>
    </div>
  </form>
</div>

</body>
</html>