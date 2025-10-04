<?php
error_reporting(E_ALL);
ini_set('display_errors', '0');
ini_set('display_startup_errors', '0');

header('Content-Type: application/json; charset=utf-8');

include_once "../clases/PlanillaAdministrativos.php";

try {
    $planilla = new PlanillaAdministrativos($dataCliente['_post'], $this->conexion);

    // Buscar si ya existe evaluación
    $sql = $planilla->sql_buscar();
    $respuesta = $this->ejecutarConsultaBdds($sql);

    if (count($respuesta) == 0) {
       // Guardar evaluación general
$sqlEval = $planilla->sql_guardar_evaluacion();
$resEval = $this->ejecutarConsultaBdds($sqlEval);

// ⚠️ Capturamos el id_eval_admin generado
$idEvalAdmin = $resEval[0][0]['id_eval_admin'] ?? null;

// Guardar objetivos (no necesitan id_eval_admin)
if (!empty($dataCliente['_post']['objetivos'])) {
    $objetivos = json_decode($dataCliente['_post']['objetivos'], true);
    foreach ($objetivos as $obj) {
        $sqlObj = $planilla->sql_guardar_objetivo(
            $obj['id_odi'],
            $obj['rango'],
            $obj['pesoXRango']
        );
        $this->ejecutarConsultaBdds($sqlObj);
    }
}

// Guardar competencias (sí necesitan id_eval_admin)
if (!empty($dataCliente['_post']['competencias']) && $idEvalAdmin) {
    $competencias = json_decode($dataCliente['_post']['competencias'], true);
    foreach ($competencias as $comp) {
        $sqlComp = $planilla->sql_guardar_competencia(
            $idEvalAdmin, // 👈 aquí lo pasamos
            $comp['id_competencia'],
            $comp['rango'],
            $comp['pesoXRango']
        );
        $this->ejecutarConsultaBdds($sqlComp);
    }
}
        echo json_encode([
            'success' => true,
            'message' => 'Evaluación guardada con éxito'
        ]);
    } else {
        echo json_encode([
            'success' => false,
            'message' => 'Ya existe una evaluación calificada para este empleado'
        ]);
    }
} catch (Throwable $e) {
    echo json_encode([
        'success' => false,
        'message' => 'Error en el servidor: ' . $e->getMessage()
    ]);
}
exit;