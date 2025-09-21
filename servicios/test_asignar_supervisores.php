<?php
error_reporting(E_ALL);
ini_set('display_errors', '1');
ini_set('display_startup_errors', '1');

header('Content-Type: application/json; charset=utf-8');

include_once "../clases/Asignar_Supervisores.php";

try {
    // 🔹 1. Conexión directa a PostgreSQL
    $conn = pg_connect("host=localhost dbname=odi2 user=postgres password=Limitronia port=5432");
    if (!$conn) {
        throw new Exception("No se pudo conectar a la base de datos");
    }

    // 🔹 2. Datos simulados como si vinieran del formulario
    $datosPrueba = [
        'id_supervisor' => 16, // Cambia por un ID válido
        'evaluadores' => [4]   // Cambia por un evaluador válido
    ];

    // 🔹 3. Instanciar la clase con la conexión directa
    $asignacion = new AsignarSupervisores($datosPrueba, $conn);

    $resultados = [];

    // 4️⃣ Probar sql_buscar_asignacion()
    $sqlBuscar = $asignacion->sql_buscar_asignacion(4);
    if (empty($sqlBuscar) || !is_string($sqlBuscar)) {
        $resultados['buscar'] = 'ERROR: sql_buscar_asignacion no devolvió un SQL válido';
    } else {
        $resBuscar = pg_query($conn, $sqlBuscar);
        if ($resBuscar === false) {
            $resultados['buscar'] = 'ERROR: pg_query falló en BUSCAR - ' . pg_last_error($conn);
        } else {
            $resultados['buscar'] = pg_fetch_all($resBuscar) ?: [];
        }
    }

    // 5️⃣ Probar sql_guardar_asignacion()
    $sqlGuardar = $asignacion->sql_guardar_asignacion(4);
    if (empty($sqlGuardar) || !is_string($sqlGuardar)) {
        $resultados['guardar'] = 'ERROR: sql_guardar_asignacion no devolvió un SQL válido';
    } else {
        $resGuardar = pg_query($conn, $sqlGuardar);
        if ($resGuardar === false) {
            $resultados['guardar'] = 'ERROR: pg_query falló en GUARDAR - ' . pg_last_error($conn);
        } else {
            $resultados['guardar'] = 'Inserción ejecutada correctamente';
        }
    }

    echo json_encode([
        'success' => true,
        'resultados' => $resultados,
        'sql_buscar' => $sqlBuscar,
        'sql_guardar' => $sqlGuardar
    ]);

} catch (Throwable $e) {
    echo json_encode([
        'success' => false,
        'message' => 'Error en el servidor: ' . $e->getMessage()
    ]);
}
exit;