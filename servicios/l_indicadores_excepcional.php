<?php
header('Content-Type: application/json; charset=utf-8');
include_once "../clases/DesempenoExcepcional.php";

try {
    // Iniciar sesión y verificar autenticación
    session_start();
    $idUsuarioSesion = $_SESSION['usuario']['id_usuario'] ?? null;
    $cedulaSesion = $_SESSION['usuario']['cedula'] ?? null;

    if (!$idUsuarioSesion || !$cedulaSesion) {
        echo json_encode([
            'success' => false,
            'message' => 'Usuario no autenticado'
        ]);
        exit;
    }

    // Compatibilidad: aceptamos id_eval_admin aunque no se use
    $data = $_GET;
    if (empty($data)) {
        $json = file_get_contents("php://input");
        $data = json_decode($json, true) ?? [];
    }

    // Consulta de indicadores personalizados por usuario
    $indicador = new DesempenoExcepcional([], $this);
    $sql = $indicador->sql_listar_indicadores($idUsuarioSesion);
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