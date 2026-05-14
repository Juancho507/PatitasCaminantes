<?php
$id = $_SESSION["id"];
$rol = $_SESSION["rol"];
?>
<body>
<?php 
include("presentacion/encabezadoD.php"); 
include("presentacion/menu" . ucfirst($rol) . ".php"); 
$perro = new Perro(); 
$perros = $perro->consultar($rol, $id); 
?>
<div class="container">
    <div class="row mt-4">
        <div class="col">
            <div class="card">
                <div class="card-header"><h4>Listado de Perros</h4></div>
                <div class="card-body">
                    <?php
                    $cantidadPerros = count($perros);
                    echo "<strong>" . $cantidadPerros . " perro" . ($cantidadPerros !== 1 ? "s" : "") . " encontrado" . ($cantidadPerros !== 1 ? "s" : "") . "</strong><br>";    
                    
                    if (empty($perros)) {
                        echo "<div class='alert alert-warning mt-3'>No se encontraron perros para mostrar.</div>";
                    } else {
                        echo "<table class='table table-striped table-hover mt-3'>";
                        echo "<thead><tr><th>Id</th><th>Foto</th><th>Nombre</th><th>Raza</th><th>Tamaño</th><th>Acción</th></tr></thead>";
                        echo "<tbody>";
                        foreach($perros as $perroItem){
                            echo "<tr>";
                            echo "<td class='align-middle py-3'>" . htmlspecialchars($perroItem->getId()) . "</td>"; 

                            echo "<td class='align-middle py-3'>";
                            echo "<div class='d-flex flex-column align-items-start'>";
                            if ($perroItem->getFoto() != "") {
                                echo "<img src='" . $perroItem->getFoto() . "' width=100' height='100' class='rounded-circle me-3' style='object-fit:cover;' />";                        
                            } else {
                                echo "<div class='text-muted mb-1'>No hay foto</div>";
                            }
                            echo "<td class='align-middle py-3'>" . htmlspecialchars($perroItem->getNombre()) . "</td>"; 
                            echo "<td class='align-middle py-3'>" . htmlspecialchars($perroItem->getRaza()) . "</td>"; 
                            echo "<td class='align-middle py-3'>" . htmlspecialchars($perroItem->getTamaño()) . "</td>"; 
                            echo "<td class='align-middle py-3'>"; 
                            echo "<a href='?pid=" . base64_encode("presentacion/perro/editarPerro.php") . "&id=" . htmlspecialchars($perroItem->getId()) . "' class='btn btn-warning btn-sm' style='margin-right: 10px;' >Editar</a>";
                            echo "<a href='?pid=" . base64_encode("presentacion/perro/eliminarPerro.php") . "&id=" . htmlspecialchars($perroItem->getId()) . "' class='btn btn-danger btn-sm' onclick='return confirm(\"¿Estás seguro de que deseas eliminar este perro?\")'>Eliminar</a> ";   
                            echo "</td>";
                            echo "</tr>";
                        }
                        echo "</tbody>";
                        echo "</table>";
                    }
                    ?>
                </div>
            </div>
        </div>
    </div>
</div>
</body>
</html>
