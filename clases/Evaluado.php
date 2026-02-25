<?php

require_once 'Usuario3.php';

class Evaluado extends Usuario {
    public function __construct($dataCliente = array(''), $conexion = NULL) {
        $dataCliente['rol_id'] = 4; // Rol Evaluado fijo por polimorfismo
        parent::__construct($dataCliente, $conexion);
    }

    public function setIdUsuario($id) { 
        $this->id_usuario = (int)$id; 
    }

    // *** POLIMORFISMO: Sobreescribe sql_listar() ***
    public static function sql_listar_sub(int $idUsuario): string {
        return sprintf("
            SELECT 
                sub.ID_USUARIO AS id,
                sub.clave,
                sub.NOMBRE_COMPLETO AS subordinado,
                sub.cedula_usuario AS cedula_usuario,
                sub.fecha_ingreso,
                sub.estado_usuario,
                sub.id_uf,
                sub.ubicacion_administrativa,
                sub.tipo_empleado,
                uf.nombre_ubicacion,
                c.NOMBRE_CARGO AS cargo,
                c.id_cargo,
                uh.NOMBRE AS unidad,
                c.ES_JEFE AS es_jefe
            FROM USUARIOS jefe
            JOIN CARGOS cj ON jefe.ID_CARGO = cj.ID_CARGO
            JOIN ORGANIZACIONES uj ON cj.ID_ORG = uj.ID_ORG
            JOIN ORGANIZACIONES uh ON (uh.PADRE_ID = uj.ID_ORG OR uh.ID_ORG = uj.ID_ORG)
            JOIN CARGOS c ON c.ID_ORG = uh.ID_ORG
            JOIN USUARIOS sub ON sub.ID_CARGO = c.ID_CARGO 
                
            LEFT JOIN ubicacion_fisica uf ON sub.id_uf = uf.id_uf
            WHERE jefe.ID_USUARIO = %d
                AND sub.ID_USUARIO != jefe.ID_USUARIO
            ORDER BY uh.NOMBRE, c.ES_JEFE DESC;", $idUsuario);
        }

    // *** POLIMORFISMO: Nombres específicos para Evaluado ***
    /*public function sql_guardar(): string {
        return sprintf("INSERT INTO usuarios (clave, cedula_usuario, nombre_completo, tipo_empleado, ubicacion_administrativa, rol_id, estado_usuario) VALUES ('%s', %d, '%s', '%s', '%s', %d, '%s') RETURNING id_usuario;",
            addslashes($this->clave), addslashes($this->cedula_usuario), addslashes($this->nombre_completo),
            addslashes($this->tipo_empleado), addslashes($this->ubicacion_administrativa), (int)$this->rol_id, 'Activo');
    }
    
    public function sql_actualizar(): string {
        return sprintf("UPDATE usuarios SET clave = '%s', rol_id = '%d', nombre_completo = '%s', tipo_empleado = '%s', ubicacion_administrativa = '%s' WHERE cedula_usuario = '%d';",
            $this->clave, $this->rol_id, $this->nombre_completo, $this->tipo_empleado, $this->ubicacion_administrativa, $this->cedula_usuario);
    }*/

    
    public function sql_guardar(): string {

        $hash = password_hash($this->clave, PASSWORD_DEFAULT);


        return sprintf(
            "INSERT INTO usuarios (id_cargo, id_uf, clave, cedula_usuario, nombre_completo, tipo_empleado, ubicacion_administrativa, fecha_ingreso, estado_usuario) VALUES (%d, %d, '%s', %d, '%s', '%s', '%s', '%s', '%s') RETURNING id_usuario;",
            $this->id_cargo,
            $this->id_uf,
            $hash,
            $this->cedula_usuario,
            $this->nombre_completo,
            $this->tipo_empleado,
            $this->ubicacion_administrativa,
            $this->fecha_ingreso,
            ('Activo')
        );
    }

    //Métdo para listar cargos sobreescrito
    public static function sql_cargos_sub(int $idCargo): string {
        return sprintf(
            "SELECT c.id_cargo, c.nombre_cargo
            FROM cargos c
            WHERE EXISTS (
                SELECT 1 FROM cargos ref 
                WHERE ref.id_cargo = %d 
                AND (c.id_org = ref.id_org OR c.id_org IN (
                    SELECT id_org FROM organizaciones WHERE padre_id = ref.id_org
                ))
            ) AND es_jefe = FALSE
            ORDER BY c.nombre_cargo;", $idCargo
        );
    }

    /*public function listarCargosSub() {
        if ($this->conexion != NULL) {
            return $this->conexion->ejecutarConsultaBdds($this->sql_cargos_sub());
        }
        return "No se ha definido la conexión";
    }*/

    /*public function listarEvaluados() {
        if ($this->conexion != NULL) {
            return $this->conexion->ejecutarConsultaBdds(self::sql_listar());
        }
        return "No se ha definido la conexión";
    }*/

    public function sql_guardar_permiso($permisos_id) {
        return sprintf("INSERT INTO posee_permisos (permisos_id, id_usuario) VALUES (%d, %d);", $permisos_id, $this->id_usuario);
    }

    public function sql_eliminar_permiso(): string {
        return sprintf("DELETE FROM posee_permisos WHERE id_usuario = %d;", $this->id_usuario);
    }

    public static function sql_buscar_permiso_comentarios() {
        return "SELECT permisos_id FROM permisos WHERE nombre_permiso = 'Comentarios' LIMIT 1;";
    }

    public function sql_buscar_usuario_eval_id(): string {
        return sprintf("SELECT u.id_usuario, u.cedula_usuario, u.clave, u.estado_usuario, r.rol_id, r.rol FROM usuarios u JOIN roles_sistema r ON u.rol_id = r.rol_id WHERE id_usuario = %d;", $this->id_usuario);
    }

    public function sql_validar_cedula_evaluado(): string {
    return sprintf(
        "SELECT id_usuario FROM usuarios WHERE cedula_usuario = '%s' LIMIT 1;",
        addslashes($this->cedula_usuario)
    );
}

    /*public function sql_buscar_user_evaluado(): string {
        return sprintf(
            "SELECT u.id_usuario, u.cedula_usuario, u.clave, r.rol_id, r.rol
             FROM usuarios u
             JOIN roles_sistema r ON u.rol_id = r.rol_id
             WHERE u.cedula_usuario = '%s';",
            addslashes($this->cedula_usuario)
        );
    }*/
}

?>
