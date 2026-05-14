<?php
if ($_SESSION["rol"] != "dueño") {
    header("Location: ?pid=" . base64_encode("presentacion/noAutorizado.php"));
    exit();
}


if (isset($_GET["id"])) {
    $id = $_GET["id"];
    
    $perroTemp = new Perro();
    $perro = $perroTemp->consultarPerroPorId($id); 
    
    if ($perro !== null) {
        if ($perro->getFoto() != "" && file_exists($perro->getFoto())) {
            unlink($perro->getFoto());
        }
        
        $perro->eliminar();
        
        header("Location: ?pid=" . base64_encode("presentacion/perro/consultarPerros.php") . "&eliminado=1");
        exit();
    } else {
        echo "<div class='alert alert-danger'>El perro no fue encontrado.</div>";
    }
} else {
    echo "<div class='alert alert-danger'>ID no proporcionado.</div>";
}
