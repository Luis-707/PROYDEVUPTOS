<?php
session_start();
include_once "../clases/Listados.php";

$cedulaSesion = $_SESSION['usuario']['cedula'] ?? null;
$rolUsuario   = $_SESSION['usuario']['rol'] ?? null;

if (!$cedulaSesion || !$rolUsuario) {
    echo json_encode(["success" => false, "message" => "Usuario no autenticado"]);
    exit;
}

$Evaluado = new Listados($this);

switch ($rolUsuario) {
    case 'evaluador':
        // Solo los evaluados asignados al evaluador en sesión
        $respuesta = $Evaluado->listar_cargos_evaluados($cedulaSesion);
        break;
    /*case 'administrador':
        // Todos los evaluados
        $respuesta = $Evaluado->listaEvaluados(Listados::sql_listar_evaluados());
        break;*/
    default:
        echo json_encode(["success" => false, "message" => "Rol no autorizado"]);
        exit;
}

return $respuesta;