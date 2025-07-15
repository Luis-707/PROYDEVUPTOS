<?php
/**
 *  Servicio para registrar, editar, listar y eliminar registros de persona
 * 
 */
/* para ejecutar sqls: $respuesta = $this->ejecutarConsultaBdds($sql); */
/* array  $dataCliente: contiene los datos enviados desde las vista o interfaz*/


// print_r($dataCliente);
// return $dataCliente;


switch ($dataCliente['_post']['accion']) {
    case 'list':
        $sql = "SELECT * from persona ORDER BY cedula;";
        $respuesta = $this->ejecutarConsultaBdds($sql);
        return $respuesta;
    case 'search':
        $sql = sprintf("SELECT * from persona where id_persona = '%s';", $dataCliente['_post']['id_persona']);
        $respuesta = $this->ejecutarConsultaBdds($sql);
        return $respuesta;
    case 'create':
        $sql = sprintf("insert into persona  (id_persona, cedula, nombre_apellido, email, telefono)  values ('%s','%s','%s','%s', '%s')",
            uniqid('PER-'),
            $dataCliente['_post']['cedula'],
            $dataCliente['_post']['nombre_apellido'],
            $dataCliente['_post']['email'],
            $dataCliente['_post']['telefono']);
        $respuesta = $this->ejecutarConsultaBdds($sql);
        return json_encode($respuesta);
    case 'update':
        $sql = sprintf("UPDATE persona SET cedula = '%s', nombre_apellido = '%s', email = '%s', telefono = '%s' WHERE id_persona = '%s'",
            $dataCliente['_post']['cedula'],
            $dataCliente['_post']['nombre_apellido'],
            $dataCliente['_post']['email'],
            $dataCliente['_post']['telefono'],
            $dataCliente['_post']['id_persona']);
        $respuesta = $this->ejecutarConsultaBdds($sql);
        return json_encode($respuesta);
    case 'delete':
        $sql = sprintf("DELETE FROM persona WHERE id_persona = '%s'", $dataCliente['_post']['id_persona']);
        $respuesta = $this->ejecutarConsultaBdds($sql);
        return json_encode($respuesta);
    default:
        // Handle invalid action
        return 'Invalid action';
}


?>