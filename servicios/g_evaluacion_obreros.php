<?php
header('Content-Type: application/json; charset=utf-8');

include_once "../clases/EvaluacionObreros.php";

session_start();

try {

    $rolesSesion = $_SESSION['usuario']['roles'] ?? [];

    if (!in_array("evaluador", $rolesSesion)) {
        echo json_encode(['success' => false, 'message' => 'Rol no autorizado']);
        exit;
    }

    $obligatorios = ['id_eval_obreros','evaluado_id','rango_id','puntaje_total','tiempo_puesto'];

    foreach ($obligatorios as $campo) {
        if (!isset($_POST[$campo])) {
            echo json_encode(['success' => false, 'message' => "Falta el campo: $campo"]);
            exit;
        }
    }

    $eval = new EvaluacionObreros($_POST, $this);

    $sqlUpdate = $eval->sql_guardar_evaluacion();
    $respUpdate = $this->ejecutarConsultaBdds($sqlUpdate);

    $idEval = $respUpdate[0][0]['id_eval_obreros'] ?? null;

    if (!$idEval) {
        echo json_encode(['success' => false, 'message' => 'No se pudo actualizar la evaluación']);
        exit;
    }

    if (!empty($_POST['seleccion'])) {

        $criterios = is_string($_POST['seleccion'])
            ? json_decode($_POST['seleccion'], true)
            : $_POST['seleccion'];

        if (is_array($criterios)) {
            foreach ($criterios as $c) {
                if (!isset($c['criterio_id'], $c['puntaje_obtenido'])) continue;

                $sqlDet = EvaluacionObreros::sql_guardar_detalle($idEval, $c);
                $this->ejecutarConsultaBdds($sqlDet);
            }
        }
    }

    echo json_encode([
        'success' => true,
        'message' => 'Evaluación guardada con éxito',
        'id_eval_obreros' => $idEval
    ]);

} catch (Throwable $e) {

    echo json_encode([
        'success' => false,
        'message' => 'Error: ' . $e->getMessage()
    ]);
}

exit;
