<?php
session_start();
include_once '../clases/Listados.php';

header('Content-Type: application/json; charset=utf-8');

try {

    // 1) Validar sesión
    $idUsuario = $_SESSION['usuario']['id_usuario'] ?? null;
    $roles     = $_SESSION['usuario']['roles'] ?? [];

    if (!$idUsuario || empty($roles)) {
        echo json_encode(["success" => false, "message" => "Usuario no autenticado"]);
        exit;
    }

    // 2) Validar rol
    if (!in_array('evaluador', $roles)) {
        echo json_encode(["success" => false, "message" => "Rol no autorizado"]);
        exit;
    }

    // 3) Ejecutar SQL
    $sql = Listados::sql_listar_evaluaciones_admin((int)$idUsuario);

    $Lista = new Listados($this);
    $resp = $Lista->listaEvaluados($sql);

    echo json_encode([
        "success" => true,
        "data"    => $resp[0] ?? []
    ]);

} catch (Throwable $e) {
    echo json_encode([
        "success" => false,
        "message" => "Error en el servidor: " . $e->getMessage()
    ]);
}
exit;