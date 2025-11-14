<?php
error_reporting(E_ALL);
ini_set('display_errors', '0');
ini_set('display_startup_errors', '0');

header('Content-Type: application/json; charset=utf-8');

include_once "../clases/PlanillaAdministrativos.php";

try {
    $planilla = new PlanillaAdministrativos($dataCliente['_post'], $this->conexion);

    // 1) Buscar evaluación existente
    $sql = $planilla->sql_buscar();
    $respuesta = $this->ejecutarConsultaBdds($sql);
    if (empty($respuesta) || empty($respuesta[0])) {
        echo json_encode([
            'success' => false,
            'message' => '❌ No existe evaluación previa para este evaluado'
        ]);
        exit;
    }

    $idEvalAdmin = (int)$respuesta[0][0]['id_eval_admin'];

    // 2) Actualizar evaluación general (puntaje y rango)
    $sqlEval = $planilla->sql_actualizar_evaluacion();
    $resEval = $this->ejecutarConsultaBdds($sqlEval);

    if (empty($resEval) || empty($resEval[0])) {
        echo json_encode([
            'success' => false,
            'message' => '❌ No se pudo actualizar la evaluación general'
        ]);
        exit;
    }

    $idEvalAdmin = (int)$resEval[0][0]['id_eval_admin'];

    // 3) Actualizar objetivos
    if (!empty($dataCliente['_post']['objetivos'])) {
        $objetivos = json_decode($dataCliente['_post']['objetivos'], true);
        foreach ($objetivos as $obj) {
            if (!empty($obj['id_odi'])) {
                $sqlObj = $planilla->sql_actualizar_objetivo(
                    $idEvalAdmin,
                    (int)$obj['id_odi'],
                    (int)$obj['rango'],
                    (int)$obj['pesoXRango']
                );
                $this->ejecutarConsultaBdds($sqlObj);
            }
        }
    }

    // 4) Actualizar competencias
    if (!empty($dataCliente['_post']['competencias'])) {
        $competencias = json_decode($dataCliente['_post']['competencias'], true);
        foreach ($competencias as $comp) {
            if (!empty($comp['id_competencia'])) {
                $sqlComp = $planilla->sql_actualizar_competencia(
                    $idEvalAdmin,
                    (int)$comp['id_competencia'],
                    (int)$comp['rango'],
                    (int)$comp['pesoXRango']
                );
                $this->ejecutarConsultaBdds($sqlComp);
            }
        }
    }

    // 5) Respuesta final
    echo json_encode([
        'success' => true,
        'message' => '✅ Evaluación, objetivos y competencias actualizados con éxito',
        'id_eval_admin' => $idEvalAdmin
    ]);

} catch (Throwable $e) {
    echo json_encode([
        'success' => false,
        'message' => 'Error en el servidor: ' . $e->getMessage()
    ]);
}
exit;