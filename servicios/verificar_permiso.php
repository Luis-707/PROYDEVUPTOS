<?php
header('Content-Type: application/json; charset=utf-8');
include_once "../clases/PermisosUsuario.php";

try {
    $perm = new PermisosUsuario($dataCliente['_post'], $this->conexion);
    $nombre_permiso = $dataCliente['_post']['nombre_permiso'] ?? '';
    $sql = $perm->sql_verificar($nombre_permiso);
    $respuesta = $this->ejecutarConsultaBdds($sql);
    echo json_encode(['acceso' => !empty($respuesta)]);
} catch (Throwable $e) {
    echo json_encode(['success'=>false, 'message'=>$e->getMessage()]);
}
exit;