<?php
include_once "../clases/PlanillaAdministrativos.php";

try {
    $evaluacion = new PlanillaAdministrativos($_POST);

    // Buscar id_rango en base al puntaje
    $sqlRango = sprintf(
        "SELECT id_rango 
         FROM rango_actuacion 
         WHERE %d BETWEEN puntaje_minimo AND puntaje_maximo 
         LIMIT 1;",
        (int)$evaluacion->puntaje_final
    );
    $respRango = $this->ejecutarConsultaBdds($sqlRango);

    if (empty($respRango) || empty($respRango[0][0]['id_rango'])) {
        echo json_encode([
            'success' => false,
            'message' => 'No se encontró un rango válido para el puntaje ' . $evaluacion->puntaje_final
        ]);
        exit;
    }

    $evaluacion->id_rango = (int)$respRango[0][0]['id_rango'];

    // Ejecutar UPDATE puro
    $sql = $evaluacion->sql_guardar_evaluacion();
    error_log("SQL update: " . $sql);
    $resp = $this->ejecutarConsultaBdds($sql);

    if (!empty($resp) && isset($resp[0][0]['id_eval_admin'])) {
        echo json_encode([
            'success' => true,
            'message' => '✅ Evaluación actualizada con éxito',
            'id_eval_admin' => $resp[0][0]['id_eval_admin']
        ]);
    } else {
        echo json_encode([
            'success' => false,
            'message' => '⚠️ No existe una evaluación previa para este usuario/periodo'
        ]);
    }
} catch (Throwable $e) {
    echo json_encode([
        'success' => false,
        'message' => 'Error en el servidor: ' . $e->getMessage()
    ]);
}
exit;