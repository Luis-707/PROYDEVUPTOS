<?php
include_once "../clases/PlanillaAdministrativos.php";

try {
    // Instanciar la clase solo con los datos del formulario
    $evaluacion = new PlanillaAdministrativos($_POST);

    // 1. Verificar si ya existe evaluación para ese evaluado y periodo
    $sql = $evaluacion->sql_buscar();
    error_log("SQL buscar: " . $sql);
    $respuesta = $this->ejecutarConsultaBdds($sql);

    if (empty($respuesta)) {
        // 2. Buscar el id_rango en la tabla rango_actuacion según el puntaje
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

        // 3. Asignar el id_rango obtenido
        $evaluacion->id_rango = (int)$respRango[0][0]['id_rango'];

        // 4. Guardar la evaluación
        $sqlInsert = $evaluacion->sql_guardar_evaluacion();
        error_log("SQL insert: " . $sqlInsert);
        $this->ejecutarConsultaBdds($sqlInsert);

        echo json_encode([
            'success' => true,
            'message' => '✅ Evaluación guardada con éxito'
        ]);
    } else {
        echo json_encode([
            'success' => false,
            'message' => '⚠️ Ya existe una evaluación para este evaluado en el mismo periodo'
        ]);
    }
} catch (Throwable $e) {
    echo json_encode([
        'success' => false,
        'message' => 'Error en el servidor: ' . $e->getMessage()
    ]);
}
exit;