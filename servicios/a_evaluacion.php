<?php
session_start();
include_once "../clases/PlanillaAdministrativos.php";

header('Content-Type: application/json; charset=utf-8');

function normalizarRespuesta($resp) {
    if (isset($resp[0][0])) return $resp[0][0];
    if (isset($resp[0])) return $resp[0];
    return [];
}

try {

    $data = $_POST;

    if (
        empty($data['id_eval_admin']) ||
        empty($data['evaluador_id']) ||
        empty($data['evaluado_id'])
    ) {
        echo json_encode([
            'success' => false,
            'message' => 'Faltan parámetros obligatorios'
        ]);
        exit;
    }

    $planilla = new PlanillaAdministrativos($data, $this->conexion);

    // 1) Verificar que exista evaluación
    $sql = PlanillaAdministrativos::sql_buscar(
        (int)$data['id_eval_admin'],
        (int)$data['evaluado_id'],
        (int)$data['evaluador_id']
    );

    $resp = $this->ejecutarConsultaBdds($sql);
    $eval = normalizarRespuesta($resp);

    if (empty($eval['id_eval_admin'])) {
        echo json_encode([
            'success' => false,
            'message' => 'No existe evaluación previa'
        ]);
        exit;
    }

    // 2) Actualizar evaluación general
    $sqlEval = $planilla->sql_actualizar_evaluacion();
    $resEval = $this->ejecutarConsultaBdds($sqlEval);
    $rowEval = normalizarRespuesta($resEval);

    $idEvalAdmin = (int)$rowEval['id_eval_admin'];

    // 3) Actualizar objetivos
    if (!empty($data['objetivos'])) {
        $objetivos = json_decode($data['objetivos'], true);

        foreach ($objetivos as $obj) {
            $sqlObj = $planilla->sql_actualizar_objetivo(
                $idEvalAdmin,
                (int)$obj['id_odi'],
                (int)$obj['rango'],
                (int)$obj['pesoXRango']
            );
            $this->ejecutarConsultaBdds($sqlObj);
        }
    }

    // 4) Actualizar competencias
    if (!empty($data['competencias'])) {
        $competencias = json_decode($data['competencias'], true);

        foreach ($competencias as $comp) {
            $sqlComp = $planilla->sql_actualizar_competencia(
                $idEvalAdmin,
                (int)$comp['id_competencia'],
                (int)$comp['rango'],
                (int)$comp['pesoXRango']
            );
            $this->ejecutarConsultaBdds($sqlComp);
        }
    }

    echo json_encode([
        'success' => true,
        'message' => 'Evaluación actualizada con éxito',
        'id_eval_admin' => $idEvalAdmin
    ]);

} catch (Throwable $e) {
    echo json_encode([
        'success' => false,
        'message' => 'Error en el servidor: ' . $e->getMessage()
    ]);
}
exit;