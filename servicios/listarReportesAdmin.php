<?php
session_start();
header('Content-Type: application/json; charset=utf-8');

include_once "../clases/ReportesPlanillaAdmin.php";

$cedulaSesion = $_SESSION['usuario']['cedula'] ?? null;
$rolUsuario   = $_SESSION['usuario']['rol'] ?? null;

if (!$cedulaSesion || !$rolUsuario) {
    echo json_encode(["success" => false, "message" => "Usuario no autenticado"]);
    exit;
}

// conexion con la clase de reportes de planilla administrativos
$reporte = new ReportesPlanillaAdmin($this);

$respuesta = $reporte->listarReportesAdmin();

if (empty($respuesta)) { 
    echo json_encode(["success" => true, "data" => [], "message" => "No hay evaluaciones disponibles para reporte"]); 
    exit; 
} 

echo json_encode(["success" => true, "data" => $respuesta]);
exit;


