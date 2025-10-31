<?php
session_start();
include_once '../clases/ListaComentarios.php';

$cedulaSesion = $_SESSION['usuario']['cedula'] ?? null;
$rolUsuario   = $_SESSION['usuario']['rol'] ?? null;

if (!$cedulaSesion || !$rolUsuario) {
    echo json_encode(["success" => false, "message" => "Usuario no autenticado"]);
    exit;
}

$Lista = new ListaComentarios($this);

// Seleccionar SQL según rol
switch ($rolUsuario) {
    case 'evaluado':
        $sql = ListaComentarios::sql_listar_por_evaluado($cedulaSesion);
        break;
    case 'supervisor del evaluador':
        $sql = ListaComentarios::sql_listar_por_supervisor($cedulaSesion);
        break;
    case 'evaluador':
        $sql = ListaComentarios::sql_listar_por_evaluador($cedulaSesion);
        break;
    case 'administrador': // si tienes un rol admin que ve todo
        $sql = ListaComentarios::sql_listar_evaluados();
        break;
    default:
        echo json_encode(["success" => false, "message" => "Rol no autorizado"]);
        exit;
}

$respuesta = $Lista->listarEvaluadosComentarios($sql);
return $respuesta;