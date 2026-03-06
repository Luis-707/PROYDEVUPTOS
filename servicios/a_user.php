<?php
include_once "../clases/Usuario3.php";

$data = $dataCliente['_post'];
$usuario = new Usuario($dataCliente['_post'], $this->conexion);

// 1) Verificar si el usuario existe
$sqlBuscar = $usuario->sql_buscar();
$respBuscar = $this->ejecutarConsultaBdds($sqlBuscar);

if (count($respBuscar) == 0) {
    echo json_encode([
        'success' => false,
        'message' => $data['cedula_usuario'] . ' no existe'
    ]);
    exit;
}

// 2) VALIDAR CARGO ÚNICO (solo un usuario activo por cargo)
$sqlCargo = $usuario->sql_validar_cargo_unico_edicion();
$existeCargo = $this->ejecutarConsultaBdds($sqlCargo);

if (!empty($existeCargo)) {
    echo json_encode([
        'success' => false,
        'message' => '❌ No puede asignar este cargo: ya está ocupado por un usuario ACTIVO.'
    ]);
    exit;
}

// 3) Actualizar usuario
$sqlUpdate = $usuario->sql_actualizar();
$this->ejecutarConsultaBdds($sqlUpdate);

// 4) Refrescar tabla
echo json_encode([
    'success' => true,
    'message' => 'Usuario actualizado con éxito',
    'data' => $this->servicio($data, 'l_user')
]);
exit;
