<?php

include_once "../clases/Objetivo.php";

// Obtener datos enviados (POST o JSON)
$dataCliente = ['_post' => $_POST];
if (empty($dataCliente['_post'])) {
    $json = file_get_contents("php://input");
    $dataCliente['_post'] = json_decode($json, true) ?? [];
}

// Validar datos requeridos
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

$evaluado_id   = (int)$dataCliente['_post']['evaluado_id'];
$idEvalAdmin   = (int)$dataCliente['_post']['id_eval_admin'];
$cedula = $dataCliente['_post']['cedula_usuario'];

// Instanciar clase Objetivo con la conexión
$objetivo = new Objetivo([], $this);

// Obtener lista filtrada de objetivos
$respuesta = $objetivo->listar_objetivos($evaluado_id, $idEvalAdmin, $cedula);

// Responder en JSON
echo json_encode([
    'success' => true,
    'data' => $respuesta
]);
exit;