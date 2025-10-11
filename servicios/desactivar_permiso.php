<?php
header('Content-Type: application/json; charset=utf-8');
include_once "../clases/PermisosUsuario.php";

try {
    $perm = new PermisosUsuario($dataCliente['_post'], $this->conexion);
    $this->ejecutarConsultaBdds($perm->sql_revocar());
    echo json_encode(['success'=>true, 'message'=>'Permiso revocado']);
} catch (Throwable $e) {
    echo json_encode(['success'=>false, 'message'=>$e->getMessage()]);
}
exit;