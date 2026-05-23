<?php
$id = $_SESSION["id"];
$rol = $_SESSION["rol"];

$exito = false;
$error = false;
$mensaje = "";

if (isset($_POST["solicitar"])) {
    $nombreRaza = trim($_POST["nombreRaza"]);
    if (!empty($nombreRaza)) {
        $conexion = new Conexion();
        $conexion->abrir();
        $nombreRazaSeguro = addslashes($nombreRaza);
        $conexion->ejecutar("INSERT INTO solicitudraza (NombreRaza, idDueño, Estado_idEstado) VALUES ('$nombreRazaSeguro', $id, 1)");
        if ($conexion->afectadas() > 0) {
            $exito = true;
            $mensaje = "Solicitud enviada. El administrador revisará tu propuesta.";
        } else {
            $error = true;
            $mensaje = "Error al enviar la solicitud.";
        }
        $conexion->cerrar();
    } else {
        $error = true;
        $mensaje = "Debe ingresar un nombre de raza.";
    }
}
?>
<body>
<?php
include("presentacion/encabezadoD.php");
include("presentacion/menu" . ucfirst($rol) . ".php");
?>
<div class="container mt-4">
    <div class="row justify-content-center">
        <div class="col-md-6">
            <div class="card shadow">
                <div class="card-header bg-info text-white">
                    <h4><i class="fa-solid fa-paw"></i> Solicitar Nueva Raza</h4>
                </div>
                <div class="card-body">
                    <p class="text-muted">Si no encuentras la raza de tu perro en nuestra lista, puedes solicitar que sea agregada.</p>
                    <?php if ($exito): ?>
                        <div class="alert alert-success"><?php echo $mensaje; ?></div>
                    <?php elseif ($error): ?>
                        <div class="alert alert-danger"><?php echo $mensaje; ?></div>
                    <?php endif; ?>
                    <form method="post">
                        <div class="mb-3">
                            <label class="form-label">Nombre de la Raza</label>
                            <input type="text" name="nombreRaza" class="form-control" placeholder="Ej: Shih Tzu" required>
                        </div>
                        <button type="submit" name="solicitar" class="btn btn-info text-white">Enviar Solicitud</button>
                        <a href="?pid=<?php echo base64_encode('presentacion/perro/consultarPerros.php'); ?>" class="btn btn-secondary">Cancelar</a>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
</body>
</html>
