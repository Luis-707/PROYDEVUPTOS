<?php

include_once "../clases/Objetivo.php";

// Obtener datos enviados (POST o JSON)
$dataCliente = ['_post' => $_POST];
if (empty($dataCliente['_post'])) {
    $json = file_get_contents("php://input");
    $dataCliente['_post'] = json_decode($json, true) ?? [];
}

// Validar que venga la cédula y el periodo
if (empty($dataCliente['_post']['cedula_usuario']) || empty($dataCliente['_post']['id_eval_admin'])) {
    echo json_encode([
        'success' => false,
        'message' => 'Faltan datos: cédula o periodo de evaluación'
    ]);
    exit;
}

$cedula = $dataCliente['_post']['cedula_usuario'];
$idEvalAdmin = $dataCliente['_post']['id_eval_admin'];

// Instanciar clase Objetivo con la conexión
$objetivo = new Objetivo([], $this);

// Obtener lista filtrada de objetivos
$respuesta = $objetivo->listar_objetivos($cedula, $idEvalAdmin);

// Responder en JSON
echo json_encode([
    'success' => true,
    'data' => $respuesta
]);
exit;
?>