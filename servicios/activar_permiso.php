<?php
header('Content-Type: application/json; charset=utf-8');
include_once "../clases/PermisosUsuario.php";

try {
    $perm = new PermisosUsuario($dataCliente['_post'], $this->conexion);
    $this->ejecutarConsultaBdds($perm->sql_asignar());
    echo json_encode(['success'=>true, 'message'=>'Permiso asignado']);
} catch (Throwable $e) {
    echo json_encode(['success'=>false, 'message'=>$e->getMessage()]);
}
exit;