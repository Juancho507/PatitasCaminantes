<?php
$id = $_SESSION["id"];
$paseador = new Paseador($id);
$paseador->consultar();
?>
<nav class="navbar navbar-expand-lg navbar-dark bg-dark px-3">
  <a class="navbar-brand" href="?pid=<?php echo base64_encode("presentacion/sesionPaseador.php"); ?>">
    <i class="fa-solid fa-dog"></i> Paseador
  </a>

  <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarPaseador" aria-controls="navbarPaseador" aria-expanded="false" aria-label="Toggle navigation">
    <span class="navbar-toggler-icon"></span>
  </button>

  <div class="collapse navbar-collapse" id="navbarPaseador">
    <ul class="navbar-nav me-auto mb-2 mb-lg-0">

      <li class="nav-item">
        <a class="nav-link" href="?pid=<?php echo base64_encode("presentacion/sesionPaseador.php"); ?>">
          <i class="fa-solid fa-house"></i> Inicio
        </a>
      </li>
      
               <li class="nav-item">
        <a class="nav-link" href="?pid=<?php echo base64_encode("presentacion/paseador/editarPaseador.php") ?>">
          <i class="fa-solid fa-user-pen"></i> Editar Datos
        </a>
      </li>

      <li class="nav-item">
        <a class="nav-link" href="?pid=<?php echo base64_encode("presentacion/paseador/establecerTarifa.php"); ?>">
          <i class="fa-solid fa-dollar-sign"></i> Mi Tarifa
        </a>
      </li>

      <li class="nav-item">
        <a class="nav-link" href="?pid=<?php echo base64_encode("presentacion/paseo/paseosPendientes.php"); ?>">
          <i class="fa-solid fa-calendar-check"></i> Paseos Pendientes
        </a>
      </li>
      <li class="nav-item">
        <a class="nav-link" href="?pid=<?php echo base64_encode("presentacion/paseador/graficaTamaños.php"); ?>">
          <i class="fa-solid fa-chart-line"></i> Estadísticas
        </a>
      </li>
      <li class="nav-item">
        <a class="nav-link" href="?pid=<?php echo base64_encode("presentacion/paseo/historialPaseosp.php"); ?>">
          <i class="fa-solid fa-list"></i> Mis Paseos
        </a>
      </li>
    </ul>
  
    <ul class="navbar-nav mb-2 mb-lg-0">
      <li class="nav-item">
        <span class="navbar-text text-white me-3">
          <?php echo $paseador->getNombre() . " " . $paseador->getApellido(); ?>
        </span>
      </li>
     
      <li class="nav-item">
       <a class="nav-link text-danger" href="?pid=<?php echo base64_encode("presentacion/autenticarse.php") ?>&sesion=false">
          <i class="fa-solid fa-right-from-bracket"></i> Cerrar Sesión
        </a>
      </li>
    </ul>
  </div>
</nav>