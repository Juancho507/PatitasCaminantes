<?php

if ($_SESSION["rol"] != "dueño") {
    header("Location: ?pid=" . base64_encode("presentacion/noAutorizado.php"));
    exit();
}
$error = 0;
$id = $_GET["id"];
$perro = new Perro();
$perro = $perro->consultarPerroPorId($id);
if (isset($_POST["editar"])) {
    $nombre = $_POST["nombre"];
    $raza = $_POST["raza"];
    $foto = $_FILES["foto"]["name"];
    $tam = $_FILES["foto"]["size"];
    $rutaLocal = $_FILES["foto"]["tmp_name"];
    
    $rutaServidor = $perro->getFoto();
    
    if ($foto != "") {
        $nuevoNombre = time() . ".png";
        $rutaServidor = "imagenes/" . $nuevoNombre;
        if (copy($rutaLocal, $rutaServidor)) {
            if ($perro->getFoto() != "") {
                $rutaFoto = __DIR__ . "/../../" . $perro->getFoto(); 
                if (file_exists($rutaFoto)) {
                    unlink($rutaFoto);
                }
            }
        } else {
            $error = 1;
        }
    }
    
    
    
    if ($error == 0) {
        $perroActualizado = new Perro($id, $nombre, $rutaServidor, $raza, $_SESSION["id"]);
        $perroActualizado->actualizar(); 
        $perro = $perroActualizado;
    }
}
?>
<body>
<?php
include("presentacion/encabezadoD.php");
include("presentacion/menuDueño.php");
?>
<div class="container mt-4">
    <div class="row justify-content-center">
        <div class="col-md-6">
            <div class="card mb-4"> 
                <div class="card-header bg-warning text-dark">
                    <h4>Editar Perro</h4>
                </div>
                <div class="card-body">
                    <?php
                    if (isset($_POST["editar"]) && $error == 0) {
                        echo "<div class='alert alert-success'>Datos actualizados correctamente.</div>";
                    } elseif (isset($_POST["editar"]) && $error == 1) {
                        echo "<div class='alert alert-danger'>Error al actualizar la foto.</div>";
                    }
                    ?>
                    <form method="post" enctype="multipart/form-data">
                        <div class="mb-3">
                            <label class="form-label">Nombre</label>
                            <input type="text" name="nombre" class="form-control" value="<?php echo htmlspecialchars($perro->getNombre()); ?>" required>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Raza</label>
                            <select name="raza" class="form-control" required>
                                <?php
                                $raza = new Raza();
                                $razas = $raza->consultarTodos(); 
                                foreach ($razas as $r) {
                                    $selected = ($r->getNombre() == $perro->getRaza()) ? "selected" : "";
                                    echo "<option value='" . $r->getId() . "' $selected>" . $r->getNombre() . "</option>";
                                }
                                ?>
                            </select>
                        </div>
                        <div class="mb-3 text-center">
                            <?php
                            if ($perro->getFoto() != "" && file_exists($perro->getFoto())) {
                                echo "<img src='" . $perro->getFoto() . "' height='150' class='rounded-circle mb-2' />";
                            } else {
                                echo "<p>No hay foto actual.</p>";
                            }
                            ?>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Foto Nueva</label>
                            <input type="file" name="foto" class="form-control">
                        </div>
                        <button type="submit" name="editar" class="btn btn-warning">Guardar Cambios</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
</body>

