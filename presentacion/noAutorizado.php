<body>
<div class="container mt-5 text-center">
    <div class="row justify-content-center">
        <div class="col-md-6">
            <div class="card shadow">
                <div class="card-body">
                    <h1 class="display-1 text-danger">403</h1>
                    <h3>Acceso No Autorizado</h3>
                    <p class="text-muted">No tienes permisos para acceder a esta página.</p>
                    <a href="?pid=<?php echo base64_encode('presentacion/autenticarse.php'); ?>" class="btn btn-primary">Volver al Inicio</a>
                </div>
            </div>
        </div>
    </div>
</div>
</body>
</html>
