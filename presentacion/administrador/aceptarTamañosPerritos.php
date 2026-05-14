<?php
$id = $_SESSION["id"];
$rol = $_SESSION["rol"];

include("presentacion/encabezadoA.php");
include("presentacion/menu" . ucfirst($rol) . ".php");

$raza = new Raza();
$razas = $raza->consultarTodosConTamaño();

$tamanio = new Tamaño();
$tamaños = $tamanio->consultarTodos();
?>

<div class="container mt-4">
    <h2>Administrar Razas de Perritos</h2>

    <?php if (empty($razas)) { ?>
        <div class="alert alert-info">No hay razas registradas.</div>
    <?php } else { ?>
        <table class="table table-bordered table-hover">
            <thead class="table-dark">
                <tr>
                    <th>Nombre de la Raza</th>
                    <th>Asignar Tamaño</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($razas as $r): 
                    $uniqueId = $r->getId(); ?>
                    <tr>
                        <td><?php echo htmlspecialchars($r->getNombre()); ?></td>
                        <td id="botonesTamaño<?php echo $uniqueId; ?>">
                            <?php if ($r->getTamaño() == null || strtolower($r->getTamaño()) == "pendiente") { ?>
    	                           <?php foreach ($tamaños as $t):
                                        if ($t->getId() == 5) continue; 
                                                     ?>
   									 <button class="btn btn-outline-primary btn-sm me-2"
        							id="btnTamaño<?php echo $t->getId() . '_' . $r->getId(); ?>">
       							 <?php echo $t->getTipo(); ?>
  										  </button>
										<?php endforeach; ?>

                            <?php } else { ?>
                                <span class="badge bg-secondary"><?php echo htmlspecialchars($r->getTamaño()); ?></span>
                            <?php } ?>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    <?php } ?>
</div>

<script>
$(document).ready(function(){
<?php foreach ($razas as $r): 
    $uniqueId = $r->getId();
    if ($r->getTamaño() == null || strtolower($r->getTamaño()) == "pendiente") {
        foreach ($tamaños as $t) {
            echo '$("#btnTamaño' . $t->getId() . '_' . $uniqueId . '").click(function(){' . "\n";
            echo '  $.ajax({' . "\n";
            echo '    url: "ajax/asignarTamañoRaza.php",' . "\n";
            echo '    method: "POST",' . "\n";
            echo '    data: { idRaza: "' . $uniqueId . '", idTamaño: "' . $t->getId() . '" },' . "\n";
            echo '    success: function(response) {' . "\n";
            echo '      $("#botonesTamaño' . $uniqueId . '").html("<span class=\'badge bg-success\'>Tamaño asignado</span>");' . "\n";
            echo '    }' . "\n";
            echo '  });' . "\n";
            echo '});' . "\n";
        }
    }
endforeach; ?>
});
</script>

