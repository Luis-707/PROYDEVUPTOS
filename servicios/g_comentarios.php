<?php
session_start();
include_once "../clases/Planilla_comentarios.php";

header('Content-Type: application/json; charset=utf-8');

try {
    $id_usuario = $_SESSION['usuario']['id_usuario'] ?? null;
    $rol        = $_SESSION['usuario']['rol'] ?? 'otro';

    if (!$id_usuario) {
        echo json_encode(["success" => false, "message" => "Usuario no autenticado"]);
        exit;
    }

    $data = [
        'id_eval_admin'         => $_POST['id_eval_admin'] ?? null,
        'comentario_supervisor' => $_POST['comentario_supervisor'] ?? null,
        'comentario_evaluado'   => $_POST['comentario_evaluado'] ?? null
    ];

    if (!$data['id_eval_admin']) {
        echo json_encode(["success" => false, "message" => "Falta el identificador de la evaluación"]);
        exit;
    }

    $planilla = new Planilla_comentarios($data, $this->conexion);

    if ($rol === "supervisor del evaluador" && $data['comentario_supervisor'] !== null) {
        $sql = $planilla->sql_update_comentario_supervisor();
    } elseif ($rol === "evaluado" && $data['comentario_evaluado'] !== null) {
        $sql = $planilla->sql_update_comentario_evaluado();
    } else {
        echo json_encode(["success" => false, "message" => "Rol no autorizado o datos incompletos"]);
        exit;
    }

    $resultado = $this->ejecutarConsultaBdds($sql);

    if ($resultado) {
        echo json_encode(["success" => true, "message" => "Comentarios guardados correctamente"]);
    } else {
        echo json_encode(["success" => false, "message" => "Error al guardar los comentarios"]);
    }

} catch (Throwable $e) {
    echo json_encode(["success" => false, "message" => "Error en el servidor: " . $e->getMessage()]);
}
exit;