<?php
session_start();
include_once '../clases/Listados.php';

$cedulaSesion = $_SESSION['usuario']['cedula'] ?? null;
$rolUsuario   = $_SESSION['usuario']['rol'] ?? null;

if (!$cedulaSesion || !$rolUsuario) {
    echo json_encode(["success" => false, "message" => "Usuario no autenticado"]);
    exit;
}

$Lista = new Listados($this);

// Seleccionar SQL según rol
switch ($rolUsuario) {

    case 'evaluado':
        $sql = Listados::sql_listar_comentarios_obrero_evaluado($cedulaSesion);
        break;
    
    case 'supervisor del evaluador':
        $sql = Listados::sql_listar_comentarios_obrero_supervisor($cedulaSesion);
        break;
   
    default:
        echo json_encode(["success" => false, "message" => "Rol no autorizado"]);
        exit;
}

$respuesta = $Lista->listarComentariosEvaluadosObreros($sql);
return $respuesta;
