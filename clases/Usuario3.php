<?php

class Usuario {
    // Propiedades privadas
    protected $conexion;
    protected $id_cargo = 0;
    protected $id_uf = 0;
    protected $clave = "";
    protected $id_usuario = 0;
    protected $cedula_usuario = "";
    //protected $rol_id = "";
    protected $nombre_completo = "";
    protected $tipo_empleado = "";
    protected $ubicacion_administrativa = "";
    protected $fecha_ingreso = "";
    protected $estado_usuario = "";

    // Constructor
    public function __construct($dataCliente = array(''), $conexion = NULL) {
        if (isset($dataCliente['id_cargo'])) {
            $this->id_cargo = $dataCliente['id_cargo'];
        }
        if (isset($dataCliente['id_uf'])) {
            $this->id_uf = $dataCliente['id_uf'];
        }
        if (isset($dataCliente['clave'])) {
            $this->clave = $dataCliente['clave'];
        }
        if (isset($dataCliente['cedula_usuario'])) {
            $this->cedula_usuario = $dataCliente['cedula_usuario'];
        }
        if (isset($dataCliente['nombre_completo'])) {
            $this->nombre_completo = $dataCliente['nombre_completo'];
        }
        if (isset($dataCliente['tipo_empleado'])) {
            $this->tipo_empleado = $dataCliente['tipo_empleado'];
        }
        if (isset($dataCliente['ubicacion_administrativa'])) {
            $this->ubicacion_administrativa = $dataCliente['ubicacion_administrativa'];
        }
        /*if (isset($dataCliente['rol_id'])) {
            $this->rol_id = $dataCliente['rol_id'];
        }*/
        if (isset($dataCliente['fecha_ingreso'])) {
            $this->fecha_ingreso = $dataCliente['fecha_ingreso'];
        }
        if (isset($dataCliente['estado_usuario'])) {
            $this->estado_usuario = $dataCliente['estado_usuario'];
        }
        if (isset($dataCliente['id_usuario'])) {
            $this->id_usuario = $dataCliente['id_usuario'];
        }
        if ($conexion != NULL) {
            $this->conexion = $conexion;
        }
    }

    // Destructor
    public function __destruct() {
        // No implementación específica necesaria
    }

