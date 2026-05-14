<?php

$idDueño = $_SESSION["id"];
$rol = $_SESSION["rol"];

include("presentacion/encabezadoD.php");
include("presentacion/menu" . ucfirst($rol) . ".php");

$perro = new Perro();
$listaPerros = $perro->consultarPorDueño($idDueño);
?>

<div class="container mt-4">
    <h3 class="mb-4">Facturas de Paseos por Perrito</h3>
    <div class="row">
        <?php
        if (empty($listaPerros)) {
            echo "<div class='alert alert-warning'>No tienes perritos registrados.</div>";
        } else {
            foreach ($listaPerros as $perrito) {
                echo "<div class='col-md-4 mb-4'>";
                echo "  <div class='card h-100'>";
                echo "    <div class='card-body text-center'>";
                if ($perrito->getFoto()) {
                    echo "<img src='" . $perrito->getFoto() . "' width=100' height='100' class='rounded-circle me-3' style='object-fit:cover;' />"; 
                }

                echo "<h5 class='card-title'>" . htmlspecialchars($perrito->getNombre()) . "</h5>";
                echo "<a href='?pid=" . base64_encode("presentacion/paseo/facturasPaseo.php") . "&idPerro=" . $perrito->getId() . "' class='btn btn-primary mt-2'>";
                echo "<i class='fa-solid fa-file-invoice-dollar'></i> Ver facturas de este perrito";
                echo "</a>";

                echo "    </div>";
                echo "  </div>";
                echo "</div>";
            }
        }
        ?>
    </div>
</div>
