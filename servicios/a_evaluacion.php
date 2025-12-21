<?php
error_reporting(E_ALL);
ini_set('display_errors', '0');
ini_set('display_startup_errors', '0');

header('Content-Type: application/json; charset=utf-8');

include_once "../clases/PlanillaAdministrativos.php";

function normalizarRespuesta($resp) {
    if (isset($resp[0][0])) {
        return $resp[0][0];
    } elseif (isset($resp[0])) {
        return $resp[0];
    }
    return [];
}

try {
    // Inicializar dataCliente
    if (!isset($dataCliente)) {
        $dataCliente = ['_post' => $_POST];
        if (empty($dataCliente['_post'])) {
            $json = file_get_contents("php://input");
            $dataCliente['_post'] = json_decode($json, true) ?? [];
        }
    }

    // Validar parámetros obligatorios
    if (empty($dataCliente['_post']['id_eval_admin']) || empty($dataCliente['_post']['id_usuario']) || empty($dataCliente['_post']['id_evaluado'])) {
        echo json_encode([
            'success' => false,
            'message' => 'Faltan parámetros obligatorios: id_eval_admin, id_usuario o id_evaluado'
        ]);
        exit;
    }

    $idEvalAdminPost = (int)$dataCliente['_post']['id_eval_admin'];

    // Instanciar clase con los datos recibidos
    $planilla = new PlanillaAdministrativos($dataCliente['_post'], $this->conexion);

    // 1) Buscar evaluación existente (pasando el argumento)
    $sql = $planilla->sql_buscar($idEvalAdminPost);
    $respuesta = $this->ejecutarConsultaBdds($sql);
    $evalData = normalizarRespuesta($respuesta);

    if (empty($evalData['id_eval_admin'])) {
        echo json_encode([
            'success' => false,
            'message' => '❌ No existe evaluación previa para este evaluado'
        ]);
        exit;
    }

    $idEvalAdmin = (int)$evalData['id_eval_admin'];
    $planilla->setIdEvalAdmin($idEvalAdmin);

    // 2) Actualizar evaluación general (puntaje y rango)
    $sqlEval = $planilla->sql_actualizar_evaluacion();
    $resEval = $this->ejecutarConsultaBdds($sqlEval);
    $rowEval = normalizarRespuesta($resEval);

    if (empty($rowEval['id_eval_admin'])) {
        echo json_encode([
            'success' => false,
            'message' => '❌ No se pudo actualizar la evaluación general'
        ]);
        exit;
    }

    $idEvalAdmin = (int)$rowEval['id_eval_admin'];

    // 3) Actualizar objetivos
    if (!empty($dataCliente['_post']['objetivos'])) {
        $objetivos = is_string($dataCliente['_post']['objetivos'])
            ? json_decode($dataCliente['_post']['objetivos'], true)
            : $dataCliente['_post']['objetivos'];

        if (is_array($objetivos)) {
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
    }

    // 4) Actualizar competencias
    if (!empty($dataCliente['_post']['competencias'])) {
        $competencias = is_string($dataCliente['_post']['competencias'])
            ? json_decode($dataCliente['_post']['competencias'], true)
            : $dataCliente['_post']['competencias'];

        if (is_array($competencias)) {
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