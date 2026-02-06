<?php
session_start();
header('Content-Type: application/json; charset=utf-8');

include_once "../clases/ReportesPlanillaObreros.php";

$idEvalObrero = isset($_GET['id_eval_obreros']) ? (int)$_GET['id_eval_obreros'] : 0;
if ($idEvalObrero <= 0) {
    echo json_encode(["success" => false, "message" => "ID inválido"]);
    exit;
}

$reporte = new ReportesPlanillaObreros($this);

// Datos generales
$sqlDatos = ReportesPlanillaObreros::sql_datos_evaluacion($idEvalObrero);
$datos    = $reporte->ejecutarConsulta($sqlDatos);

$info = $datos[0][0] ?? $datos[0] ?? [];

if (empty($info)) {
    echo json_encode(["success" => false, "message" => "No se encontraron datos de evaluación"]);
    exit;
}

// Factores
$sqlFact = ReportesPlanillaObreros::sql_factores();
$factoresR = $reporte->ejecutarConsulta($sqlFact);
$factores = $factoresR[0] ?? $factoresR;

// Criterios
$sqlCrit = ReportesPlanillaObreros::sql_criterios();
$criteriosR = $reporte->ejecutarConsulta($sqlCrit);
$criterios = $criteriosR[0] ?? $criteriosR;

// Seleccionados
$sqlSel = ReportesPlanillaObreros::sql_criterios_seleccionados($idEvalObrero);
$selR = $reporte->ejecutarConsulta($sqlSel);
$seleccionados = $selR[0] ?? $selR;

echo json_encode([
    "success"       => true,
    "data"          => $info,
    "factores"      => $factores,
    "criterios"     => $criterios,
    "seleccionados" => $seleccionados
]);

exit;