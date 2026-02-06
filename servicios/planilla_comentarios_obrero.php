<?php
session_start();
include_once "../clases/Planilla_comentarios_obreros.php";

header('Content-Type: application/json; charset=utf-8');

function normalizarRespuesta($resp) {
    if (isset($resp[0][0])) return $resp[0][0];
    if (isset($resp[0])) return $resp[0];
    return [];
}

try {

    if (!isset($dataCliente)) {
        $dataCliente = ['_post' => $_POST];
        if (empty($dataCliente['_post'])) {
            $json = file_get_contents("php://input");
            $dataCliente['_post'] = json_decode($json, true) ?? [];
        }
    }

    if (empty($dataCliente['_post']['id_eval_obreros']) || empty($dataCliente['_post']['cedula_usuario'])) {
        echo json_encode([
            'success' => false,
            'message' => 'Faltan parámetros: id_eval_obreros y/o cedula_usuario'
        ]);
        exit;
    }

    $idEvalObreros = (int)$dataCliente['_post']['id_eval_obreros'];
    $cedula        = trim($dataCliente['_post']['cedula_usuario']);

    $planilla = new Planilla_comentarios_obreros([
        'id_eval_obreros' => $idEvalObreros,
        'cedula_usuario'  => $cedula
    ], $this->conexion);

    // 1. Evaluación
    $sqlEval = $planilla->sql_buscar_obrero();
    $evaluaciones = $this->ejecutarConsultaBdds($sqlEval);
    $evalData = normalizarRespuesta($evaluaciones);

    if (empty($evalData['id_eval_obreros'])) {
        echo json_encode([
            'success' => false,
            'message' => 'No se encontró evaluación obrera registrada'
        ]);
        exit;
    }

    $planilla->setIdEvalObrero((int)$evalData['id_eval_obreros']);

    // 2. Relaciones
    $sqlRel = $planilla->sql_relaciones_por_cedula_obrero($cedula);
    $relaciones = $this->ejecutarConsultaBdds($sqlRel);
    $relacionData = normalizarRespuesta($relaciones);

    // 3. Factores completos
    $sqlFactores = Planilla_comentarios_obreros::sql_factores_completos();
    $factores = $this->ejecutarConsultaBdds($sqlFactores);
    $factores = $factores[0] ?? [];

    // 4. Criterios completos
    $sqlCriterios = Planilla_comentarios_obreros::sql_criterios_completos();
    $criterios = $this->ejecutarConsultaBdds($sqlCriterios);
    $criterios = $criterios[0] ?? [];

    // 5. Criterios seleccionados
    $sqlSel = Planilla_comentarios_obreros::sql_criterios_seleccionados($idEvalObreros);
    $seleccionados = $this->ejecutarConsultaBdds($sqlSel);
    $seleccionados = $seleccionados[0] ?? [];

    echo json_encode([
        'success' => true,
        'message' => 'Datos cargados correctamente',
        'data' => [
            'evaluacion'   => $evalData,
            'relaciones'   => $relacionData,
            'factores'     => $factores,
            'criterios'    => $criterios,
            'seleccionados'=> $seleccionados
        ]
    ]);

} catch (Throwable $e) {
    echo json_encode([
        'success' => false,
        'message' => 'Error en el servidor: ' . $e->getMessage()
    ]);
}

exit;
