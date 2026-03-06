<?php
include_once "../clases/Usuario3.php";

$data = $dataCliente['_post'];
$editarEstadoUser = new Usuario($dataCliente['_post'], $this->conexion);

// 1) Verificar que el usuario exista
$sql = $editarEstadoUser->sql_buscar_usuario_por_id();
$respuesta = $this->ejecutarConsultaBdds($sql);

if (count($respuesta) == 0) {     
    echo json_encode([
        'success' => false,
        'message' => $data['id_usuario'] . ' No Existe'
    ]);
    exit;
}

// ============================================================
// 2) VALIDAR CARGO ÚNICO AL REACTIVAR USUARIO
// ============================================================

if ($data['estado_usuario'] === 'Activo') {

    // Necesitamos cargar el cargo actual del usuario
    $idCargo = $respuesta[0][0]['id_cargo'] ?? null;

    if ($idCargo) {
        // Crear objeto con id_usuario + id_cargo para validar
        $validacion = new Usuario([
            'id_usuario' => $data['id_usuario'],
            'id_cargo'   => $idCargo
        ], $this->conexion);

        // Validar si OTRO usuario activo tiene este cargo
        $sqlCargo = $validacion->sql_validar_cargo_unico_edicion();
        $existeCargo = $this->ejecutarConsultaBdds($sqlCargo);

        if (!empty($existeCargo)) {
            echo json_encode([
                'success' => false,
                'message' => '❌ No puede ACTIVAR este usuario: su cargo ya está ocupado por otro usuario ACTIVO.'
            ]);
            exit;
        }
    }
}

// ============================================================
// 3) Actualizar estado del usuario
// ============================================================

$sql = $editarEstadoUser->sql_actualizar_estado_usuario();
$this->ejecutarConsultaBdds($sql);

// ============================================================
// 4) Refrescar tabla
// ============================================================

echo json_encode([
    'success' => true,
    'message' => 'Estado actualizado correctamente',
    'data' => $this->servicio($data, 'l_user')
]);
exit;