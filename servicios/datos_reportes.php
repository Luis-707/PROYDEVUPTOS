<?php
session_start();
header('Content-Type: application/json; charset=utf-8');

include_once "../clases/ReportesPlanillaAdmin.php";

$idEvalAdmin = isset($_GET['id_eval_admin']) ? (int)$_GET['id_eval_admin'] : 0;

if ($idEvalAdmin <= 0) {
    echo json_encode([
        "success" => false,
        "message" => "ID de evaluación inválido"
    ]);
    exit;
}

$reporte = new ReportesPlanillaAdmin($this);

// Obtener datos administrativos completos
$sqlDatos = ReportesPlanillaAdmin::sql_datos_evaluacion($idEvalAdmin);
$datos = $reporte->ejecutarConsulta($sqlDatos);

// Aplanar estructura
$info = [];
if (is_array($datos)) {
    if (isset($datos[0]) && is_array($datos[0])) {
        $info = isset($datos[0][0]) ? $datos[0][0] : $datos[0];
    } else {
        $info = $datos;
    }
}

if (empty($info)) {
    echo json_encode([
        "success" => false,
        "message" => "No se encontraron datos para esta evaluación"
    ]);
    exit;
}

// Objetivos
$sqlObj = ReportesPlanillaAdmin::sql_objetivos($idEvalAdmin);
$objetivosR = $reporte->ejecutarConsulta($sqlObj);
$objetivos = (isset($objetivosR[0]) && is_array($objetivosR[0])) ? $objetivosR[0] : $objetivosR;

// Competencias
$sqlComp = ReportesPlanillaAdmin::sql_competencias($idEvalAdmin);
$competenciasR = $reporte->ejecutarConsulta($sqlComp);
$competencias = (isset($competenciasR[0]) && is_array($competenciasR[0])) ? $competenciasR[0] : $competenciasR;

echo json_encode([
    "success"      => true,
    "data"         => $info,
    "objetivos"    => $objetivos,
    "competencias" => $competencias
]);
exit;
?>