<?php
$id = $_SESSION["id"];
$rol = $_SESSION["rol"];
?>
<body>
<?php 
include("presentacion/encabezadoD.php"); 
include("presentacion/menu" . ucfirst($rol) . ".php"); 
$paseador = new Paseador();
$lista = $paseador->consultarActivos(); 
?>

<div class="container mt-4">
    <h3 class="text-center">Paseadores Disponibles</h3>
    <div class="row">
        <?php
        foreach ($lista as $p) {
        ?>
            <div class="col-md-4 mb-4">
                <div class="card h-100 shadow-sm">
                    <?php
                    if ($p->getFoto() != "" && file_exists($p->getFoto())) {
                        echo "<img src='" . $p->getFoto() . "' class='card-img-top' style='height: 250px; object-fit: cover;'>";
                    } else {
                        echo "<div class='card-img-top bg-secondary text-white text-center py-5'>Sin Foto</div>";
                    }
                    ?>
                    <div class="card-body">
                        <h5 class="card-title"><?php echo htmlspecialchars($p->getNombre() . " " . $p->getApellido()); ?></h5>
                         <p><strong>Estado:</strong> 
                            <?php 
                            echo ($p->getActivo() == 1) ? '<span class="badge bg-success">Activo</span>' : '<span class="badge bg-danger">Inactivo</span>'; 
                            ?>
                        </p>
                        <p><strong>Correo:</strong> <?php echo htmlspecialchars($p->getCorreo()); ?></p>
                        <p><strong>Contacto:</strong> <?php echo htmlspecialchars($p->getContacto()); ?></p>
                        <p><strong>Descripción:</strong> <?php echo htmlspecialchars($p->getInformacion()); ?></p>
                         <?php
                        $tarifas = $p->getTarifas();
                        if (!empty($tarifas)) {
                            echo "<p><strong>Tarifas por hora:</strong></p><ul>";
                            foreach ($tarifas as $tarifa) {
                                echo "<li>" . htmlspecialchars($tarifa->getNombreTamaño()) . ": $" . htmlspecialchars(number_format($tarifa->getPrecioHora(), 2, ',', '.')) . "</li>";
                            }
                            echo "</ul>";
                        } else {
                            echo "<p><strong>Tarifas:</strong> No especificadas</p>";
                        }
                        ?>
                    </div>
                </div>
            </div>
        <?php
        }
        ?>
    </div>
</div>

</body>
</html>
