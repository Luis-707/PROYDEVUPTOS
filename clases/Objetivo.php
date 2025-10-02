<?php
class Objetivo {
    private $conexion;

    public function __construct($dataCliente=array(''), $conexion = null) {
        if ($conexion != NULL) {
            $this->conexion = $conexion;
        }
    }

    public static function sql_listar(string $cedula_usuario): string {
        return "SELECT 
            odi.nombre_objetivo, 
            odi.peso_objetivo,
            c.id_eval_admin,
            u.cedula_usuario
        FROM 
            objetivos_desempeno_individual odi
        JOIN 
            contiene c ON odi.id_odi = c.id_odi
        JOIN 
            evaluacion_administrativos ea ON c.id_eval_admin = ea.id_eval_admin
        JOIN 
            evaluados e ON ea.id_evaluado = e.id_evaluado
        JOIN 
            usuarios u ON e.id_usuario = u.id_usuario
        WHERE 
            u.cedula_usuario = '" . addslashes($cedula_usuario) . "'";
    }

    public function listar_objetivos(string $cedula_usuario) {
        if ($this->conexion != NULL) {
            $sql = self::sql_listar($cedula_usuario);
            return $this->conexion->ejecutarConsultaBdds($sql);
        }
        return "No se ha definido la conexión";
    }
}