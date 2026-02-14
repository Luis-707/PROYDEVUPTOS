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

    $post = $dataCliente['_post'];

    // 1) Validaciones mínimas
    $required = ['evaluado_id','evaluador_id','id_eval_admin','puntaje_final'];
    foreach ($required as $key) {
        if (empty($post[$key])) {
            echo json_encode([
                'success' => false,
                'message' => "Falta el campo requerido: $key"
            ]);
            exit;
        }
    }

    $planilla = new PlanillaAdministrativos($post, $this->conexion);

    $evaluado_id   = (int)$post['evaluado_id'];
    $evaluador_id  = (int)$post['evaluador_id'];
    $id_eval_admin = (int)$post['id_eval_admin'];
    $puntaje_final = (int)$post['puntaje_final'];
    $id_rango      = isset($post['id_rango']) ? (int)$post['id_rango'] : 0;

    // 2) Si no viene id_rango, lo calculamos por puntaje
    if ($id_rango <= 0) {
        $sqlRango = PlanillaAdministrativos::sql_id_rango_por_puntaje($puntaje_final);
        $resRango = $this->ejecutarConsultaBdds($sqlRango);
        if (!empty($resRango[0][0]['id_rango'])) {
            $id_rango = (int)$resRango[0][0]['id_rango'];
            $post['id_rango'] = $id_rango;
            $planilla = new PlanillaAdministrativos($post, $this->conexion);
        }
    }

    // 3) Verificar duplicados de objetivos/competencias para este id_eval_admin
    $sqlCheckObj = PlanillaAdministrativos::sql_existen_objetivos($id_eval_admin);
    $resCheckObj = $this->ejecutarConsultaBdds($sqlCheckObj);
    $totalObj = isset($resCheckObj[0][0]['total']) ? (int)$resCheckObj[0][0]['total'] : 0;

    $sqlCheckComp = PlanillaAdministrativos::sql_existen_competencias($id_eval_admin);
    $resCheckComp = $this->ejecutarConsultaBdds($sqlCheckComp);
    $totalComp = isset($resCheckComp[0][0]['total']) ? (int)$resCheckComp[0][0]['total'] : 0;

    if ($totalObj > 0 || $totalComp > 0) {
        echo json_encode([
            'success' => false,
            'message' => 'Ya existen objetivos o competencias registrados para esta evaluación'
        ]);
        exit;
    }

    // 4) Actualizar evaluación general
    $sqlUpdate = $planilla->sql_guardar_evaluacion();
    $resUpdate = $this->ejecutarConsultaBdds($sqlUpdate);
    $rowEval   = isset($resUpdate[0][0]) ? $resUpdate[0][0] : ($resUpdate[0] ?? []);

    if (empty($rowEval['id_eval_admin'])) {
        echo json_encode([
            'success' => false,
            'message' => 'No se pudo actualizar la evaluación'
        ]);
        exit;
    }

    $idEvalFinal = (int)$rowEval['id_eval_admin'];

    // 5) Guardar objetivos
    if (!empty($post['objetivos'])) {
        $objetivos = is_string($post['objetivos'])
            ? json_decode($post['objetivos'], true)
            : $post['objetivos'];

        if (is_array($objetivos)) {
            foreach ($objetivos as $obj) {
                if (!empty($obj['id_odi'])) {
                    $sqlObj = $planilla->sql_guardar_objetivo(
                        $idEvalFinal,
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
    if (!empty($post['competencias'])) {
        $competencias = is_string($post['competencias'])
            ? json_decode($post['competencias'], true)
            : $post['competencias'];

        if (is_array($competencias)) {
            foreach ($competencias as $comp) {
                if (!empty($comp['id_competencia'])) {
                    $sqlComp = $planilla->sql_guardar_competencia(
                        $idEvalFinal,
                        (int)$comp['id_competencia'],
                        (int)$comp['rango'],
                        (int)$comp['pesoXRango']
                    );
                    $this->ejecutarConsultaBdds($sqlComp);
                }
            }
        }
    }

    echo json_encode([
        'success' => true,
        'message' => 'Evaluación guardada/actualizada con éxito',
        'id_eval_admin' => $idEvalFinal
    ]);

} catch (Throwable $e) {
    echo json_encode([
        'success' => false,
        'message' => 'Error en el servidor: ' . $e->getMessage()
    ]);
}
exit;