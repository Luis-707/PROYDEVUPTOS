<?php
header('Content-Type: application/json; charset=utf-8');
include_once "../clases/ContieneObjetivo.php";

try {
    $listObj = new ContieneObjetivo($dataCliente['_post'], $this->conexion);
    $respuesta = $this->ejecutarConsultaBdds($listObj->sql_listar_odi());
    echo json_encode($respuesta ?: []);
} catch (Throwable $e) {
    echo json_encode(['success'=>false, 'message'=>$e->getMessage()]);
}
exit;