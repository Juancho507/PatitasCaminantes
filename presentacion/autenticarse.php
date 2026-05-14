<?php 
if(isset($_GET["sesion"])){
    if($_GET["sesion"] == "false"){
        session_destroy();
    }
}
$error=false;
if(isset($_POST["autenticarse"])){
    $correo = $_POST["correo"];
    $clave = $_POST["clave"];
    $administrador = new Administrador("", "", "", $correo, $clave);
    if($administrador -> autenticarse()){
        $_SESSION["id"] = $administrador -> getId();
        $_SESSION["rol"] = "administrador";
        header("Location: ?pid=" . base64_encode("presentacion/sesionAdministrador.php"));
    }else {
        $paseador = new Paseador("", "", "", $correo, $clave);
        if($paseador -> autenticarse()){
            $_SESSION["id"] = $paseador -> getId();
            $_SESSION["rol"] = "paseador";
            header("Location: ?pid=" . base64_encode("presentacion/sesionPaseador.php"));
        }else{
            $dueño = new Dueño("", "", "", $correo, $clave);
            if($dueño -> autenticarse()){
                $_SESSION["id"] = $dueño -> getId();
                $_SESSION["rol"] = "dueño";
                header("Location: ?pid=" . base64_encode("presentacion/sesionDueño.php"));
            }else{
                $error=true;
            }
        }
    }
}
?>

<body class="position-relative" style="background-color: #fff3b0; min-height: 100vh; font-family: 'Segoe UI', sans-serif;">

 
  <img src="img/huellas_superior.png" class="position-fixed top-0 end-0" style="width: 100px; z-index: 0;" alt="huellas1">
  <img src="img/huellas_inferior.png" class="position-fixed bottom-0 start-0" style="width: 100px; z-index: 0;" alt="huellas2">

  <div class="container-fluid d-flex flex-wrap justify-content-center align-items-center min-vh-100 px-3" style="z-index: 1; position: relative;">
    <div class="row w-100 flex-lg-row flex-column justify-content-center align-items-center text-center text-lg-start">

      <div class="col-lg-5 col-12 d-flex flex-column justify-content-center align-items-center px-3 mb-4 mb-lg-0">
        <div class="rounded-circle overflow-hidden mb-3" style="width: 220px; height: 220px;">
          <img src="img/logo.png" class="w-100 h-100" style="object-fit: cover;" alt="Logo DoggyToons">
        </div>
        <div class="text-center">
          <p class="fw-bold fs-4">Paseos personalizados para tu perrito.</p>
          <ul class="list-unstyled">
            <li class="d-flex justify-content-center align-items-center mb-2"><span>✔️ Elije a tu paseador</span></li>
            <li class="d-flex justify-content-center align-items-center mb-2"><span>✔️ Elije tus horarios</span></li>
            <li class="d-flex justify-content-center align-items-center"><span>✔️ Elije tu precio</span></li>
            <li class="d-flex justify-content-center align-items-center"><span>✔️ Solo pagos en efectivo y en persona</span></li>
          </ul>
        </div>
      </div>

      <div class="col-lg-5 col-12 d-flex justify-content-center">
        <div class="card shadow p-4" style="max-width: 500px; width: 100%; border-radius: 1rem; z-index: 2;">
          <h4 class="text-center mb-4">Bienvenido a DoggyToons</h4>
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
          </form>

          <div class="text-center mt-3">
            <a href="?pid=<?php echo base64_encode('presentacion/dueño/nuevodueño.php'); ?>" class="fw-semibold" style="color: #e67e22; text-decoration: underline;">¿Eres dueño nuevo? Regístrate aquí</a>
          </div>
        </div>
      </div>

    </div>
  </div>

</body>
</html>
