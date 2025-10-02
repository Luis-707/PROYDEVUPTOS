<?php
include_once "../clases/PlanillaAdministrativos.php";

try {
    $evaluacion = new PlanillaAdministrativos($_POST, $this->conexion);

    // Ejecutar UPDATE de periodo
    $sql = $evaluacion->sql_actualizar_periodo();
    error_log("SQL update periodo: " . $sql);
    $resp = $this->ejecutarConsultaBdds($sql);

    if (!empty($resp) && isset($resp[0][0]['id_eval_admin'])) {
        echo json_encode([
            'success' => true,
            'message' => '✅ Periodo actualizado con éxito',
            'id_eval_admin' => $resp[0][0]['id_eval_admin']
        ]);
    } else {
        echo json_encode([
            'success' => false,
            'message' => '⚠️ No se encontró la fila para este evaluado'
        ]);
    }
} catch (Throwable $e) {
    echo json_encode([
        'success' => false,
        'message' => 'Error en el servidor: ' . $e->getMessage()
    ]);
}
exit;