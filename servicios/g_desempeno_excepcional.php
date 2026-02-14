<?php
header('Content-Type: application/json; charset=utf-8');
include_once "../clases/DesempenoExcepcional.php";

try {
    $data = $_POST;

    if (empty($data)) {
        $json = file_get_contents("php://input");
        $data = json_decode($json, true) ?? [];
    }

    // Validación mínima
    if (empty($data['id_eval_admin']) || empty($data['periodo']) || empty($data['fecha'])) {
        echo json_encode([
            'success' => false,
            'message' => 'Faltan datos obligatorios'
        ]);
        exit;
    }

    $idEvalAdmin = (int)$data['id_eval_admin'];

    // Verificar si ya existe planilla excepcional
    $sqlExiste = DesempenoExcepcional::sql_existe_excepcional($idEvalAdmin);
    $resExiste = $this->ejecutarConsultaBdds($sqlExiste);

    if (!empty($resExiste[0])) {
        echo json_encode([
            'success' => false,
            'message' => 'Ya existe una planilla excepcional para esta evaluación'
        ]);
        exit;
    }

    // Crear objeto
    $planilla = new DesempenoExcepcional($data, $this->conexion);

    // Guardar registro principal
    $sqlPrincipal = $planilla->sql_guardar_excepcional();
    $resPrincipal = $this->ejecutarConsultaBdds($sqlPrincipal);

    $rowPrincipal = $resPrincipal[0][0] ?? $resPrincipal[0] ?? [];
    $idDesempExcepcional = (int)($rowPrincipal['id_desemp_excepcional'] ?? 0);

    if ($idDesempExcepcional <= 0) {
        echo json_encode([
            'success' => false,
            'message' => 'No se pudo crear la planilla excepcional'
        ]);
        exit;
    }

    // Procesar indicadores y motivos
    $indicadores = is_string($data['indicadores'])
        ? json_decode($data['indicadores'], true)
        : $data['indicadores'];

    if (is_array($indicadores)) {
        foreach ($indicadores as $ind) {
            $indicadorId = (int)$ind['indicador_id'];
            $motivo = trim($ind['motivo'] ?? '');

            if ($motivo === '') continue;

            // Guardar motivo
            $sqlMotivo = $planilla->sql_guardar_motivo($idDesempExcepcional, $indicadorId, $motivo);
            $this->ejecutarConsultaBdds($sqlMotivo);

            // Guardar relación
            $sqlRelacion = $planilla->sql_guardar_relacion($idDesempExcepcional, $indicadorId);
            $this->ejecutarConsultaBdds($sqlRelacion);
        }
    }

    echo json_encode([
        'success' => true,
        'message' => 'Planilla de desempeño excepcional guardada con éxito',
        'id_desemp_excepcional' => $idDesempExcepcional
    ]);

} catch (Throwable $e) {
    echo json_encode([
        'success' => false,
        'message' => 'Error en el servidor: ' . $e->getMessage()
    ]);
}
exit;
?>
