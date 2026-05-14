<?php
ob_start();
require_once(__DIR__ . "/../../fpdf/fpdf.php");
include(__DIR__ . "/../../phpqrcode/qrlib.php");


$dueñoId = $_SESSION["id"] ?? null;
$idPerro = $_GET["idPerro"] ?? 0;

if (!$idPerro || !$dueñoId) {
    exit("Datos inválidos para generar la factura.");
}

$dueño = new Dueño($dueñoId);
$dueño->consultar();
$nombreDueño = $dueño->getNombre();
$perro = new Perro($idPerro);
$perroEncontrado = $perro->consultarPerroPorId($idPerro);

$nombrePerro = "";
if ($perroEncontrado) {
    $nombrePerro = $perroEncontrado->getNombre();
} else {
    $nombrePerro = "Desconocido";
}
$paseo = new Paseo();
$paseos = $paseo->consultarPaseosCompletadosPorPerro($idPerro);

$pdf = new FPDF();
$pdf->AddPage();
$pdf->Image("img/logo.png", 10, 10, 30);
$pdf->SetY(20);
$pdf->SetFont("Arial", "B", 18);
$pdf->SetTextColor(0);
$pdf->Cell(0, 15, "DoggyToons - Factura de Paseos", 0, 1, "C");

$pdf->Ln(20);


if(empty($paseos)) { 
    $pdf->SetFont("Arial", "B", 14);
    $pdf->Cell(0, 10, "Perrito: " . $nombrePerro, 0, 1, "L"); 
    $pdf->Ln(4);
    $pdf->SetFont("Arial", "B", 12);
    $pdf->SetFillColor(230, 230, 250);
    $pdf->Cell(20, 10, "ID", 1, 0, 'C', true);
    $pdf->Cell(30, 10, "Fecha", 1, 0, 'C', true);
    $pdf->Cell(30, 10, "Hora Ini.", 1, 0, 'C', true);
    $pdf->Cell(30, 10, "Hora Fin", 1, 0, 'C', true);
    $pdf->Cell(50, 10, "Paseador", 1, 0, 'C', true);
    $pdf->Cell(30, 10, "Precio", 1, 1, 'C', true);
    $pdf->SetFont("Arial", "I", 12);
    $pdf->Ln(10);
    $pdf->Cell(0, 10, "Este perrito aun no tiene paseos completados para facturar.", 0, 1, "C");
} else {
    $pdf->SetFont("Arial", "B", 14);
    $pdf->Cell(0, 10, "Perrito: " . $nombrePerro, 0, 1, "L"); 
    $pdf->Ln(4);
    $pdf->SetFont("Arial", "B", 12);
    $pdf->SetFillColor(230, 230, 250);
    $pdf->Cell(20, 10, "ID", 1, 0, 'C', true);
    $pdf->Cell(30, 10, "Fecha", 1, 0, 'C', true);
    $pdf->Cell(30, 10, "Hora Ini.", 1, 0, 'C', true);
    $pdf->Cell(30, 10, "Hora Fin", 1, 0, 'C', true);
    $pdf->Cell(50, 10, "Paseador", 1, 0, 'C', true);
    $pdf->Cell(30, 10, "Precio", 1, 1, 'C', true);
    $pdf->SetFont("Arial", "", 11);
    
    $totalPrecio = 0;
    foreach ($paseos as $paseoItem) {
        $fechaInicio = new DateTime($paseoItem->getFechaInicio());
        $fechaFin = new DateTime($paseoItem->getFechaFin());
        
        $pdf->Cell(20, 10, $paseoItem->getId(), 1, 0, 'C');
        $pdf->Cell(30, 10, $fechaInicio->format("Y-m-d"), 1, 0, 'C');
        $pdf->Cell(30, 10, $fechaInicio->format("H:i"), 1, 0, 'C');
        $pdf->Cell(30, 10, $fechaFin->format("H:i"), 1, 0, 'C');
        $pdf->Cell(50, 10, $paseoItem->getPaseador(), 1, 0, 'C');
        $pdf->Cell(30, 10, "$" . number_format($paseoItem->getPrecio(), 0, '', '.'), 1, 1, 'C');
        $totalPrecio += $paseoItem->getPrecio();
    }
    
    $pdf->Ln(5);
    $pdf->SetFont("Arial", "B", 12);
    $pdf->Cell(160, 10, "Total a Pagar:", 0, 0, 'R');
    $pdf->Cell(30, 10, "$" . number_format($totalPrecio, 0, '', '.'), 1, 1, 'C');
}

$pdf->Ln(10);
$pdf->SetFont("Arial", "I", 10);
$pdf->Cell(0, 10, "Gracias por confiar en DoggyToons", 0, 1, "C");

$mensajeQR = "Hola " . ($nombreDueño ? $nombreDueño : "cliente") . ", esta es la factura de tu perrito " . ($nombrePerro ? $nombrePerro : "");
QRcode::png($mensajeQR, "img/qr.png");
$anchoQR = 40;
$pdf->Image("img/qr.png", 165, 10, $anchoQR, $anchoQR);

ob_start();
$pdf->Output("I", "Factura_" . ($nombrePerro ? $nombrePerro : "sin_nombre") . ".pdf");
exit();

?>