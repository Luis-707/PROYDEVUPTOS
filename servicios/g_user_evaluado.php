<?php

error_reporting(E_ALL);
ini_set('display_errors', '0'); 
ini_set('display_startup_errors', '0');

header('Content-Type: application/json; charset=utf-8');

include_once "../clases/Evaluado.php";

try {
    $evaluado = new Evaluado($dataCliente['_post'], $this->conexion);

    // 1) Buscar si el usuario evaluado ya existe
    $sql = $evaluado->sql_buscar();  // ✅ Heredado de Usuario
    $respuesta = $this->ejecutarConsultaBdds($sql);

    if (count($respuesta) == 0) {
        // 2) Insertar nuevo usuario evaluado
        $sql = $evaluado->sql_guardar();  // ✅ Polimórfico (PostgreSQL RETURNING)
        $respInsert = $this->ejecutarConsultaBdds($sql);

        if (empty($respInsert) || empty($respInsert[0][0]['id_usuario'])) {
            echo json_encode([
                'success' => false,
                'message' => '❌ No se pudo crear el usuario evaluado'
            ]);
            exit;
        }

        $nuevoIdUsuario = (int)$respInsert[0][0]['id_usuario'];
        $evaluado->setIdUsuario($nuevoIdUsuario);

        // 3) Buscar permiso "Comentarios"
        $sqlPermiso = Evaluado::sql_buscar_permiso_comentarios();
        $respPermiso = $this->ejecutarConsultaBdds($sqlPermiso);

        if (empty($respPermiso) || empty($respPermiso[0][0]['permisos_id'])) {
            echo json_encode([
                'success' => false,
                'message' => '❌ No se encontró el permiso Comentarios'
            ]);
            exit;
        }

        $permisoId = (int)$respPermiso[0][0]['permisos_id'];

        // 4) Insertar en posee_permisos
        $sqlPosee = $evaluado->sql_guardar_permiso($permisoId);
        $this->ejecutarConsultaBdds($sqlPosee);

        // 5) Respuesta final
        echo json_encode([
            'success'    => true,
            'message'    => '✅ Usuario creado con éxito y permiso asignado',
            'id_usuario' => $nuevoIdUsuario,
            'permisos_id'=> $permisoId
        ]);

    } else {
        // Ya existe el usuario evaluado
        echo json_encode([
            'success' => false,
            'message' => 'Ya existe un usuario para este empleado'
        ]);
    }

} catch (Throwable $e) {
    echo json_encode([
        'success' => false,
        'message' => 'Error en el servidor: ' . $e->getMessage()
    ]);
}
exit;
