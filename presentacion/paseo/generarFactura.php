<?php
session_start();
if (!isset($_SESSION["id"]) || !isset($_SESSION["rol"])) {
    header("HTTP/1.0 403 Forbidden");
    echo "No autorizado";
    exit;
}
$idUsuario = (int)$_SESSION["id"];
$rol = $_SESSION["rol"];

require_once(__DIR__ . "/../../persistencia/Conexion.php");
require_once(__DIR__ . "/../../fpdf/fpdf.php");
require_once(__DIR__ . "/../../phpqrcode/qrlib.php");

$idPaseo = isset($_GET["idPaseo"]) ? (int)$_GET["idPaseo"] : 0;
if ($idPaseo <= 0) {
    die("ID de paseo inválido.");
}

$conexion = new Conexion();
$conexion->abrir();

$conexion->ejecutar("SELECT EstadoPaseo_idEstadoPaseo FROM paseo WHERE idPaseo = $idPaseo");
$estadoPaseo = $conexion->registro();
if (!$estadoPaseo || (int)$estadoPaseo[0] !== 4) {
    $conexion->cerrar();
    die("La factura solo está disponible para paseos completados.");
}

$conexion->ejecutar("SELECT p.FechaInicio, p.FechaFin, p.Bozal,
    CONCAT(pa.Nombre, ' ', pa.Apellido) AS paseador, pa.Contacto AS contactoPaseador,
    CONCAT(d.Nombre, ' ', d.Apellido) AS dueño, d.Direccion, d.Contacto AS contactoDueño
    FROM paseo p
    INNER JOIN Paseador pa ON p.Paseador_idPaseador = pa.idPaseador
    INNER JOIN Perro per ON p.perro_idPerro = per.idPerro
    INNER JOIN Dueño d ON per.Dueño_idDueño = d.idDueño
    WHERE p.idPaseo = $idPaseo");

$paseo = $conexion->registro();
if (!$paseo) {
    $conexion->cerrar();
    die("Paseo no encontrado.");
}

$fechaInicio = $paseo[0];
$fechaFin = $paseo[1];
$bozal = $paseo[2];
$nombrePaseador = $paseo[3];
$contactoPaseador = $paseo[4];
$nombreDueño = $paseo[5];
$direccionDueño = $paseo[6];
$contactoDueño = $paseo[7];

$fInicio = new DateTime($fechaInicio);
$fFin = new DateTime($fechaFin);
$duracionMinutos = max(60, ($fFin->getTimestamp() - $fInicio->getTimestamp()) / 60);
$horasCobrar = ceil($duracionMinutos / 60);

$conexion->ejecutar("SELECT Paseador_idPaseador FROM paseo WHERE idPaseo = $idPaseo");
$paseoData = $conexion->registro();
$idPaseadorPerro = (int)$paseoData[0];

$conexion->ejecutar("SELECT perro_idPerro, perro_idPerro2, perro_idPerro3, perro_idPerro4, perro_idPerro5, perro_idPerro6 FROM paseo WHERE idPaseo = $idPaseo");
$paseoData2 = $conexion->registro();
$perroIds = [];
for ($i = 0; $i < 6; $i++) {
    if (!empty($paseoData2[$i])) $perroIds[] = (int)$paseoData2[$i];
}

$perrosPaseo = [];
$precioHora = 0;
if (!empty($perroIds)) {
    $idsStr = implode(",", $perroIds);
    $conexion->ejecutar("SELECT per.Nombre, per.Peso, r.Raza, pg.Nivel, t.PrecioHora
        FROM Perro per
        INNER JOIN Raza r ON per.Raza_idRaza = r.idRaza
        INNER JOIN Peligrosidad pg ON per.Peligrosidad_idPeligrosidad = pg.idPeligrosidad
        INNER JOIN Tarifa t ON t.Paseador_idPaseador = $idPaseadorPerro
            AND t.Peligrosidad_idPeligrosidad = per.Peligrosidad_idPeligrosidad
        WHERE per.idPerro IN ($idsStr) AND per.Dueño_idDueño = $idUsuario AND t.Activa = 1");
    while ($tarifaReg = $conexion->registro()) {
        $perrosPaseo[] = [
            "nombre" => $tarifaReg[0],
            "peso" => $tarifaReg[1],
            "raza" => $tarifaReg[2],
            "peligrosidad" => $tarifaReg[3],
            "precioHora" => $tarifaReg[4]
        ];
        if ($tarifaReg[4] > $precioHora) $precioHora = $tarifaReg[4];
    }
}
$total = $horasCobrar * $precioHora;

$conexion->cerrar();

if (empty($perrosPaseo)) {
    die("No hay perros asociados a tu cuenta en este paseo.");
}

class PDFInvoice extends FPDF {
    function Header() {
        $this->Image(__DIR__ . '/../../img/patitas.png', 10, 8, 25);
        $this->SetFont('Arial', 'B', 16);
        $this->Cell(0, 10, 'PATITAS CAMINANTES', 0, 1, 'C');
        $this->SetFont('Arial', '', 10);
        $this->Cell(0, 6, 'Paseos personalizados para tu perrito', 0, 1, 'C');
        $this->Cell(0, 6, 'Factura #' . $_GET['idPaseo'], 0, 1, 'C');
        $this->Ln(5);
    }

    function Footer() {
        $this->SetY(-15);
        $this->SetFont('Arial', 'I', 8);
        $this->Cell(0, 10, 'Gracias por confiar en Patitas Caminantes - Pagina ' . $this->PageNo() . '/{nb}', 0, 0, 'C');
    }
}

$pdf = new PDFInvoice();
$pdf->AliasNbPages();
$pdf->AddPage();
$pdf->SetFont('Arial', '', 10);

$pdf->SetFont('Arial', 'B', 12);
$pdf->Cell(0, 8, 'INFORMACION DEL DUEÑO', 0, 1);
$pdf->SetFont('Arial', '', 10);
$pdf->Cell(0, 6, 'Nombre: ' . $nombreDueño, 0, 1);
$pdf->Cell(0, 6, 'Direccion: ' . $direccionDueño, 0, 1);
$pdf->Cell(0, 6, 'Contacto: ' . $contactoDueño, 0, 1);
$pdf->Ln(5);

$pdf->SetFont('Arial', 'B', 12);
$pdf->Cell(0, 8, 'INFORMACION DEL PASEADOR', 0, 1);
$pdf->SetFont('Arial', '', 10);
$pdf->Cell(0, 6, 'Nombre: ' . $nombrePaseador, 0, 1);
$pdf->Cell(0, 6, 'Contacto: ' . $contactoPaseador, 0, 1);
$pdf->Ln(5);

$pdf->SetFont('Arial', 'B', 12);
$pdf->Cell(0, 8, 'TUS PERROS EN ESTE PASEO', 0, 1);
$pdf->SetFont('Arial', '', 10);
foreach ($perrosPaseo as $perroItem) {
    $pdf->Cell(0, 6, '- ' . $perroItem["nombre"] . ' (' . $perroItem["raza"] . ', ' . $perroItem["peso"] . ' kg, ' . $perroItem["peligrosidad"] . ')', 0, 1);
}
$pdf->Ln(5);

$pdf->SetFont('Arial', 'B', 12);
$pdf->Cell(0, 8, 'DETALLE DEL PASEO', 0, 1);
$pdf->SetFont('Arial', '', 10);
$pdf->Cell(0, 6, 'Fecha: ' . $fInicio->format('d/m/Y'), 0, 1);
$pdf->Cell(0, 6, 'Hora Inicio: ' . $fInicio->format('H:i'), 0, 1);
$pdf->Cell(0, 6, 'Hora Fin: ' . $fFin->format('H:i'), 0, 1);
$pdf->Cell(0, 6, 'Duracion: ' . $duracionMinutos . ' min (' . $horasCobrar . ' hora(s) facturada(s))', 0, 1);
$pdf->Cell(0, 6, 'Bozal: ' . ($bozal ? 'Si' : 'No'), 0, 1);
$pdf->Ln(5);

$pdf->SetFont('Arial', 'B', 12);
$pdf->Cell(0, 8, 'DETALLE DE COBRO', 0, 1);
$pdf->SetFont('Arial', '', 10);

$pdf->Cell(80, 7, 'Concepto', 1);
$pdf->Cell(30, 7, 'Cantidad', 1, 0, 'C');
$pdf->Cell(40, 7, 'Valor Unit.', 1, 0, 'C');
$pdf->Cell(40, 7, 'Total', 1, 1, 'C');

foreach ($perrosPaseo as $perroItem) {
    $pdf->Cell(80, 7, $perroItem["nombre"] . ' (' . $perroItem["peligrosidad"] . ')', 1);
    $pdf->Cell(30, 7, $horasCobrar . ' hora(s)', 1, 0, 'C');
    $pdf->Cell(40, 7, '$' . number_format($perroItem["precioHora"], 0, ',', '.'), 1, 0, 'C');
    $pdf->Cell(40, 7, '$' . number_format($horasCobrar * $perroItem["precioHora"], 0, ',', '.'), 1, 1, 'C');
}

$pdf->Ln(5);
$pdf->SetFont('Arial', 'B', 14);
$pdf->Cell(0, 10, 'TOTAL: $' . number_format($total, 0, ',', '.'), 0, 1, 'R');

$qrData = "Factura Patitas Caminantes\n";
$qrData .= "#: " . $idPaseo . "\n";
$qrData .= "Fecha: " . $fInicio->format('d/m/Y H:i') . "\n";
$qrData .= "Cliente: " . $nombreDueño . "\n";
$qrData .= "Paseador: " . $nombrePaseador . "\n";
$qrData .= "Tus perros: " . implode(", ", array_column($perrosPaseo, "nombre")) . "\n";
$qrData .= "Total: $" . number_format($total, 0, ',', '.');

$qrFile = __DIR__ . '/../../temp/qr_' . $idPaseo . '.png';
if (!is_dir(__DIR__ . '/../../temp')) {
    mkdir(__DIR__ . '/../../temp', 0777, true);
}
QRcode::png($qrData, $qrFile, QR_ECLEVEL_L, 4);
$pdf->Image($qrFile, 160, $pdf->GetY() + 5, 30, 30);

$pdf->Output('D', 'Factura_' . $idPaseo . '.pdf');

if (file_exists($qrFile)) {
    unlink($qrFile);
}
?>
