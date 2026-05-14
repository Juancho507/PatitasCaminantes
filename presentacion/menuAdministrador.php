<?php

$id = $_SESSION["id"];
$administrador = new Administrador($id);
$administrador->consultar();
?>
<nav class="navbar navbar-expand-lg navbar-dark bg-dark px-3">
  <a class="navbar-brand" href="?pid=<?php echo base64_encode("presentacion/sesionAdministrador.php"); ?>">
    <i class="fa-solid fa-screwdriver-wrench"></i> Panel Admin
  </a>

  <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarAdmin" aria-controls="navbarAdmin" aria-expanded="false" aria-label="Toggle navigation">
    <span class="navbar-toggler-icon"></span>
  </button>

  <div class="collapse navbar-collapse" id="navbarAdmin">
    <ul class="navbar-nav me-auto mb-2 mb-lg-0">

      <li class="nav-item">
        <a class="nav-link" href="?pid=<?php echo base64_encode("presentacion/sesionAdministrador.php"); ?>">
          <i class="fa-solid fa-house"></i> Inicio
        </a>
      </li>

      <li class="nav-item">
        <a class="nav-link" href="?pid=<?php echo base64_encode("presentacion/editarAdministrador.php") ?>">
          <i class="fa-solid fa-user-pen"></i> Editar Datos
        </a>
      </li>
      
       <li class="nav-item">
        <a class="nav-link" href="?pid=<?php echo base64_encode("presentacion/administrador/aceptarTamañosPerritos.php"); ?>">
          <i class="fa-solid fa-dog"></i> Perritos
        </a>
      </li>

      <li class="nav-item dropdown">
        <a class="nav-link dropdown-toggle" href="#" id="paseadoresDropdown" role="button" data-bs-toggle="dropdown" aria-expanded="false">
          <i class="fa-solid fa-person-walking"></i> Paseadores
        </a>
        <ul class="dropdown-menu" aria-labelledby="paseadoresDropdown">
          <li><a class="dropdown-item" href="?pid=<?php echo base64_encode("presentacion/paseador/gestionarPaseadores.php"); ?>">Gestionar Paseadores</a></li>
          <li><a class="dropdown-item" href="?pid=<?php echo base64_encode("presentacion/paseador/registrarPaseador.php") ?>">Registrar Paseador</a></li>
        </ul>
      </li>

      <li class="nav-item">
        <a class="nav-link" href="?pid=<?php echo base64_encode("presentacion/estadisticas.php"); ?>">
          <i class="fa-solid fa-chart-line"></i> Estadísticas
        </a>
      </li>
    </ul>

    <ul class="navbar-nav mb-2 mb-lg-0">
      <li class="nav-item">
        <span class="navbar-text text-white me-3">
          <?php echo $administrador->getNombre() . " " . $administrador->getApellido(); ?>
        </span>
      </li>
      <li class="nav-item">
        <a class="nav-link text-danger" href="?pid=<?php echo base64_encode("presentacion/autenticarse.php") ?>&sesion=false">
          <i class="fa-solid fa-right-from-bracket"></i> Cerrar Sesión
        </a>
    </ul>
  </div>
</nav><?php
