<?php
session_start();
include_once "../clases/Planilla_comentarios_obreros.php";

header('Content-Type: application/json; charset=utf-8');

// =============================
// Normalizar respuesta (igual que administrativos)
// =============================
function normalizarRespuesta($resp) {
    if (isset($resp[0][0])) {
        return $resp[0][0];
    } elseif (isset($resp[0])) {
        return $resp[0];
    }
    return [];
}

try {

    // =============================
    // Inicializar dataCliente (POST o JSON)
    // =============================
    if (!isset($dataCliente)) {
        $dataCliente = ['_post' => $_POST];
        if (empty($dataCliente['_post'])) {
            $json = file_get_contents("php://input");
            $dataCliente['_post'] = json_decode($json, true) ?? [];
        }
    }

    // =============================
    // Validar parámetros obligatorios
    // =============================
    if (empty($dataCliente['_post']['id_eval_obreros']) || empty($dataCliente['_post']['cedula_usuario'])) {
        echo json_encode([
            'success' => false,
            'message' => 'Faltan parámetros: id_eval_obreros y/o cedula_usuario'
        ]);
        exit;
    }

    $idEvalObreros = (int)$dataCliente['_post']['id_eval_obreros'];
    $cedula        = trim($dataCliente['_post']['cedula_usuario']);

    // =============================
    // Instanciar clase obrera
    // =============================
    $planilla = new Planilla_comentarios_obreros([
        'id_eval_obreros' => $idEvalObreros,
        'cedula_usuario'  => $cedula
    ], $this->conexion);

    // =============================
    // 1. Ejecutar consulta de evaluación obrera
    // =============================
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

    // =============================
    // 2. Relaciones obreras
    // =============================
    $sqlRel = $planilla->sql_relaciones_por_cedula_obrero($cedula);
    $relaciones = $this->ejecutarConsultaBdds($sqlRel);
    $relacionData = normalizarRespuesta($relaciones);

    // =============================
    // 3. Factores y criterios
    // =============================
    $sqlFact = $planilla->sql_factores_obrero($planilla->getIdEvalObrero());
    $factores = $this->ejecutarConsultaBdds($sqlFact);
    $factores = isset($factores[0][0]) ? $factores[0] : ($factores[0] ?? []);

    // =============================
    // 4. Fusionar cargos en evaluación (igual que administrativos)
    // =============================
    if ($relacionData) {
        $evalData['cargo_evaluado']   = $relacionData['cargo_evaluado'] ?? null;
        $evalData['cargo_evaluador']  = $relacionData['cargo_evaluador'] ?? null;
        $evalData['cargo_supervisor'] = $relacionData['cargo_supervisor'] ?? null;
    }

    // =============================
    // 5. Respuesta final (idéntica estructura al administrativo)
    // =============================
    echo json_encode([
        'success' => true,
        'message' => 'Datos cargados correctamente',
        'data' => [
            'evaluacion' => $evalData,
            'factores'   => $factores,
            'relaciones' => $relacionData
        ]
    ]);

} catch (Throwable $e) {
    echo json_encode([
        'success' => false,
        'message' => 'Error en el servidor: ' . $e->getMessage()
    ]);
}

exit;