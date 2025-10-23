<?php

/*ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);*/

include_once "../clases/Planilla_comentarios.php";
$cedulaSesion = $_SESSION['usuario']['cedula'] ?? null;
if (!$cedulaSesion) {
    echo json_encode(["success" => false, "message" => "Usuario no autenticado"]);
    exit;
}

$data=$dataCliente['_post'];


// 1. Buscar si existe la evaluación
$planilla = new Planilla_comentarios($dataCliente['_post']);
$sql = $planilla->sql_buscar_por_id_y_supervisor($cedulaSesion);
$respuesta = $this->ejecutarConsultaBdds($sql);

if (count($respuesta) == 0) {
    $respuesta = $dataCliente['id_eval_admin'] . ' No Existe';
} else {
    // 2. Ejecutar update de comentario supervisor
    $sql = $planilla->sql_update_comentario_supervisor();
    $respuesta = $this->ejecutarConsultaBdds($sql);
}

// 3. Retornar respuesta
return $respuesta;
?>