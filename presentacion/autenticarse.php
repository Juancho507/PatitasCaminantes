<?php 
$error = false;
$mensajePaseador = "";

if (isset($_GET["sesion"]) && $_GET["sesion"] == "false") {
    session_destroy();
}

if (isset($_POST["autenticarse"])) {
    $correo = $_POST["correo"];
    $clave = $_POST["clave"];

    
    $administrador = new Administrador("", "", "", $correo, $clave);
    if ($administrador->autenticarse()) {
        $_SESSION["id"] = $administrador->getId();
        $_SESSION["rol"] = "administrador";
        header("Location: ?pid=" . base64_encode("presentacion/sesionAdministrador.php"));
        exit;
    }

   
    require_once("persistencia/Conexion.php");
    $conexion = new Conexion();
    $conexion->abrir();
    $conexion->ejecutar("SELECT idDueño, Estado_idEstado FROM Dueño WHERE Correo = '$correo' AND (Clave = '$clave' OR Clave = '" . md5($clave) . "')");
    if ($conexion->filas() == 1) {
        $datosDueño = $conexion->registro();
        if ($datosDueño[1] == 4) {
            $mensajePaseador = "Usted tiene una multa pendiente. Contacte al administrador para solucionarlo y activar nuevamente su cuenta.";
        } else {
            $_SESSION["id"] = $datosDueño[0];
            $_SESSION["rol"] = "dueño";
            $conexion->cerrar();
            header("Location: ?pid=" . base64_encode("presentacion/sesionDueño.php"));
            exit;
        }
    } else {
       
        $conexion->ejecutar("SELECT p.idPaseador, p.Estado_idEstado, e.Nombre
            FROM paseador p
            INNER JOIN estado e ON p.Estado_idEstado = e.idEstado
            WHERE p.Correo = '$correo' AND (p.Clave = '$clave' OR p.Clave = '" . md5($clave) . "')");
        if ($conexion->filas() == 1) {
            $datos = $conexion->registro();
            $idPaseador = $datos[0];
            $estadoId = (int)$datos[1];
            $estadoNombre = $datos[2];

            if ($estadoId == 2) {
                $conexion->cerrar();
                $_SESSION["id"] = $idPaseador;
                $_SESSION["rol"] = "paseador";
                header("Location: ?pid=" . base64_encode("presentacion/sesionPaseador.php"));
                exit;
            } elseif ($estadoId == 1) {
                $mensajePaseador = "Tu solicitud está pendiente. Espera la respuesta del administrador en un plazo máximo de una semana.";
            } elseif ($estadoId == 3) {
                $mensajePaseador = "Tu solicitud no fue aprobada. Comunícate con el administrador para más información.";
            } elseif ($estadoId == 4) {
                $mensajePaseador = "Tu cuenta ha sido bloqueada. Contacta al administrador para reactivarla.";
            } else {
                $error = true;
            }
        } else {
            $error = true;
        }
    }
    $conexion->cerrar();
}
?>

<body class="position-relative" style="background-color: #fff3b0; min-height: 100vh; font-family: 'Segoe UI', sans-serif;">

  <img src="img/huellas_superior.png" class="position-fixed top-0 end-0" style="width: 100px; z-index: 0;" alt="huellas1">
  <img src="img/huellas_inferior.png" class="position-fixed bottom-0 start-0" style="width: 100px; z-index: 0;" alt="huellas2">

  <div class="container-fluid d-flex flex-wrap justify-content-center align-items-center min-vh-100 px-3" style="z-index: 1; position: relative;">
    <div class="row w-100 flex-lg-row flex-column justify-content-center align-items-center text-center text-lg-start">

      <div class="col-lg-5 col-12 d-flex flex-column justify-content-center align-items-center px-3 mb-4 mb-lg-0">
        <div class="rounded-circle overflow-hidden mb-3" style="width: 220px; height: 220px;">
          <img src="img/patitas.png" class="w-100 h-100" style="object-fit: cover;" alt="Logo DoggyToons">
        </div>
        <div class="text-center">
          <p class="fw-bold fs-4">Patitas Caminantes</p>
          <ul class="list-unstyled">
            <li class="d-flex justify-content-center align-items-center mb-2"><span>✔️ Elije a tu paseador</span></li>
            <li class="d-flex justify-content-center align-items-center mb-2"><span>✔️ Elije tus horarios</span></li>
            <li class="d-flex justify-content-center align-items-center mb-2"><span>✔️ Elije tu precio</span></li>
            <li class="d-flex justify-content-center align-items-center mb-2"><span>✔️ Paseos de 1 hora de duración</span></li>
            <li class="d-flex justify-content-center align-items-center"><span>✔️ Solo pagos en efectivo y en persona</span></li>
          </ul>
        </div>
      </div>

      <div class="col-lg-5 col-12 d-flex justify-content-center">
        <div class="card shadow p-4" style="max-width: 500px; width: 100%; border-radius: 1rem; z-index: 2;">
          <h4 class="text-center mb-4">Bienvenido a Patitas Caminantes</h4>
          <form method="POST" action="?pid=<?php echo base64_encode("presentacion/autenticarse.php"); ?>">
            <input type="hidden" name="autenticarse" value="1">

            <div class="mb-3 text-start">
              <label for="correo" class="form-label">Correo electrónico</label>
              <input type="email" class="form-control" id="correo" name="correo" required>
            </div>

            <div class="mb-3 text-start">
              <label for="clave" class="form-label">Contraseña</label>
              <input type="password" class="form-control" id="clave" name="clave" required>
            </div>

            <button type="submit" class="btn w-100 text-white fw-bold" style="background-color: #e67e22;">Iniciar Sesión</button>

            <?php if ($error): ?>
              <div class="alert alert-danger mt-3" role="alert">Clave o correo incorrecto</div>
            <?php endif; ?>

            <?php if ($mensajePaseador): ?>
              <div class="alert alert-warning mt-3" role="alert"><?php echo $mensajePaseador; ?></div>
            <?php endif; ?>
          </form>

          <div class="text-center mt-3">
            <a href="?pid=<?php echo base64_encode('presentacion/dueño/nuevodueño.php'); ?>" class="fw-semibold" style="color: #e67e22; text-decoration: underline;">¿Eres dueño nuevo? Regístrate aquí</a>
          </div>

          <div class="text-center mt-2">
            <a href="?pid=<?php echo base64_encode('presentacion/paseador/registrarAspirante.php'); ?>" class="fw-semibold" style="color: #e67e22; text-decoration: underline;">¿Deseas ser paseador? Regístrate aquí</a>
          </div>

        </div>
      </div>

    </div>
  </div>

</body>
</html>
