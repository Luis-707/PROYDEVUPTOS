<?php
header('Content-Type: application/json; charset=utf-8');
include_once "../clases/DesempenoExcepcional.php";

try {
    // Compatibilidad: aceptamos id_eval_admin aunque no se use
    $data = $_GET;
    if (empty($data)) {
        $json = file_get_contents("php://input");
        $data = json_decode($json, true) ?? [];
    }

    // Consulta de indicadores fijos activos
    $sql = DesempenoExcepcional::sql_listar_indicadores();
    $res = $this->ejecutarConsultaBdds($sql);

    // Aplanar estructura
    $indicadores = $res[0] ?? $res;

    echo json_encode([
        'success' => true,
        'data' => $indicadores
    ]);

} catch (Throwable $e) {
    echo json_encode([
        'success' => false,
        'message' => 'Error en el servidor: ' . $e->getMessage()
    ]);
}
exit;
?>