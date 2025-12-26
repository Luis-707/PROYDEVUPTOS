<?php
error_reporting(E_ALL);
ini_set('display_errors', '0');
ini_set('display_startup_errors', '0');

header('Content-Type: application/json; charset=utf-8');

include_once "../clases/Planilla_resultados.php";

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
    if (empty($dataCliente['_post']['id_eval_admin']) || empty($dataCliente['_post']['cedula_usuario'])) {
        echo json_encode([
            'success' => false,
            'message' => 'Faltan parámetros: id_eval_admin y/o cedula_usuario'
        ]);
        exit;
    }

    $idEvalAdmin = (int)$dataCliente['_post']['id_eval_admin'];
    $cedula      = trim($dataCliente['_post']['cedula_usuario']);

    // Instanciar clase con ambos valores
    $planilla = new Planilla_resultados([
        'id_eval_admin'  => $idEvalAdmin,
        'cedula_usuario' => $cedula
    ], $this->conexion);

    // 1. Ejecutar consulta de evaluación
    $sqlEval = $planilla->sql_buscar_resultados();
    $evaluaciones = $this->ejecutarConsultaBdds($sqlEval);
    $evalData = normalizarRespuesta($evaluaciones);

    if (empty($evalData['id_eval_admin'])) {
        echo json_encode([
            'success' => false,
            'message' => 'No se encontró evaluación registrada'
        ]);
        exit;
    }

    $planilla->setIdEvalAdmin_resultados((int)$evalData['id_eval_admin']);

    // 2. Objetivos (ahora con cedula + id_eval_admin)
    $sqlObj = $planilla->sql_objetivos_resultados($cedula, $planilla->getIdEvalAdmin_resultados());
    $objetivos = $this->ejecutarConsultaBdds($sqlObj);
    $objetivos = isset($objetivos[0][0]) ? $objetivos[0] : ($objetivos[0] ?? []);

    // 3. Competencias (por id_eval_admin)
    $sqlComp = $planilla->sql_competencias_resultados($planilla->getIdEvalAdmin_resultados());
    $competencias = $this->ejecutarConsultaBdds($sqlComp);
    $competencias = isset($competencias[0][0]) ? $competencias[0] : ($competencias[0] ?? []);

    // 4. Relaciones (por cédula)
    $sqlRel = $planilla->sql_relaciones_resultados($cedula);
    $relaciones = $this->ejecutarConsultaBdds($sqlRel);
    $relacionData = isset($relaciones[0][0]) ? $relaciones[0][0] : ($relaciones[0] ?? null);

    // 5. Fusionar cargos en evaluación
    if ($relacionData) {
        $evalData['cargo_evaluado']   = $relacionData['cargo_evaluado'] ?? null;
        $evalData['cargo_evaluador']  = $relacionData['cargo_evaluador'] ?? null;
        $evalData['cargo_supervisor'] = $relacionData['cargo_supervisor'] ?? null;
    }

    // 6. Respuesta final
    echo json_encode([
        'success' => true,
        'message' => 'Datos cargados correctamente',
        'data' => [
            'evaluacion'   => $evalData,
            'objetivos'    => $objetivos,
            'competencias' => $competencias,
            'relaciones'   => $relacionData
        ]
    ]);

} catch (Throwable $e) {
    echo json_encode([
        'success' => false,
        'message' => 'Error en el servidor: ' . $e->getMessage()
    ]);
}
exit;