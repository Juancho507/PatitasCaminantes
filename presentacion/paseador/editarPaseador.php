<?php
if ($_SESSION["rol"] != "paseador") {
    header("Location: ?pid=" . base64_encode("presentacion/noAutorizado.php"));
    exit();
}
$error = 0; 
$id = $_SESSION["id"];
$paseador = new Paseador($id);
$paseador->consultar(); 


if (isset($_POST["editar"])) {
    $nombre = $_POST["nombre"];
    $apellido = $_POST["apellido"];
    $correo = $_POST["correo"];
    $claveNueva = $_POST["clave"];
    $contacto = isset($_POST["contacto"]) ? trim($_POST["contacto"]) : "0";
    if ($contacto === "" || !is_numeric($contacto)) {
        $contacto = "0"; 
    }
    $informacion = isset($_POST["informacion"]) ? $_POST["informacion"] : "";
    $foto = $_FILES["foto"]["name"];
    $tam = $_FILES["foto"]["size"];
    $rutaLocal = $_FILES["foto"]["tmp_name"];
    
    $claveFinal = $paseador->getClave();
    if (!empty($claveNueva)) {
        $claveFinal = md5($claveNueva);
    }
    
    $rutaServidor = $paseador->getFoto();
    if ($foto != "") {
        $nuevoNombre = time() . ".png";
        $rutaServidor = "imagenes/" . $nuevoNombre;
        if (copy($rutaLocal, $rutaServidor)) {
            if ($paseador->getFoto() != "") {
                $rutaFoto = __DIR__ . "/../../" . $paseador->getFoto();
                if (file_exists($rutaFoto)) {
                    unlink($rutaFoto);
                }
            }
        } else {
            $error = 1;
        }
    }
    
    if ($error == 0) {
        $paseadorActualizado = new Paseador(
            $id,
            $nombre,
            $apellido,
            $correo,
            $claveFinal,
            $contacto,
            $rutaServidor,
            $informacion
            );
        $paseadorActualizado->actualizar();
        $paseador = $paseadorActualizado; 
    }
}
?>
<body>
<?php
include("presentacion/encabezadoP.php");
include("presentacion/menuPaseador.php");
?>
<div class="container mt-4">
    <div class="row justify-content-center">
        <div class="col-md-6">
            <div class="card mb-4"> 
                <div class="card-header bg-primary text-white">
                    <h4>Editar Perfil</h4>
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
                            <input type="text" name="nombre" class="form-control" value="<?php echo htmlspecialchars($paseador->getNombre()); ?>" required>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Apellido</label>
                            <input type="text" name="apellido" class="form-control" value="<?php echo htmlspecialchars($paseador->getApellido()); ?>" required>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Correo</label>
                            <input type="email" name="correo" class="form-control" value="<?php echo htmlspecialchars($paseador->getCorreo()); ?>" required>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Nueva Clave (dejar en blanco si no deseas cambiarla)</label>
                            <input type="password" name="clave" class="form-control">
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Contacto</label>
                            <input type="text" name="contacto" class="form-control" value="<?php echo htmlspecialchars($paseador->getContacto()); ?>" required>
                        </div>
                         <div class="mb-3">
                            <label class="form-label">Información / Descripción</label>
                            <textarea name="informacion" class="form-control" rows="3" required><?php echo htmlspecialchars($paseador->getInformacion()); ?></textarea>
                        </div>
                        <div class="mb-3 text-center">
                            <?php
                            if ($paseador->getFoto() != "" && file_exists($paseador->getFoto())) {
                                echo "<img src='" . $paseador->getFoto() . "' height='150' class='rounded-circle mb-2' />";
                            } else {
                                echo "<p>No hay foto actual.</p>";
                            }
                            ?>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Foto Nueva</label>
                            <input type="file" name="foto" class="form-control">
                        </div>
                        <button type="submit" name="editar" class="btn btn-primary">Guardar Cambios</button>
                    </form>

                    

                </div>
            </div>
        </div>
    </div>
</div>
</body>

