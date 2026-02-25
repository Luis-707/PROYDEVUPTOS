<?php

include_once "../clases/Objetivo.php";

$dataCliente = ['_post' => $_POST];

if (
    empty($dataCliente['_post']['evaluado_id']) ||
    empty($dataCliente['_post']['id_eval_admin']) ||
    empty($dataCliente['_post']['cedula_usuario'])
) {
    echo json_encode([
        'success' => false,
        'message' => 'Faltan datos: evaluado_id, id_eval_admin o cedula_usuario'
    ]);
    exit;
}

$evaluado_id = (int)$dataCliente['_post']['evaluado_id'];
$idEvalAdmin = (int)$dataCliente['_post']['id_eval_admin'];
$cedula = $dataCliente['_post']['cedula_usuario'];

$objetivo = new Objetivo([], $this);

$respuesta = $objetivo->listar_objetivos($evaluado_id, $idEvalAdmin, $cedula);

echo json_encode([
    'success' => true,
    'data' => $respuesta
]);
exit;
