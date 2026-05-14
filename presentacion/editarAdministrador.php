<?php
if ($_SESSION["rol"] != "administrador") {
    header("Location: ?pid=" . base64_encode("presentacion/noAutorizado.php"));
    exit();
}

require_once("logica/Administrador.php");

$id = $_SESSION["id"];
$administrador = new Administrador($id);
$administrador->consultar();

$mensaje = "";
$error = false;

if (isset($_POST["editar"])) {
    $nombre = $_POST["nombre"];
    $apellido = $_POST["apellido"];
    $correo = $_POST["correo"];
    $claveNueva = $_POST["clave"];

    $claveFinal = $administrador->getClave();
    if (!empty($claveNueva)) {
        $claveFinal = md5($claveNueva);
    }

    $administradorActualizado = new Administrador(
        $id,
        $nombre,
        $apellido,
        $correo,
        $claveFinal
    );

    try {
        $administradorActualizado->actualizar();
        $mensaje = "<div class='alert alert-success'>✅ Datos actualizados correctamente.</div>";
        $administrador = $administradorActualizado;
    } catch (Exception $e) {
        $mensaje = "<div class='alert alert-danger'>❌ Error al actualizar: " . $e->getMessage() . "</div>";
        $error = true;
    }
}
?>

<body>
<?php
include("presentacion/encabezadoA.php");
include("presentacion/menuAdministrador.php");
?>
<div class="container mt-4">
    <div class="row justify-content-center">
        <div class="col-md-6">
            <div class="card mb-4">
                <div class="card-header bg-primary text-white">
                    <h4>Editar Perfil</h4>
                </div>
                <div class="card-body">
                    <?php echo $mensaje; ?>
                    <form method="post" enctype="multipart/form-data">
                        <div class="mb-3">
                            <label class="form-label">Nombre</label>
                            <input type="text" name="nombre" class="form-control" value="<?php echo htmlspecialchars($administrador->getNombre()); ?>" required>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Apellido</label>
                            <input type="text" name="apellido" class="form-control" value="<?php echo htmlspecialchars($administrador->getApellido()); ?>" required>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Correo</label>
                            <input type="email" name="correo" class="form-control" value="<?php echo htmlspecialchars($administrador->getCorreo()); ?>" required>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Nueva Clave (dejar en blanco si no deseas cambiarla)</label>
                            <input type="password" name="clave" class="form-control">
                        </div>
                        <button type="submit" name="editar" class="btn btn-primary">Guardar Cambios</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
</body>

