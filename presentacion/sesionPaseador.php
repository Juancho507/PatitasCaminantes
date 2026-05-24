<?php
if($_SESSION["rol"] != "paseador"){
    header("Location: ?pid=" . base64_encode("presentacion/noAutorizado.php"));
}
$id = $_SESSION["id"];
?>
<body>
<?php 
include ("presentacion/encabezadoP.php");
include ("presentacion/menuPaseador.php");
$paseador = new Paseador($id);
$paseador ->consultar();

// Show pending review message for aspirantes
$estadoPaseador = $paseador->getEstadoId();
?>
<?php if ($estadoPaseador == 1): ?>
<div class="container mt-4">
    <div class="alert alert-info text-center shadow-sm">
        <i class="fas fa-clock me-2"></i>
        <strong>Tu solicitud está en revisión.</strong> Te notificaremos cuando sea procesada. Gracias por tu paciencia.
    </div>
</div>
<?php elseif ($estadoPaseador == 3): ?>
<div class="container mt-4">
    <div class="alert alert-danger text-center shadow-sm">
        <i class="fas fa-times-circle me-2"></i>
        <strong>Tu solicitud fue rechazada.</strong> Si crees que fue un error, por favor contáctanos al correo <a href="mailto:juan@patitas.com" class="alert-link">juan@patitas.com</a>.
    </div>
</div>
<?php endif; ?>
<div class="container mt-5">
  <div class="row">
    <div class="col-md-7 mx-auto"> 
      <div class="card">
        <div class="card-body">
        <h2 class="my-2 text-center">Perfil</h2>
          <div class="text-center mb-3">
            <?php
            if ($paseador->getFoto() != "" && file_exists($paseador->getFoto())) {
                echo "<img src='" . $paseador->getFoto() . "' class='rounded-circle shadow' height='150'>";
            } else {
                echo "<p class='text-muted'>No hay foto de perfil.</p>";
            }
            ?>
          </div>

          <div class="table-responsive-sm my-2">
            <table class="table table-striped">
              <tr>
                <th>Nombre</th>
                <td><?php echo $paseador->getNombre(); ?></td>
              </tr>
              <tr>
                <th>Apellido</th>
                <td><?php echo $paseador->getApellido(); ?></td>
              </tr>
              <tr>
                <th>Documento</th>
                <td><?php echo htmlspecialchars($paseador->getNroDocumento() ?: 'N/A'); ?></td>
              </tr>
              <tr>
                <th>Fecha Nacimiento</th>
                <td><?php echo $paseador->getFechaNacimiento() ? date('d/m/Y', strtotime($paseador->getFechaNacimiento())) : 'N/A'; ?></td>
              </tr>
              <tr>
                <th>Correo</th>
                <td><?php echo $paseador->getCorreo(); ?></td>
              </tr>
              <tr>
                <th>Contacto</th>
                <td><?php echo $paseador->getContacto(); ?></td>
              </tr>
              <tr>
                <th>Ciudad</th>
                <td><?php echo htmlspecialchars($paseador->getCiudadNombre() ?: 'N/A'); ?></td>
              </tr>
              <tr>
                <th>Barrio / Localidad</th>
                <td><?php echo htmlspecialchars($paseador->getLocalidadNombre() ?: 'N/A'); ?></td>
              </tr>
              <tr>
                <th>Perro Peligroso</th>
                <td><?php echo $paseador->getAprobadoPeligroso() ? '<span class="badge bg-success">Sí</span>' : '<span class="badge bg-secondary">No</span>'; ?></td>
              </tr>
              <?php if ($paseador->getCertificados()): ?>
              <?php endif; ?>
            </table>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>


</body>
