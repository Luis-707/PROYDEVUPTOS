<?php

include_once '../clases/RolesSistema.php';

/*$cargoSupervisor = new CargoSupervisor($conexion);
$cargos = $cargoSupervisor->listarCargos();

if (is_string($cargos)) {
    // Aquí cae cuando hay error, muestra el mensaje exacto
    echo "Error: " . $cargos;
} elseif (empty($cargos)) {
    // Si no hay datos
    echo "No hay cargos disponibles.";
} else {
    // Aquí ya tienes los datos para generar el select
    foreach ($cargos as $cargo) {
        echo "<option value='{$cargo['id']}'>{$cargo['nombre_cargo']}</option>";
    }
}*/

// Instanciar la clase con la conexión
$Roles = new RolesSistema([],$this);

// Obtener las opciones HTML para el select
$respuesta = $Roles->listarRoles();

return $respuesta;
?>