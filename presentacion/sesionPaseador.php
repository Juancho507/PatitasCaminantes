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
?>
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
                <th>Correo</th>
                <td><?php echo $paseador->getCorreo(); ?></td>
              </tr>
              <tr>
                <th>Contacto</th>
                <td><?php echo $paseador->getContacto(); ?></td>
              </tr>
            </table>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>


</body>
