<?php
header('Content-Type: application/json');
session_start();

error_reporting(E_ALL);
ini_set('display_errors', 1);

ob_start();
require_once(__DIR__ . "/../logica/Paseo.php");
$output = ob_get_clean();

$response = [
    "session" => $_SESSION,
    "include_output" => $output,
    "test" => "ok"
];
echo json_encode($response);
