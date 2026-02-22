<?php
// Habilitar reporte de errores
//error_reporting(E_ALL);
ini_set('display_errors', '1');
//ini_set('display_startup_errors', '1');
/*
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

$subordinado = new EvaluacionesAdministrativos([], $this);

// Pasar el ID del usuario autenticado al método sql_listar_evaluadosAd
$sql = $subordinado->sql_listar_evaluadosAd($idUsuarioSesion);
             
$respuesta = $this->ejecutarConsultaBdds($sql);
return $respuesta;
*/


session_start();

// Validar usuario en sesión
$id_usuario = $_SESSION['usuario']['id_usuario'] ?? null;
$rolesUsuario = $_SESSION['usuario']['roles'] ?? [];

// Verificar autenticación básica
if (!$id_usuario) {
    echo json_encode(["success" => false, "message" => "Usuario no autenticado"]);
    exit;
}

// Verificar si tiene rol 'evaluador' (minúsculas por normalización)
if (!in_array('evaluador', $rolesUsuario)) {
    echo json_encode(["success" => false, "message" => "Usuario sin rol Evaluador requerido"]);
    exit;
}

include_once "../clases/EvaluacionAdministrativos.php";
$subordinado = new EvaluacionesAdministrativos([], $this);

// Decidir método basado en roles (todo minúsculas)
if (in_array('supervisor del evaluador', $rolesUsuario)) {
    $sql = $subordinado->sql_listar_evaluadosES($id_usuario);  // Supervisor
} else {
    $sql = $subordinado->sql_listar_evaluadosAd($id_usuario);  // Solo Evaluador
}

$respuesta = $this->ejecutarConsultaBdds($sql);
return $respuesta;