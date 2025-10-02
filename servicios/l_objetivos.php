<?php

include_once "../clases/Objetivo.php";

// Obtener datos enviados (POST o JSON)
$dataCliente = ['_post' => $_POST];
if (empty($dataCliente['_post'])) {
    $json = file_get_contents("php://input");
    $dataCliente['_post'] = json_decode($json, true) ?? [];
}

// Validar que venga la cédula
if (empty($dataCliente['_post']['cedula_usuario'])) {
    echo json_encode([
        'success' => false,
        'message' => 'No se recibió la cédula del evaluado'
    ]);
    exit;
}

$cedula = $dataCliente['_post']['cedula_usuario'];

// Instanciar clase Objetivo con la conexión ($this debe tener la conexión definida)
$objetivo = new Objetivo([], $this);

// Obtener lista filtrada de objetivos para la cédula recibida
$respuesta = $objetivo->listar_objetivos($cedula);

// Responder en JSON
echo json_encode([
    'success' => true,
    'data' => $respuesta
]);
exit;
?>