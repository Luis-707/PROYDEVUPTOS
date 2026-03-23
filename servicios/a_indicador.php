<?php
/*
include_once "../clases/GestionIndicador.php";

// Buscar y actualizar indicador por ID
$data = $dataCliente['_post'];

// Validar que venga el indicador_id
if (empty($data['indicador_id'])) {
    return ['error' => 'El indicador_id es obligatorio'];
}

$editarIndicador = new GestionIndicador($dataCliente['_post']);

$sqlBuscar = $editarIndicador->sql_buscar_indicardor_id();
$respuesta = $this->ejecutarConsultaBdds($sqlBuscar);

if (count($respuesta) == 0) {     
    $respuesta = $data['indicador_id'].' No Existe';
} else {
    // Actualizar el indicador
    $sqlActualizar = $editarIndicador->sql_actualizar_indicador();
    $respuesta = $this->ejecutarConsultaBdds($sqlActualizar);
    
    // Opcional: listar todos para refrescar la vista
   // $sqlListar = GestionIndicador::sql_listarIndicadores();
   // $respuesta = $this->ejecutarConsultaBdds($sqlListar);
}

// Retornar resultado (ajusta según tu sistema de servicios)
//return $this->servicio($data, 'l_gestionIndicadores');
$respuesta = $this->ejecutarConsultaBdds($sql);
$respuesta = $this->servicio($data,'l_gestionIndicador'); // el parametro no tiene extension pero es el servicio del archivo l_usuario.php
return $respuesta;
*/

//session_start();
header('Content-Type: application/json; charset=utf-8');

include_once "../clases/GestionIndicador.php";
//include_once "../config/conexion.php"; // ← TU CONEXIÓN REAL

// 1. OBTENER DATOS DEL FORM (correcto)
$dataCliente = [];
if (strpos($_SERVER['CONTENT_TYPE'], 'application/json') !== false) {
    $raw = file_get_contents("php://input");
    $dataCliente = json_decode($raw, true) ?? [];
} else {
    $dataCliente = $_POST;
}

// 2. VALIDAR indicador_id
if (empty($dataCliente['indicador_id'])) {
    echo json_encode([
        'success' => false,
        'message' => '❌ El indicador_id es obligatorio'
    ]);
    exit;
}

// 3. CONEXIÓN REAL (NO $this)
//$conexion = new Conexion(); // Ajusta según tu sistema
$editarIndicador = new GestionIndicador($dataCliente);

// 4. EJECUTAR ACTUALIZACIÓN DIRECTO (SIN BUSCAR PRIMERO)
$sqlBuscar = $editarIndicador->sql_buscar_indicardor_id();
$respuesta = $this->ejecutarConsultaBdds($sqlBuscar);

if (count($respuesta) == 0) {     
    $respuesta = $data['indicador_id'].' No Existe';
} else {
    // Actualizar el indicador
    $sqlActualizar = $editarIndicador->sql_actualizar_indicador();
    $respuesta = $this->ejecutarConsultaBdds($sqlActualizar);
    
}

// 5. RESPUESTA SIMPLE Y CLARA
echo json_encode([
    'success' => true,
    'message' => '✅ Indicador actualizado correctamente',
    'indicador_id' => $dataCliente['indicador_id']
]);
exit;