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

        if ($row['estado_usuario'] === 'Inactivo') {
            echo json_encode([
                "success" => false,
                "message" => "Usuario inactivo. No puede acceder al sistema.",
                "type" => "inactive"
            ]);
            exit;
        }
        
        

        if (password_verify($clave, $row['clave'])) {
            if ($pin !== null && $pin !== $row['cedula_usuario']) {
                echo json_encode([
                    "success" => false,
                    "message" => "PIN no coincide con la cédula registrada"
                ]);
                exit;
            }

            $_SESSION['usuario'] = [
                'id_usuario' => $row['id_usuario'],
                'cedula'     => $row['cedula_usuario'],
                'rol_id'     => $row['rol_id'],
                'rol'        => strtolower(trim($row['rol']))
            ];

            echo json_encode([
                "success"   => true,
                "message"   => "Bienvenido",
                "id_usuario"=> $row['id_usuario'],
                "cedula"    => $row['cedula_usuario'],
                "rol_id"    => $row['rol_id'],
                "rol"       => strtolower(trim($row['rol']))
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
