<?php
error_reporting(E_ALL);
ini_set('display_errors', '0');
ini_set('display_startup_errors', '0');

header('Content-Type: application/json; charset=utf-8');

session_start();
include_once "../clases/EvaluacionAdministrativos.php";

try {

    // 1) Validar sesión
    $idUsuarioSesion = $_SESSION['usuario']['id_usuario'] ?? null;
    if (!$idUsuarioSesion) {
        echo json_encode([
            'success' => false,
            'message' => '❌ Usuario no autenticado'
        ]);
        exit;
    }

    // 2) Recibir datos
    $data = $dataCliente['_post'] ?? $_POST;

    // 3) Instanciar clase
    $evalAdmin = new EvaluacionesAdministrativos($data, $this->conexion);

    // 4) Verificar que el registro exista
    $sqlExiste = $evalAdmin->sql_buscarPorId();
    $respExiste = $this->ejecutarConsultaBdds($sqlExiste);

    if (empty($respExiste)) {
        echo json_encode([
            'success' => false,
            'message' => $data['id_eval_admin'] . ' No Existe'
        ]);
        exit;
    }

    // 5) Validar duplicado de período (NUEVO)
    $sqlDup = $evalAdmin->sql_existe_duplicado_periodo_edicion();
    $respDup = $this->ejecutarConsultaBdds($sqlDup);

    if (!empty($respDup) && !empty($respDup[0][0]['id_eval_admin'])) {
        echo json_encode([
            'success' => false,
            'message' => '❌ No puede cambiar el período: ya existe otra evaluación de este evaluado en ese período'
        ]);
        exit;
    }

    // 6) Actualizar período
    $sqlUpdate = $evalAdmin->sql_actualizar_periodo();
    $respUpdate = $this->ejecutarConsultaBdds($sqlUpdate);

    // 7) Respuesta final
    echo json_encode([
        'success' => true,
        'message' => '✅ Período actualizado correctamente',
        'data'    => $respUpdate
    ]);
    exit;

} catch (Throwable $e) {
    echo json_encode([
        'success' => false,
        'message' => 'Error en el servidor: ' . $e->getMessage()
    ]);
}
exit;