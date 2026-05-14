<?php
if ($_SESSION["rol"] != "dueño") {
    header("Location: ?pid=" . base64_encode("presentacion/noAutorizado.php"));
    exit();
}

$error = 0;
$id = $_SESSION["id"];
$dueño = new Dueño($id);
$dueño->consultar();

if (isset($_POST["editar"])) {
    $nombre = $_POST["nombre"];
    $apellido = $_POST["apellido"];
    $correo = $_POST["correo"];
    $claveNueva = $_POST["clave"];
    $contacto = $_POST["contacto"];
    $foto = $_FILES["foto"]["name"];
    $tam = $_FILES["foto"]["size"];
    $rutaLocal = $_FILES["foto"]["tmp_name"];
    
    $claveFinal = $dueño->getClave();
    if (!empty($claveNueva)) {
        $claveFinal = md5($claveNueva);
    }
    
    $rutaServidor = $dueño->getFoto();
    if ($foto != "") {
        $nuevoNombre = time() . ".png";
        $rutaServidor = "imagenes/" . $nuevoNombre;
        if (copy($rutaLocal, $rutaServidor)) {
            if ($dueño->getFoto() != "") {
                $rutaFoto = __DIR__ . "/../../" . $dueño->getFoto();
                if (file_exists($rutaFoto)) {
                    unlink($rutaFoto);
                }
            }
        } else {
            $error = 1;
        }
    }
    
    if ($error == 0) {
        $dueñoActualizado = new Dueño($id, $nombre, $apellido, $correo, $claveFinal, $contacto, $rutaServidor);
        $dueñoActualizado->actualizar();
        $dueño = $dueñoActualizado;
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
                <div class="card-header bg-success text-white">
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
                            <input type="text" name="nombre" class="form-control" value="<?php echo htmlspecialchars($dueño->getNombre()); ?>" required>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Apellido</label>
                            <input type="text" name="apellido" class="form-control" value="<?php echo htmlspecialchars($dueño->getApellido()); ?>" required>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Correo</label>
                            <input type="email" name="correo" class="form-control" value="<?php echo htmlspecialchars($dueño->getCorreo()); ?>" required>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Nueva Clave (dejar en blanco si no deseas cambiarla)</label>
                            <input type="password" name="clave" class="form-control">
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Contacto</label>
                            <input type="text" name="contacto" class="form-control" value="<?php echo htmlspecialchars($dueño->getContacto()); ?>" required>
                        </div>
                        <div class="mb-3 text-center">
                            <?php
                            if ($dueño->getFoto() != "" && file_exists($dueño->getFoto())) {
                                echo "<img src='" . $dueño->getFoto() . "' height='150' class='rounded-circle mb-2' />";
                            } else {
                                echo "<p>No hay foto actual.</p>";
                            }
                            ?>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Foto Nueva</label>
                            <input type="file" name="foto" class="form-control">
                        </div>
                        <button type="submit" name="editar" class="btn btn-success">Guardar Cambios</button>
                    </form>

                    <hr>
                    <form method="post" action="?pid=<?php echo base64_encode("presentacion/dueño/eliminarDueño.php"); ?>" onsubmit="return confirmarEliminacion();">
   				 <button type="submit" name="eliminar" class="btn btn-danger">Eliminar Cuenta</button>
					</form>
                  			  <script>
			function confirmarEliminacion() {
   			 return confirm("¿Estás seguro de eliminar tu cuenta? Esta acción no se puede deshacer.");
				}
					</script>

                </div>
            </div>
        </div>
    </div>
</div>
</body>

