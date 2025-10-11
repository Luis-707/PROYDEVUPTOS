<?php
session_start();
include_once "../clases/Usuario3.php";

header('Content-Type: application/json; charset=utf-8');

try {
    $cedula = $_POST['cedula_usuario'] ?? '';
    $clave  = $_POST['clave'] ?? '';
    $jsonExtra = $_POST['extra'] ?? '';

    $pin = null;
    if ($jsonExtra !== '') {
        $decoded = json_decode($jsonExtra, true);
        if (json_last_error() === JSON_ERROR_NONE && isset($decoded['pin'])) {
            $pin = $decoded['pin'];
        }
    }

    if ($cedula === '' || $clave === '') {
        echo json_encode([
            "success" => false,
            "message" => "Faltan datos"
        ]);
        exit;
    }

    $usuario = new Usuario($_POST, $this->conexion);
    $sql = $usuario->sql_buscar();
    $respuesta = $this->ejecutarConsultaBdds($sql);

    if ($respuesta && count($respuesta[0]) > 0) {
        $row = $respuesta[0][0];

        if ($row['clave'] === $clave) {
            if ($pin !== null && $pin !== $row['cedula_usuario']) {
                echo json_encode([
                    "success" => false,
                    "message" => "PIN no coincide con la cédula registrada"
                ]);
                exit;
            }

            // Guardar en sesión PHP
            $_SESSION['usuario'] = [
                'id_usuario' => $row['id_usuario'],
                'cedula'     => $row['cedula_usuario'],
                'rol_id'     => $row['rol_id']
            ];

            // Devolver también id_usuario
            echo json_encode([
                "success"   => true,
                "message"   => "Bienvenido",
                "id_usuario"=> $row['id_usuario'],
                "cedula"    => $row['cedula_usuario'],
                "rol_id"    => $row['rol_id']
            ]);
        } else {
            echo json_encode([
                "success" => false,
                "message" => "Clave incorrecta"
            ]);
        }
    } else {
        echo json_encode([
            "success" => false,
            "message" => "Usuario no encontrado"
        ]);
    }
} catch (Throwable $e) {
    echo json_encode([
        "success" => false,
        "message" => "Error en el servidor: " . $e->getMessage()
    ]);
}
exit;