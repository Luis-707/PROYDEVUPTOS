<?php
// Habilitar reporte de errores
//error_reporting(E_ALL);
ini_set('display_errors', '1');
//ini_set('display_startup_errors', '1');

// Iniciar sesión para verificar autenticación
session_start();

$idUsuarioSesion = $_SESSION['usuario']['id_usuario'] ?? null;
$cedulaSesion    = $_SESSION['usuario']['cedula'] ?? null;

// Verificar que el usuario esté autenticado (sin rol)
if (!$idUsuarioSesion || !$cedulaSesion) {
    echo json_encode(["success" => false, "message" => "Usuario no autenticado"]);
    exit;
}

include_once "../clases/EvaluacionAdministrativos.php";

$evalAdmin = new EvaluacionesAdministrativos([], $this);

// Pasar el ID del usuario autenticado al método sql_listar_evaluacionesAdmin
$sql = $evalAdmin->sql_listar_evaluacionesAdmin($idUsuarioSesion);
             
$respuesta = $this->ejecutarConsultaBdds($sql);
return $respuesta;
