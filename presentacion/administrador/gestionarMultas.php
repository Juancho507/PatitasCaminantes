<?php
$id = $_SESSION["id"];
$rol = $_SESSION["rol"];

include("presentacion/encabezadoA.php");
include("presentacion/menu" . ucfirst($rol) . ".php");
?>
<div class="container mt-4">
    <h2 class="mb-4"><i class="fas fa-exclamation-triangle me-2"></i>Gestionar Multas</h2>
    <div class="alert alert-info">
        <i class="fas fa-info-circle me-2"></i>
        Las multas ahora se gestionan mediante el bloqueo automático de cuentas. Cuando un dueño cancela un paseo con menos de 2 horas de anticipación, su cuenta es bloqueada automáticamente. El administrador puede reactivar la cuenta desde la sección de gestión de dueños.
    </div>
    <a href="?pid=<?php echo base64_encode("presentacion/administrador/gestionarDueños.php"); ?>" class="btn btn-primary">
        <i class="fas fa-users"></i> Ir a Gestionar Dueños
    </a>
</div>
