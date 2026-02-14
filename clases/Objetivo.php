<?php
class Objetivo {
    private $conexion;

    public function __construct($dataCliente=array(''), $conexion = null) {
        if ($conexion != NULL) {
            $this->conexion = $conexion;
        }
    }

    public static function sql_listar(int $evaluado_id, string $id_eval_admin, string $cedula_usuario): string {
        return "
            SELECT 
                odi.id_odi,
                odi.nombre_objetivo, 
                odi.peso_objetivo,
                c.id_eval_admin,
                u.cedula_usuario
            FROM objetivos_desempeno_individual odi
            JOIN contiene c ON odi.id_odi = c.id_odi
            JOIN evaluacion_administrativos ea ON c.id_eval_admin = ea.id_eval_admin
            JOIN usuarios u ON ea.evaluado_id = u.id_usuario
            WHERE ea.evaluado_id = {$evaluado_id}
              AND c.id_eval_admin = {$id_eval_admin}
              AND u.cedula_usuario = '" . addslashes($cedula_usuario) . "';
        ";
    }
  
    public function listar_objetivos(int $evaluado_id, string $id_eval_admin, string $cedula_usuario) {
        if ($this->conexion != NULL) {
            $sql = self::sql_listar($evaluado_id, $id_eval_admin, $cedula_usuario);
            return $this->conexion->ejecutarConsultaBdds($sql);
        }
        return "No se ha definido la conexión";
    }
}