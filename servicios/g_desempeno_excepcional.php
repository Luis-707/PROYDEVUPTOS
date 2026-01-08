<?php
header('Content-Type: application/json; charset=utf-8');
include_once "../clases/DesempenoExcepcional.php";

try {
    $data = $_POST;
    if (empty($data)) {
        $json = file_get_contents("php://input");
        $data = json_decode($json, true) ?? [];
    }

    if (empty($data['id_eval_admin']) || empty($data['periodo']) || empty($data['fecha'])) {
        echo json_encode(['success' => false, 'message' => 'Faltan datos obligatorios']);
        exit;
    }

    $planilla = new DesempenoExcepcional($data, $this->conexion);

    // Verificar si ya existe
    $sqlExiste = DesempenoExcepcional::sql_existe_excepcional((int)$data['id_eval_admin']);
    $resExiste = $this->ejecutarConsultaBdds($sqlExiste);
    if (!empty($resExiste[0])) {
        echo json_encode(['success' => false, 'message' => 'Ya existe una planilla excepcional para esta evaluación']);
        exit;
    }

    // Guardar registro principal con RETURNING
    $sqlPrincipal = $planilla->sql_guardar_excepcional();
    $resPrincipal = $this->ejecutarConsultaBdds($sqlPrincipal);
    $rowPrincipal = $resPrincipal[0][0] ?? [];
    $idDesempExcepcional = (int)($rowPrincipal['id_desemp_excepcional'] ?? 0);

    if ($idDesempExcepcional <= 0) {
        echo json_encode(['success' => false, 'message' => 'No se pudo crear la planilla excepcional']);
        exit;
    }

    // Guardar motivos e indicadores
    $indicadores = is_string($data['indicadores']) ? json_decode($data['indicadores'], true) : $data['indicadores'];
    if (is_array($indicadores)) {
        foreach ($indicadores as $ind) {
            $indicadorId = (int)$ind['indicador_id'];
            $motivo = $ind['motivo'] ?? '';

            // Insertar motivo con RETURNING
            $sqlMotivo = $planilla->sql_guardar_motivo($idDesempExcepcional, $indicadorId, $motivo);
            $resMotivo = $this->ejecutarConsultaBdds($sqlMotivo);
            $rowMotivo = $resMotivo[0][0] ?? [];
            $idMotivo = $rowMotivo['motivo_id'] ?? null;

            // Insertar relación con RETURNING
            $sqlRelacion = $planilla->sql_guardar_relacion($idDesempExcepcional, $indicadorId);
            $resRelacion = $this->ejecutarConsultaBdds($sqlRelacion);
            $rowRelacion = $resRelacion[0][0] ?? [];
            $idRelacion = $rowRelacion['id_relacion'] ?? null;
        }
    }

    echo json_encode([
        'success' => true,
        'message' => '✅ Planilla de desempeño excepcional guardada con éxito',
        'id_desemp_excepcional' => $idDesempExcepcional
    ]);

} catch (Throwable $e) {
    echo json_encode(['success' => false, 'message' => 'Error en el servidor: ' . $e->getMessage()]);
}
exit;
?>