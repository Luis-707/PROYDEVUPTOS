<?php

include_once "../clases/Competencia.php";

$competencia = new Competencia([], $this);

$respuesta = $competencia->listar_competencias();

echo json_encode([
    'success' => true,
    'data' => $respuesta
]);
exit;
