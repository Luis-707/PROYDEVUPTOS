<?php
include_once "../clases/PlanillaAdministrativos.php";

header('Content-Type: application/json; charset=utf-8');

// Normalizar entrada
$data = $_POST;

$idEvalAdmin  = isset($data['id_eval_admin']) ? (int)$data['id_eval_admin'] : 0;
$evaluado_id  = isset($data['evaluado_id']) ? (int)$data['evaluado_id'] : 0;
$evaluador_id = isset($data['evaluador_id']) ? (int)$data['evaluador_id'] : 0;

// Validación mínima
if ($idEvalAdmin <= 0 || $evaluado_id <= 0 || $evaluador_id <= 0) {
    echo json_encode(0);
    exit;
}

// SQL
$sql = PlanillaAdministrativos::sql_buscar_evaluacion($idEvalAdmin, $evaluado_id, $evaluador_id);

// Ejecutar
$respuesta = $this->ejecutarConsultaBdds($sql);

// Retornar 0 si no existe, 1 si existe
echo json_encode(!empty($respuesta) ? 1 : 0);
exit;