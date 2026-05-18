<?php
$idDueñoLogueado = $_SESSION["id"];
$rol = $_SESSION["rol"];
?>

<body>
<?php
include("presentacion/encabezadoD.php");
include("presentacion/menu" . ucfirst($rol) . ".php");

$mensaje = "";
$claseMensaje = "";
$razaLogica = new Raza();
$razas = $razaLogica->consultarTodos();
$peligrosidadLogica = new Peligrosidad();
$peligrosidades = $peligrosidadLogica->consultarTodos();

if (isset($_POST["crearPerro"])) {
    $nombrePerro = trim($_POST["nombrePerro"] ?? '');
    $peso = floatval($_POST["peso"] ?? 0);
    $razaId = intval($_POST["razaId"] ?? 0);
    $peligrosidadId = intval($_POST["peligrosidad"] ?? 0);
    $recomendacion = trim($_POST["recomendacion"] ?? '');
    $fotoRuta = "";
    $errorEnSubidaFoto = false;

    if (empty($nombrePerro)) {
        $mensaje = "El nombre del perro es obligatorio.";
        $claseMensaje = "alert-danger";
        $errorEnSubidaFoto = true;
    } elseif ($peso <= 0) {
        $mensaje = "El peso debe ser un valor positivo.";
        $claseMensaje = "alert-danger";
        $errorEnSubidaFoto = true;
    } elseif ($razaId <= 0) {
        $mensaje = "Debe seleccionar una raza.";
        $claseMensaje = "alert-danger";
        $errorEnSubidaFoto = true;
    } elseif ($peligrosidadId <= 0) {
        $mensaje = "Debe seleccionar un nivel de peligrosidad.";
        $claseMensaje = "alert-danger";
        $errorEnSubidaFoto = true;
    }

    if (!$errorEnSubidaFoto && isset($_FILES["foto"]) && $_FILES["foto"]["error"] === UPLOAD_ERR_OK) {
        $nombreFotoOriginal = $_FILES["foto"]["name"];
        $rutaTemporal = $_FILES["foto"]["tmp_name"];
        $extension = pathinfo($nombreFotoOriginal, PATHINFO_EXTENSION);

        if (strtolower($extension) != 'png') {
            $mensaje = "Formato de imagen no permitido. Solo se aceptan imágenes PNG.";
            $claseMensaje = "alert-danger";
            $errorEnSubidaFoto = true;
        } else {
            $nuevoNombreFoto = time() . ".png";
            $directorioDestino = "imagenes/";
            $rutaServidor = $directorioDestino . $nuevoNombreFoto;

            if (move_uploaded_file($rutaTemporal, $rutaServidor)) {
                $fotoRuta = $rutaServidor;
            } else {
                $mensaje = "Error al mover el archivo de la foto al servidor.";
                $claseMensaje = "alert-danger";
                $errorEnSubidaFoto = true;
            }
        }
    } elseif (!$errorEnSubidaFoto && isset($_FILES["foto"]) && $_FILES["foto"]["error"] !== UPLOAD_ERR_NO_FILE) {
        $mensaje = "Error en la subida de la foto (código: " . $_FILES["foto"]["error"] . ").";
        $claseMensaje = "alert-danger";
        $errorEnSubidaFoto = true;
    }

    $dueño = new Dueño($idDueñoLogueado);

    if (!$errorEnSubidaFoto) {
        $raza = new Raza($razaId);
        $perro = new Perro(0, $nombrePerro, $peso, $recomendacion, 1, $fotoRuta, $raza, $dueño, $peligrosidadId);
        $guardadoExitoso = $perro->insertar();

        if ($guardadoExitoso) {
            $mensaje = "Perro '" . htmlspecialchars($nombrePerro) . "' registrado correctamente.";
            $claseMensaje = "alert-success";
            $_POST = array();
        } else {
            $mensaje = "Error al registrar el perro. Verifique los datos e intente nuevamente.";
            $claseMensaje = "alert-danger";
            if ($fotoRuta && file_exists($fotoRuta)) {
                unlink($fotoRuta);
            }
        }
    }
}

?>

<div class="container">
    <div class="row mt-4">
        <div class="col-md-8 offset-md-2">
            <div class="card">
                <div class="card-header"><h4>Registrar Nuevo Perro</h4></div>
                <div class="card-body">
                    <?php if ($mensaje): ?>
                        <div class="alert <?php echo $claseMensaje; ?>" role="alert">
                            <?php echo $mensaje; ?>
                        </div>
                    <?php endif; ?>

                    <form action="<?= "?pid=" . base64_encode("presentacion/perro/registrarPerro.php"); ?>" method="POST" enctype="multipart/form-data">
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group mb-3">
                                    <label for="nombrePerro">Nombre del Perro:</label>
                                    <input type="text" class="form-control" id="nombrePerro" name="nombrePerro" required
                                        value="<?php echo isset($_POST['nombrePerro']) ? htmlspecialchars($_POST['nombrePerro']) : ''; ?>">
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group mb-3">
                                    <label for="peso">Peso (kg):</label>
                                    <input type="number" step="0.01" min="0.1" class="form-control" id="peso" name="peso" required
                                        value="<?php echo isset($_POST['peso']) ? htmlspecialchars($_POST['peso']) : ''; ?>">
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group mb-3">
                                    <label for="razaId">Raza:</label>
                                    <select class="form-control" id="razaId" name="razaId" required>
                                        <option value="">Seleccione una raza</option>
                                        <?php foreach ($razas as $razaItem):
                                        $t = $razaItem->getTamaño();
                                        if ($t === null || $t === "" || $t == 0) continue;
                                        ?>
                                        <option value="<?php echo $razaItem->getId(); ?>"
                                            <?php echo isset($_POST['razaId']) && $_POST['razaId'] == $razaItem->getId() ? 'selected' : ''; ?>>
                                            <?php echo htmlspecialchars($razaItem->getNombre()) . " (" . $razaItem->getTamaño() . ")"; ?>
                                        </option>
                                        <?php endforeach; ?>
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group mb-3">
                                    <label for="peligrosidad">Peligrosidad:</label>
                                    <select class="form-control" id="peligrosidad" name="peligrosidad" required>
                                        <option value="">Seleccione nivel</option>
                                        <?php foreach ($peligrosidades as $p): ?>
                                        <option value="<?php echo $p->getId(); ?>"
                                            <?php echo isset($_POST['peligrosidad']) && $_POST['peligrosidad'] == $p->getId() ? 'selected' : ''; ?>>
                                            <?php echo htmlspecialchars($p->getNivel()); ?>
                                        </option>
                                        <?php endforeach; ?>
                                    </select>
                                </div>
                            </div>
                        </div>

                        <div class="form-group mb-3">
                            <label for="recomendacion">Recomendacion:</label>
                            <textarea class="form-control" id="recomendacion" name="recomendacion" rows="3"><?php echo isset($_POST['recomendacion']) ? htmlspecialchars($_POST['recomendacion']) : ''; ?></textarea>
                        </div>

                        <div class="form-group mb-3">
                            <label for="foto">Foto de Perfil:</label>
                            <input type="file" class="form-control" id="foto" name="foto" accept="image/png">
                            <small class="form-text text-muted">Solo se permiten imágenes PNG.</small>
                        </div>

                        <button type="submit" name="crearPerro" class="btn btn-primary">Registrar Perro</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

</body>
</html>
