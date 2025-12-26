<?php
session_start();
include_once '../clases/Listados.php';

$id_usuario = $_SESSION['usuario']['id_usuario'] ?? null;
$cedulaSesion = $_SESSION['usuario']['cedula'] ?? null;
$rolUsuario   = $_SESSION['usuario']['rol'] ?? null;

if (!$id_usuario || !$cedulaSesion || !$rolUsuario) {
    echo json_encode(["success" => false, "message" => "Usuario no autenticado"]);
    exit;
}

$Lista = new Listados($this);

// Seleccionar consulta según rol
switch ($rolUsuario) {
    case 'evaluador':
        // Usamos el método sql_listar_eval_administrativos para traer datos por id_usuario del evaluador
        $sql = Listados::sql_listar_eval_administrativos($id_usuario);
        break;
   
    default:
        echo json_encode(["success" => false, "message" => "Rol no autorizado"]);
        exit;
}

// Ejecutar la consulta usando listarEvalAdmin
$respuesta = $Lista->listarEvalAdmin($sql);
return $respuesta;
