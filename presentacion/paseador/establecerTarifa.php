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
    $tarifasMap[$tarifa->getTamañoIdTamaño()] = $tarifa;
}
$tamañoObj = new Tamaño(); 
$todosTamanos = $tamañoObj->consultarTodos(); 
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST["nuevoPrecio"])) {
    $tarifasActualizadas = false;
    foreach ($todosTamanos as $tamano) {
        $idTamano = $tamano->getId(); 
        $nuevoPrecio = isset($_POST["nuevoPrecio"][$idTamano]) ? intval($_POST["nuevoPrecio"][$idTamano]) : 0;
        $precioActual = 0; 
        if (isset($tarifasMap[$idTamano])) {
            $precioActual = intval($tarifasMap[$idTamano]->getPrecioHora());
        }
        if ($nuevoPrecio > 0 && $nuevoPrecio != $precioActual) {
            $tarifa = new Tarifa("", $nuevoPrecio, $id, $idTamano);
            $tarifa->desactivarAnterior();
            $tarifa->insertarNueva();

            $tarifasActualizadas = true; 
        }
    }
    $paseador->consultar();
    $tarifasExistentes = $paseador->getTarifas();
    $tarifasMap = [];
    foreach ($tarifasExistentes as $tarifa) {
        $tarifasMap[$tarifa->getTamañoIdTamaño()] = $tarifa;
    }
    if ($tarifasActualizadas) {
        echo "<div class='alert alert-success text-center mt-4'>¡Tarifas actualizadas correctamente!</div>";
    }
}
?>

<div class="container mt-4">
  <h2>Mis Tarifas por Tamaño de Perro</h2>
  <p class="text-muted">Si deseas modificar alguna tarifa, escribe el nuevo precio y pulsa <strong>Actualizar tarifas</strong>.</p>

  <form method="post">
    <table class="table table-bordered table-hover">
      <thead class="table-info">
        <tr>
          <th>Tamaño del perro</th>
          <th>Precio actual (COP)</th>
          <th>Nuevo precio</th>
        </tr>
      </thead>
      <tbody>
        <?php
        foreach ($todosTamanos as $tamano) {
            if ($tamano->getId() == 5) continue; 
            $idTamano = $tamano->getId(); 
            $nombreTamano = $tamano->getTipo(); 
            $precioActual = 0; 
            if (isset($tarifasMap[$idTamano])) {
                $precioActual = $tarifasMap[$idTamano]->getPrecioHora();
            }
        ?>
            <tr>
              <td><?php echo $nombreTamano; ?></td>
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
                <input type="number" class="form-control" name="nuevoPrecio[<?php echo $idTamano; ?>]" min="0" placeholder="Ej: 8000">
                <input type="hidden" name="precioActual[<?php echo $idTamano; ?>]" value="<?php echo $precioActual; ?>">
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