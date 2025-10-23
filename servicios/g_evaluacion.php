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

    // ⚠️ validar estructura: ejecutarConsultaBdds devuelve array de arrays
    if (empty($respuesta) || empty($respuesta[0])) {
        echo json_encode([
            'success' => false,
            'message' => '❌ No existe una evaluación previa para este evaluado y evaluador'
        ]);
        exit;
    }

    // 2) Tomar el id_eval_admin
    $planilla->id_eval_admin = (int)$respuesta[0][0]['id_eval_admin'];

    // 3) Actualizar evaluación general
    $sqlEval = $planilla->sql_guardar_evaluacion();
    $resEval = $this->ejecutarConsultaBdds($sqlEval);

    if (empty($resEval) || empty($resEval[0])) {
        echo json_encode([
            'success' => false,
            'message' => '❌ No se pudo actualizar la evaluación'
        ]);
        exit;
    }

    $idEvalAdmin = (int)$resEval[0][0]['id_eval_admin'];

    // 4) Guardar objetivos
    if (!empty($dataCliente['_post']['objetivos'])) {
        $objetivos = json_decode($dataCliente['_post']['objetivos'], true);
        foreach ($objetivos as $obj) {
            if (!empty($obj['id_odi'])) {
                $sqlObj = $planilla->sql_guardar_objetivo(
                    $idEvalAdmin,
                    (int)$obj['id_odi'],
                    (int)$obj['rango'],
                    (int)$obj['pesoXRango']
                );
                $this->ejecutarConsultaBdds($sqlObj);
            }
        }
    }

    // 5) Guardar competencias
    if (!empty($dataCliente['_post']['competencias'])) {
        $competencias = json_decode($dataCliente['_post']['competencias'], true);
        foreach ($competencias as $comp) {
            if (!empty($comp['id_competencia'])) {
                $sqlComp = $planilla->sql_guardar_competencia(
                    $idEvalAdmin,
                    (int)$comp['id_competencia'],
                    (int)$comp['rango'],
                    (int)$comp['pesoXRango']
                );
                $this->ejecutarConsultaBdds($sqlComp);
            }
        }
    }

    // 6) Respuesta final
    echo json_encode([
        'success' => true,
        'message' => '✅ Evaluación actualizada con éxito',
        'id_eval_admin' => $idEvalAdmin
    ]);

} catch (Throwable $e) {
    echo json_encode([
        'success' => false,
        'message' => 'Error en el servidor: ' . $e->getMessage()
    ]);
}
exit;