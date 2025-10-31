<?php
error_reporting(E_ALL);
ini_set('display_errors', '0');
ini_set('display_startup_errors', '0');

header('Content-Type: application/json; charset=utf-8');

include_once "../clases/Evaluado.php";
include_once "../servicios/Sesion.php"; // para obtener $id_usuario y $rolUsuario

try {
    $evaluado = new Evaluado($dataCliente['_post'], $this->conexion);

    // 1) Insertar en usuarios (rol_id fijo para evaluados, ej: 3)
    $rolEvaluado = 3; // ⚠️ Ajustar según tu tabla roles_sistema
    $sqlUsuario = $evaluado->sql_guardar_usuario($rolEvaluado);
    $respUsuario = $this->ejecutarConsultaBdds($sqlUsuario);

    if (empty($respUsuario) || empty($respUsuario[0][0]['id_usuario'])) {
        echo json_encode(['success' => false, 'message' => 'No se pudo crear el usuario evaluado']);
        exit;
    }
    $nuevoIdUsuario = (int)$respUsuario[0][0]['id_usuario'];
    $evaluado->setIdUsuario($nuevoIdUsuario);

    // 2) Insertar en posee_permisos (permiso "Comentarios")
    $sqlPermiso = Evaluado::sql_buscar_permiso_comentarios();
    $respPermiso = $this->ejecutarConsultaBdds($sqlPermiso);
    if (empty($respPermiso) || empty($respPermiso[0][0]['permisos_id'])) {
        echo json_encode(['success' => false, 'message' => 'No se encontró el permiso Comentarios']);
        exit;
    }
    $permisoId = (int)$respPermiso[0][0]['permisos_id'];
    $sqlPosee = $evaluado->sql_guardar_permiso($permisoId);
    $this->ejecutarConsultaBdds($sqlPosee);

    // 3) Insertar en evaluados (id_evaluador desde sesión)
    $evaluado->setIdEvaluador($id_usuario); // el usuario autenticado es el evaluador
    $sqlEval = $evaluado->sql_guardar_evaluado();
    $this->ejecutarConsultaBdds($sqlEval);

    echo json_encode([
        'success' => true,
        'message' => '✅ Evaluado creado con éxito',
        'id_usuario' => $nuevoIdUsuario
    ]);

} catch (Throwable $e) {
    echo json_encode([
        'success' => false,
        'message' => 'Error en el servidor: ' . $e->getMessage()
    ]);
}
exit;