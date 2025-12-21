<?php
error_reporting(E_ALL);
ini_set('display_errors', '0');
ini_set('display_startup_errors', '0');

header('Content-Type: application/json; charset=utf-8');

include_once "../clases/PlanillaAdministrativos.php";

try {
    // Normalizar entrada
    if (!isset($dataCliente)) {
        $dataCliente = ['_post' => $_POST];
        if (empty($dataCliente['_post'])) {
            $json = file_get_contents("php://input");
            $dataCliente['_post'] = json_decode($json, true) ?? [];
        }
    }

    // Validaciones mínimas
    $required = ['id_usuario','id_evaluado','periodo_evaluado','puntaje_final'];
    foreach ($required as $key) {
        if (empty($dataCliente['_post'][$key])) {
            echo json_encode([
                'success' => false,
                'message' => "Falta el campo requerido: $key"
            ]);
            exit;
        }
    }

    // Instanciar clase con conexión
    $planilla = new PlanillaAdministrativos($dataCliente['_post'], $this->conexion);

    // 1) Buscar evaluación existente con sql_buscar(id_eval_admin) o, si no llega, derivar por id_usuario + id_evaluado + periodo
    $idEvalAdminPost = isset($dataCliente['_post']['id_eval_admin']) ? (int)$dataCliente['_post']['id_eval_admin'] : 0;

    if ($idEvalAdminPost > 0) {
        // Usar sql_buscar con el id recibido
        $sqlEval = $planilla->sql_buscar($idEvalAdminPost);
        $respEval = $this->ejecutarConsultaBdds($sqlEval);
        $evalRow  = isset($respEval[0][0]) ? $respEval[0][0] : ($respEval[0] ?? []);
        if (empty($evalRow)) {
            echo json_encode([
                'success' => false,
                'message' => '❌ No existe evaluación previa con ese id_eval_admin para este evaluado/evaluador'
            ]);
            exit;
        }
        $planilla->setIdEvalAdmin((int)$evalRow['id_eval_admin']);
    } else {
        // Fallback: localizar la evaluación por id_usuario + id_evaluado + periodo
        // reutilizando sql_listar_periodo_por_id requeriría id_eval_admin, así que aquí asumimos
        // que ya existe un registro para esa combinación y actualizamos con sql_guardar_evaluacion (que filtra por periodo)
        // Si necesitas confirmación estricta, podrías crear un método sql_buscar_por_usuario_evaluado_periodo.
        // Continuamos y confiamos en el UPDATE por periodo.
    }

    // 2) Verificar duplicados en objetivos
    if ($planilla->getPeriodoevaluado() !== '') {
        // Si tenemos id_eval_admin set, verificamos duplicados
        $idEvalAdmin = $planilla->getPeriodoevaluado() !== '' && $planilla->getIdevaluado() > 0
            ? ($planilla->getIdEvalAdmin() ?? 0)
            : ($planilla->getIdEvalAdmin() ?? 0);
    } else {
        $idEvalAdmin = $planilla->getIdEvalAdmin() ?? 0;
    }

    if ($idEvalAdmin > 0) {
        $sqlCheckObj = $planilla->sql_existen_objetivos($idEvalAdmin);
        $resCheckObj = $this->ejecutarConsultaBdds($sqlCheckObj);
        $totalObj = isset($resCheckObj[0][0]['total']) ? (int)$resCheckObj[0][0]['total'] : (int)($resCheckObj[0]['total'] ?? 0);

        if ($totalObj > 0) {
            echo json_encode([
                'success' => false,
                'message' => '⚠️ Ya existen objetivos y competencias registrados para esta evaluación'
            ]);
            exit;
        }

        $sqlCheckComp = $planilla->sql_existen_competencias($idEvalAdmin);
        $resCheckComp = $this->ejecutarConsultaBdds($sqlCheckComp);
        $totalComp = isset($resCheckComp[0][0]['total']) ? (int)$resCheckComp[0][0]['total'] : (int)($resCheckComp[0]['total'] ?? 0);

        if ($totalComp > 0) {
            echo json_encode([
                'success' => false,
                'message' => '⚠️ Ya existen competencias registradas para esta evaluación'
            ]);
            exit;
        }
    }

    // 3) Calcular id_rango si no viene (opcional)
    if (empty($dataCliente['_post']['id_rango'])) {
        $idRango = $planilla->obtenerIdRangoPorPuntaje((int)$dataCliente['_post']['puntaje_final']);
        if ($idRango !== null) {
            // inyectamos el id_rango en la instancia para el UPDATE
            // (si tu clase tiene setter, úsalo; si no, recrea la instancia)
            $dataCliente['_post']['id_rango'] = $idRango;
            $planilla = new PlanillaAdministrativos($dataCliente['_post'], $this->conexion);
        }
    }

    // 4) Actualizar evaluación general por periodo (id_evaluado + id_usuario + periodo_evaluado)
    $sqlUpdateEval = $planilla->sql_guardar_evaluacion();
    $resEval = $this->ejecutarConsultaBdds($sqlUpdateEval);
    $rowEval = isset($resEval[0][0]) ? $resEval[0][0] : ($resEval[0] ?? []);

    if (empty($rowEval['id_eval_admin'])) {
        echo json_encode([
            'success' => false,
            'message' => '❌ No se pudo actualizar la evaluación'
        ]);
        exit;
    }

    $idEvalAdminFinal = (int)$rowEval['id_eval_admin'];
    $planilla->setIdEvalAdmin($idEvalAdminFinal);

    // 5) Guardar objetivos
    if (!empty($dataCliente['_post']['objetivos'])) {
        $objetivos = is_string($dataCliente['_post']['objetivos'])
            ? json_decode($dataCliente['_post']['objetivos'], true)
            : $dataCliente['_post']['objetivos'];

        if (is_array($objetivos)) {
            foreach ($objetivos as $obj) {
                if (!empty($obj['id_odi'])) {
                    $sqlObj = $planilla->sql_guardar_objetivo(
                        $idEvalAdminFinal,
                        (int)$obj['id_odi'],
                        (int)$obj['rango'],
                        (int)$obj['pesoXRango']
                    );
                    $this->ejecutarConsultaBdds($sqlObj);
                }
            }
        }
    }

    // 6) Guardar competencias
    if (!empty($dataCliente['_post']['competencias'])) {
        $competencias = is_string($dataCliente['_post']['competencias'])
            ? json_decode($dataCliente['_post']['competencias'], true)
            : $dataCliente['_post']['competencias'];

        if (is_array($competencias)) {
            foreach ($competencias as $comp) {
                if (!empty($comp['id_competencia'])) {
                    $sqlComp = $planilla->sql_guardar_competencia(
                        $idEvalAdminFinal,
                        (int)$comp['id_competencia'],
                        (int)$comp['rango'],
                        (int)$comp['pesoXRango']
                    );
                    $this->ejecutarConsultaBdds($sqlComp);
                }
            }
        }
    }

    // 7) Respuesta final
    echo json_encode([
        'success' => true,
        'message' => '✅ Evaluación actualizada con éxito',
        'id_eval_admin' => $idEvalAdminFinal
    ]);

} catch (Throwable $e) {
    echo json_encode([
        'success' => false,
        'message' => 'Error en el servidor: ' . $e->getMessage()
    ]);
}
exit;