<?php
error_reporting(E_ALL);
ini_set('display_errors', 0);
ini_set('display_startup_errors', 0);

header('Content-Type: application/json; charset=utf-8');

include_once "../clases/Evaluado.php";
include_once "../servicios/Sesion.php"; // para $id_usuario y $rolUsuario

try {
    // Usar directamente $_POST para recibir datos del formulario
    $evaluado = new Evaluado($_POST, $this->conexion);

    // 1) Insertar en usuarios
    $sqlUsuario = $evaluado->sql_guardar_usuario();
    $respUsuario = $this->ejecutarConsultaBdds($sqlUsuario);

    if (empty($respUsuario) || empty($respUsuario[0][0]['id_usuario'])) {
        echo json_encode(['success' => false, 'message' => 'No se pudo crear el usuario evaluado']);
        exit;
    }
    $nuevoIdUsuario = (int)$respUsuario[0][0]['id_usuario'];
    $evaluado->setIdUsuario($nuevoIdUsuario);

    // 2) Insertar en posee_permisos
    $sqlPermiso = Evaluado::sql_buscar_permiso_comentarios();
    $respPermiso = $this->ejecutarConsultaBdds($sqlPermiso);
    if (empty($respPermiso) || empty($respPermiso[0][0]['permisos_id'])) {
        echo json_encode(['success' => false, 'message' => 'No se encontró el permiso Comentarios']);
        exit;
    }
    $permisoId = (int)$respPermiso[0][0]['permisos_id'];
    $sqlPosee = $evaluado->sql_guardar_permiso($permisoId);
    $this->ejecutarConsultaBdds($sqlPosee);

    // 3) Insertar en evaluados
    $evaluado->setIdEvaluador($id_usuario); // sesión
    $sqlEval = $evaluado->sql_guardar_evaluado();
    $this->ejecutarConsultaBdds($sqlEval);

    // Respuesta exitosa
    echo json_encode([
        'success' => true,
        'message' => '✅ Evaluado creado con éxito',
        'id_usuario' => $nuevoIdUsuario
    ]);


} catch (Throwable $e) {
    // Enviar JSON de error sin imprimir nada más antes
    echo json_encode([
        'success' => false,
        'message' => 'Error en el servidor: ' . $e->getMessage()
    ]);
}
exit;
?>
