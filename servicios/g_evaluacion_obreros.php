<?php
header('Content-Type: application/json; charset=utf-8');
include_once "../clases/EvaluacionObreros.php";

try {

    // ============================
    // Validación de campos obligatorios
    // ============================
    $obligatorios = ['id_eval_obreros','id_evaluado', 'id_usuario', 'rango_id', 'puntaje_total'];

    foreach ($obligatorios as $campo) {
        if (empty($_POST[$campo])) {
            echo json_encode(['success' => false, 'message' => "Falta el campo obligatorio: $campo"]);
            exit;
        }
    }

    // ============================
    // Crear objeto EvaluacionObreros
    // ============================
    $eval = new EvaluacionObreros($_POST, $this->conexion);

    // ============================
    // Guardar evaluación (UPDATE)
    // ============================
    $sqlSave = $eval->sql_guardar_evaluacion();
    $respSave = $this->ejecutarConsultaBdds($sqlSave);
    $idEval = $respSave[0][0]['id_eval_obreros'] ?? null;

    if (!$idEval) {
        echo json_encode(['success' => false, 'message' => 'No se pudo actualizar la evaluación']);
        exit;
    }

    // ============================
    // Guardar criterios seleccionados
    // ============================
    if (!empty($_POST['seleccion'])) {
        $criterios = is_string($_POST['seleccion']) 
            ? json_decode($_POST['seleccion'], true) 
            : $_POST['seleccion'];

            foreach ($criterios as $c) {
                $sql = EvaluacionObreros::sql_guardar_detalle($idEval, $c);
                $this->ejecutarConsultaBdds($sql);
            }
    }

    echo json_encode([
        'success' => true,
        'message' => 'Evaluación guardada con éxito',
        'id_eval_obreros' => $idEval
    ]);

} catch (Throwable $e) {
    echo json_encode(['success' => false, 'message' => 'Error: '.$e->getMessage()]);
}
exit;