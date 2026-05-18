<?php
if ($_SESSION["rol"] != "dueño") {
    header("Location: ?pid=" . base64_encode("presentacion/noAutorizado.php"));
    exit();
}

$id = $_GET["id"];
$perro = new Perro();
$perro = $perro->consultarPerroPorId($id);

$razas = new Raza();
$listaRazas = $razas->consultarTodos();
$peligrosidades = new Peligrosidad();
$listaPeligrosidades = $peligrosidades->consultarTodos();

$mensaje = "";
$claseMensaje = "";

if (isset($_POST["editar"])) {
    $nombre = trim($_POST["nombre"]);
    $peso = floatval($_POST["peso"]);
    $razaId = intval($_POST["raza"]);
    $peligrosidadId = intval($_POST["peligrosidad"]);
    $recomendacion = trim($_POST["recomendacion"]);
    $error = 0;

    $rutaServidor = $perro->getFoto();

    if (isset($_FILES["foto"]) && $_FILES["foto"]["error"] === UPLOAD_ERR_OK) {
        $nombreFotoOriginal = $_FILES["foto"]["name"];
        $rutaTemporal = $_FILES["foto"]["tmp_name"];
        $extension = pathinfo($nombreFotoOriginal, PATHINFO_EXTENSION);

        if (strtolower($extension) != 'png') {
            $mensaje = "Formato de imagen no permitido. Solo se aceptan imágenes PNG.";
            $claseMensaje = "alert-danger";
            $error = 1;
        } else {
            $nuevoNombre = time() . ".png";
            $rutaServidor = "imagenes/" . $nuevoNombre;
            if (move_uploaded_file($rutaTemporal, $rutaServidor)) {
                if ($perro->getFoto() != "" && file_exists($perro->getFoto())) {
                    unlink($perro->getFoto());
                }
            } else {
                $mensaje = "Error al subir la nueva foto.";
                $claseMensaje = "alert-danger";
                $error = 1;
            }
        }
    }

    if ($error == 0) {
        $perroActualizado = new Perro($id, $nombre, $peso, $recomendacion, 1, $rutaServidor, $razaId, $_SESSION["id"], $peligrosidadId);
        $perroActualizado->actualizar();
        $perro = $perroActualizado;
        $mensaje = "Datos actualizados correctamente.";
        $claseMensaje = "alert-success";
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
        <div class="col-md-8">
            <div class="card mb-4">
                <div class="card-header bg-warning text-dark">
                    <h4>Editar Perro</h4>
                </div>
                <div class="card-body">
                    <?php if ($mensaje): ?>
                        <div class="alert <?php echo $claseMensaje; ?>"><?php echo $mensaje; ?>
                            <?php if ($claseMensaje === "alert-success"): ?>
                            <br><a href="?pid=<?php echo base64_encode("presentacion/perro/consultarPerros.php"); ?>" class="btn btn-success btn-sm mt-2">Volver al listado</a>
                            <?php endif; ?>
                        </div>
                    <?php endif; ?>

                    <form method="post" enctype="multipart/form-data">
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Nombre</label>
                                <input type="text" name="nombre" class="form-control" value="<?php echo htmlspecialchars($perro->getNombre()); ?>" required>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Peso (kg)</label>
                                <input type="number" step="0.01" min="0.1" name="peso" class="form-control" value="<?php echo htmlspecialchars($perro->getPeso()); ?>" required>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Raza</label>
                                <select name="raza" class="form-control" required>
                                    <?php foreach ($listaRazas as $r):
                                        if ($r->getTamaño() == 5) continue;
                                        $selected = ($r->getId() == $perro->getRaza() || $r->getNombre() == $perro->getRaza()) ? "selected" : "";
                                    ?>
                                        <option value="<?php echo $r->getId(); ?>" <?php echo $selected; ?>>
                                            <?php echo htmlspecialchars($r->getNombre()); ?>
                                        </option>
                                    <?php endforeach; ?>
                                </select>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Peligrosidad</label>
                                <select name="peligrosidad" class="form-control" required>
                                    <?php foreach ($listaPeligrosidades as $p):
                                        $selected = ($p->getId() == $perro->getPeligrosidad()) ? "selected" : "";
                                    ?>
                                        <option value="<?php echo $p->getId(); ?>" <?php echo $selected; ?>>
                                            <?php echo htmlspecialchars($p->getNivel()); ?>
                                        </option>
                                    <?php endforeach; ?>
                                </select>
                            </div>
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Recomendacion</label>
                            <textarea name="recomendacion" class="form-control" rows="3"><?php echo htmlspecialchars($perro->getRecomendacion()); ?></textarea>
                        </div>

                        <div class="mb-3 text-center">
                            <?php
                            if ($perro->getFoto() != "" && file_exists($perro->getFoto())) {
                                echo "<img src='" . $perro->getFoto() . "' height='150' class='rounded-circle mb-2' />";
                            } else {
                                echo "<p class='text-muted'>No hay foto actual.</p>";
                            }
                            ?>
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Foto Nueva (solo PNG)</label>
                            <input type="file" name="foto" class="form-control" accept="image/png">
                        </div>

                        <button type="submit" name="editar" class="btn btn-warning">Guardar Cambios</button>
                        <a href="?pid=<?php echo base64_encode("presentacion/perro/consultarPerros.php"); ?>" class="btn btn-secondary">Volver al listado</a>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
</body>
</html>