    // Método para guardar (INSERT)
    public function sql_guardar(): string {

        $hash = password_hash($this->clave, PASSWORD_DEFAULT);

        return sprintf(
            "INSERT INTO usuarios (id_cargo, id_uf, clave, cedula_usuario, nombre_completo, tipo_empleado, ubicacion_administrativa, fecha_ingreso, estado_usuario) VALUES (%d, %d, '%s', %d, '%s', '%s', '%s', '%s', '%s');",
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

    // Método para actualizar (UPDATE) según cedula_usuario
    public function sql_actualizar(): string {

        $hash = password_hash($this->clave, PASSWORD_DEFAULT);
        return sprintf(
            "UPDATE usuarios SET id_cargo = '%d', id_uf = '%d', clave = '%s', nombre_completo = '%s', tipo_empleado = '%s', ubicacion_administrativa = '%s', fecha_ingreso = '%s' WHERE cedula_usuario = '%d';",
            $this->id_cargo,
            $this->id_uf,
            $hash,
            $this->nombre_completo,
            $this->tipo_empleado,
            $this->ubicacion_administrativa,
            $this->fecha_ingreso,
            $this->cedula_usuario
        );
    }

    // Método para actualizar el estado del usuario (Activo, Inactivo) según id_usuario
    public function sql_actualizar_estado_usuario(): string {
        return sprintf(
            "UPDATE usuarios SET estado_usuario = '%s' WHERE id_usuario = %d;",
            $this->estado_usuario,
            $this->id_usuario
        );
    }

    // Método para listar todos (SELECT)
    public static function sql_listar(): string {
        return "SELECT 
            u.id_usuario,
            u.clave,
            u.cedula_usuario,
            u.nombre_completo,
            u.ubicacion_administrativa,
            u.tipo_empleado,
            uf.id_uf,
            uf.nombre_ubicacion,
            c.id_cargo,
            c.nombre_cargo,
            u.estado_usuario,
            u.fecha_ingreso
        FROM usuarios u
        INNER JOIN cargos c ON u.id_cargo = c.id_cargo
        INNER JOIN ubicacion_fisica uf ON u.id_uf = uf.id_uf
        WHERE c.es_jefe = TRUE
        ORDER BY u.nombre_completo;";
    }

    // Método para listar todos ejecutando la consulta usando $conexion
    public function listar() {
        if ($this->conexion != NULL) {
            return $this->conexion->ejecutarConsultaBdds(self::sql_listar());
        }
        return "No se ha definido la conexión";
    }

    // Método para buscar por clave primaria cedula_usuario
    public function sql_buscar(): string {
        return sprintf(
            "SELECT 
            u.id_usuario,
            u.cedula_usuario,
            u.clave,
            u.id_cargo,
            u.estado_usuario,
            r.rol AS nombre_rol
        FROM usuarios u
        JOIN posee_rol pr ON pr.id_usuario = u.id_usuario
        JOIN roles_sistema r ON r.rol_id = pr.rol_id
        WHERE u.cedula_usuario = '%s';",
            addslashes($this->cedula_usuario)
        );
    }  

    public function sql_validar_cedula(): string {
    return sprintf(
        "SELECT id_usuario FROM usuarios WHERE cedula_usuario = '%s' LIMIT 1;",
        addslashes($this->cedula_usuario)
    );
}

public function sql_validar_cargo_unico(): string {
    return sprintf(
        "SELECT id_usuario 
         FROM usuarios 
         WHERE id_cargo = %d 
         AND estado_usuario = 'Activo'
         LIMIT 1;",
        $this->id_cargo
    );
}



    // Método para consultar datos para el perfil de usuario
    public function sql_perfil_usuario(): string {
        return sprintf(
            "SELECT 
            u.cedula_usuario,
            u.nombre_completo,
            u.ubicacion_administrativa,
            uf.nombre_ubicacion AS ubicacion_fisica,
            org.nombre AS area_ocupacional,
            u.fecha_ingreso,
            c.nombre_cargo AS cargo_usuario,
            org.nombre AS nombre_organizacion,
            STRING_AGG(r.rol, ', ') AS rol

        FROM usuarios u
        JOIN cargos c 
            ON c.id_cargo = u.id_cargo
        JOIN organizaciones org
            ON org.id_org = c.id_org
         JOIN posee_rol pr
            ON pr.id_usuario = u.id_usuario
         JOIN ubicacion_fisica uf ON u.id_uf = uf.id_uf
         JOIN roles_sistema r
            ON r.rol_id = pr.rol_id

        WHERE u.cedula_usuario = '%d'

        GROUP BY 
            u.cedula_usuario,
            u.nombre_completo,
            u.ubicacion_administrativa,
            uf.nombre_ubicacion,
            org.nombre,
            u.fecha_ingreso,
            c.nombre_cargo,
            org.nombre;",
            $this->cedula_usuario
        );
    }

    // Método para buscar por id_usuario
    public function sql_buscar_usuario_por_id(): string {
        return sprintf(
            "SELECT * FROM usuarios WHERE id_usuario = %d;",
            $this->id_usuario
        );
    }

    // Método para listar ubicacion fisica
    public function sql_listar_ubicacionFisica(): string {
        return sprintf(
            "SELECT * FROM ubicacion_fisica;",
        );
    }

    //Método para listar ubicacion administrativa usando el metodo ejecutarConsultaBdds
    public function listarUbicacionFisica() {
        if ($this->conexion != NULL) {
            return $this->conexion->ejecutarConsultaBdds(self::sql_listar_ubicacionFisica());
        }
        return "No se ha definido la conexión";
    }

    //Métdo para listar cargos
    public function sql_cargos(): string {
        return sprintf(
            "SELECT * FROM cargos WHERE es_jefe = TRUE;",
        );
    }

    // Método para validar si el cargo está disponible (solo 1 activo por cargo)
    /*public function sql_validar_cargo_disponible(): string {
        return sprintf(
            "SELECT COUNT(*) as total FROM usuarios 
            WHERE id_cargo = %d AND estado_usuario = 'Activo'",
            $this->id_cargo
        );
    }*/


    //Método para listar cargos usando el metodo ejecutarConsultaBdds
    public function listarCargos() {
        if ($this->conexion != NULL) {
            return $this->conexion->ejecutarConsultaBdds(self::sql_cargos());
        }
        return "No se ha definido la conexión";
    }

    // Getters
    public function getClave(): string {
        return $this->clave;
    }

    public function getCedulaUsuario(): string {
        return $this->cedula_usuario;
    }
}
?>