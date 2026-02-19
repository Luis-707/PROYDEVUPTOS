<?php
error_reporting(E_ALL);
ini_set('display_errors', '0');
ini_set('display_startup_errors', '0');

header('Content-Type: application/json; charset=utf-8');

include_once "../clases/ResultadosAdmin.php";

function normalizarRespuesta($resp) {
    if (isset($resp[0][0])) {
        return $resp[0][0];
    } elseif (isset($resp[0])) {
        return $resp[0];
    }
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

    if (empty($dataCliente['_post']['id_eval_admin'])) {
        echo json_encode([
            'success' => false,
            'message' => 'Falta parámetro: id_eval_admin'
        ]);
        exit;
    }

    $idEvalAdmin = (int)$dataCliente['_post']['id_eval_admin'];

    // Clase con conexión del controlador
    $Resultados = new ResultadosAdmin($this);

    // 1) Evaluación (cabecera + resultado final)
    $sqlEval   = ResultadosAdmin::sql_evaluacion_detalle($idEvalAdmin);
    $evalResp  = $Resultados->ejecutar($sqlEval);
    $evalData  = normalizarRespuesta($evalResp);

    if (empty($evalData['id_eval_admin'])) {
        echo json_encode([
            'success' => false,
            'message' => 'No se encontró evaluación registrada'
        ]);
        exit;
    }

    // 2) Objetivos
    $sqlObj     = ResultadosAdmin::sql_objetivos_resultados($idEvalAdmin);
    $objResp    = $Resultados->ejecutar($sqlObj);
    $objetivos  = isset($objResp[0]) ? $objResp[0] : [];

    // 3) Competencias
    $sqlComp       = ResultadosAdmin::sql_competencias_resultados($idEvalAdmin);
    $compResp      = $Resultados->ejecutar($sqlComp);
    $competencias  = isset($compResp[0]) ? $compResp[0] : [];

    // 4) Relaciones (evaluado, evaluador, supervisor)
    $sqlRel      = ResultadosAdmin::sql_relaciones_resultados($idEvalAdmin);
    $relResp     = $Resultados->ejecutar($sqlRel);
    $relaciones  = normalizarRespuesta($relResp);

    echo json_encode([
        'success' => true,
        'message' => 'Datos cargados correctamente',
        'data' => [
            'evaluacion'   => $evalData,
            'objetivos'    => $objetivos,
            'competencias' => $competencias,
            'relaciones'   => $relaciones
        ]
    ]);

} catch (Throwable $e) {
    echo json_encode([
        'success' => false,
        'message' => 'Error en el servidor: ' . $e->getMessage()
    ]);
}
exit;