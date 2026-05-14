<?php
$id = $_SESSION["id"];
$dueño = new Dueño($id);
$dueño->consultar();
?>
<nav class="navbar navbar-expand-lg navbar-dark bg-dark px-3">
  <a class="navbar-brand" href="?pid=<?php echo base64_encode("presentacion/sesionDueño.php") ?>">
    <i class="fa-solid fa-shield-dog"></i> Panel Dueño
  </a>

  <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarDueño" aria-controls="navbarDueño" aria-expanded="false" aria-label="Toggle navigation">
    <span class="navbar-toggler-icon"></span>
  </button>

  <div class="collapse navbar-collapse" id="navbarDueño">
    <ul class="navbar-nav me-auto mb-2 mb-lg-0">

      <li class="nav-item">
        <a class="nav-link" href="?pid=<?php echo base64_encode("presentacion/sesionDueño.php") ?>">
          <i class="fa-solid fa-house"></i> Inicio
        </a>
      </li>
      
            <li class="nav-item">
        <a class="nav-link" href="?pid=<?php echo base64_encode("presentacion/dueño/editarDueño.php") ?>">
          <i class="fa-solid fa-user-pen"></i> Editar Datos
        </a>
      </li>

      <li class="nav-item dropdown">
        <a class="nav-link dropdown-toggle" href="#" id="perrosDropdown" role="button" data-bs-toggle="dropdown" aria-expanded="false">
          <i class="fa-solid fa-dog"></i> Mis Perritos
        </a>
        <ul class="dropdown-menu" aria-labelledby="perrosDropdown">
          <li><a class="dropdown-item" href="?pid=<?php echo base64_encode("presentacion/perro/registrarPerro.php") ?>">Registrar Nuevo</a></li>
          <li><a class="dropdown-item" href="?pid=<?php echo base64_encode("presentacion/perro/consultarPerros.php") ?>">Ver Todos</a></li>
        </ul>
      </li>

<li class="nav-item dropdown">
  <a class="nav-link dropdown-toggle" href="#" id="paseadoresDropdown" role="button" data-bs-toggle="dropdown" aria-expanded="false">
    <i class="	fa-solid fa-person-walking"></i> Paseadores
  </a>
  <ul class="dropdown-menu" aria-labelledby="paseadoresDropdown">
    <li><a class="dropdown-item" href="?pid=<?php echo base64_encode("presentacion/paseador/consultarPaseadores.php") ?>">Ver Todos</a></li>
    <li><a class="dropdown-item" href="?pid=<?php echo base64_encode("presentacion/paseo/solicitarPaseo.php") ?>">Solicitar Paseo</a></li>
  </ul>
</li>

<li class="nav-item dropdown">
  <a class="nav-link dropdown-toggle" href="#" id="historialDropdown" role="button" data-bs-toggle="dropdown" aria-expanded="false">
    <i class="fa-solid fa-file-lines"></i> Historial
  </a>
  <ul class="dropdown-menu" aria-labelledby="historialDropdown">
    <li><a class="dropdown-item" href="?pid=<?php echo base64_encode("presentacion/paseo/historialPaseosd.php") ?>">Paseos</a></li>
    <li><a class="dropdown-item" href="?pid=<?php echo base64_encode("presentacion/paseo/verFacturas.php") ?>">Facturas</a></li>
  </ul>
</li>
<li class="nav-item">
        <a class="nav-link" href="?pid=<?php echo base64_encode("presentacion/dueño/graficaPrecios.php"); ?>">
          <i class="fa-solid fa-chart-line"></i> Estadísticas
        </a>
      </li>

    </ul>

    <ul class="navbar-nav mb-2 mb-lg-0">
      <li class="nav-item">
        <span class="navbar-text text-white me-3">
          <?php echo $dueño->getNombre() . " " . $dueño->getApellido(); ?>
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
