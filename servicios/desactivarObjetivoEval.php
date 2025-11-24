<?php
header('Content-Type: application/json; charset=utf-8');
include_once "../clases/ContieneObjetivo.php";

try {
    $desactivar = new ContieneObjetivo($dataCliente['_post'], $this->conexion);
    $this->ejecutarConsultaBdds($desactivar->sql_quitar_objetivo());
    echo json_encode(['success'=>true, 'message'=>'Objetivo revocado']);
} catch (Throwable $e) {
    echo json_encode(['success'=>false, 'message'=>$e->getMessage()]);
}
exit;