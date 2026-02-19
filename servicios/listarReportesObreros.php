<?php
session_start();
header('Content-Type: application/json; charset=utf-8');

include_once "../clases/ReportesPlanillaObreros.php";

$idUsuarioSesion = $_SESSION['usuario']['id_usuario'] ?? null;
$rolesSesion     = $_SESSION['usuario']['roles'] ?? [];

if (!$idUsuarioSesion || empty($rolesSesion)) {
    echo json_encode(["success" => false, "message" => "Usuario no autenticado"]);
    exit;
}

try {
    $reporte = new ReportesPlanillaObreros($this);

    $res = $reporte->listarReportesObrerosFiltrado($idUsuarioSesion, $rolesSesion);
    $data = $res[0] ?? $res;

    echo json_encode([
        "success" => true,
        "data" => $data
    ]);

} catch (Throwable $e) {
    echo json_encode([
        "success" => false,
        "message" => "Error en el servidor: " . $e->getMessage()
    ]);
}

exit;
