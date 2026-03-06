<?php

error_reporting(E_ALL);
ini_set('display_errors', '0'); 
ini_set('display_startup_errors', '0');

header('Content-Type: application/json; charset=utf-8');

include_once "../clases/Usuario3.php";

try {
    $usuario = new Usuario($dataCliente['_post'], $this->conexion);

    // ============================================================
    // 1) VALIDAR CÉDULA DUPLICADA
    // ============================================================
    $sqlCedula = $usuario->sql_validar_cedula();
    $existeCedula = $this->ejecutarConsultaBdds($sqlCedula);

    if (count($existeCedula) > 0) {
        echo json_encode([
            'success' => false,
            'message' => '❌ Ya existe un usuario registrado con esta cédula.'
        ]);
        exit;
    }

    // ============================================================
    // 2) VALIDAR CARGO ÚNICO (solo un usuario activo por cargo)
    // ============================================================
   
$sqlCargo = $usuario->sql_validar_cargo_unico();
$existeCargo = $this->ejecutarConsultaBdds($sqlCargo);

if (count($existeCargo) > 0) {
    echo json_encode([
        'success' => false,
        'message' => '❌ Ya existe un usuario ACTIVO asignado a este cargo.'
    ]);
    exit;
}

    // ============================================================
    // 3) INSERTAR NUEVO USUARIO
    // ============================================================
    $sqlInsert = $usuario->sql_guardar();
    $this->ejecutarConsultaBdds($sqlInsert);

    echo json_encode([
        'success' => true,
        'message' => '✅ Usuario creado con éxito'
    ]);

} catch (Throwable $e) {
    echo json_encode([
        'success' => false,
        'message' => 'Error en el servidor: ' . $e->getMessage()
    ]);
}
exit;