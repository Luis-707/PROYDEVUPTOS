<?php

class DesempenoExcepcional {
    private $conexion;
    private $id_eval_admin = 0;
    private $periodo = "";
    private $fecha = "";

    public function __construct($data = [], $conexion = null) {
        if (isset($data['id_eval_admin'])) {
            $this->id_eval_admin = (int)$data['id_eval_admin'];
        }
        if (isset($data['periodo'])) {
            $this->periodo = trim($data['periodo']);
        }
        if (isset($data['fecha'])) {
            $this->fecha = trim($data['fecha']);
        }
        if ($conexion !== null) {
            $this->conexion = $conexion;
        }
    }

    // ============================================================
    // 1) Guardar registro principal
    // ============================================================
    public function sql_guardar_excepcional(): string {
        return sprintf("
            INSERT INTO desempeno_excepcional (id_eval_admin, periodo, fecha)
            VALUES (%d, '%s', '%s')
            RETURNING id_desemp_excepcional;
        ",
            $this->id_eval_admin,
            pg_escape_string($this->periodo),
            pg_escape_string($this->fecha)
        );
    }

    // ============================================================
    // 2) Guardar motivo asociado a un indicador
    // ============================================================
    public function sql_guardar_motivo(int $idDesempExcepcional, int $indicadorId, string $motivo): string {
        return sprintf("
            INSERT INTO motivos (id_desemp_excepcional, indicador_id, motivo)
            VALUES (%d, %d, '%s')
            RETURNING motivo_id;
        ",
            $idDesempExcepcional,
            $indicadorId,
            pg_escape_string($motivo)
        );
    }

    // ============================================================
    // 3) Relacionar indicador con desempeño excepcional
    //    (tabla muchos-a-muchos SIN id_relacion)
    // ============================================================
    public function sql_guardar_relacion(int $idDesempExcepcional, int $indicadorId): string {
        return sprintf("
            INSERT INTO tiene_indicador (id_desemp_excepcional, indicador_id)
            VALUES (%d, %d);
        ",
            $idDesempExcepcional,
            $indicadorId
        );
    }

    // ============================================================
    // 4) Listar motivos + indicador (para PDF y reportes)
    // ============================================================
    public static function sql_listar_motivos(int $idDesempExcepcional): string {
        return sprintf("
            SELECT 
                m.motivo_id,
                m.motivo,
                i.indicador
            FROM motivos m
            JOIN indicadores i ON i.indicador_id = m.indicador_id
            WHERE m.id_desemp_excepcional = %d
            ORDER BY m.motivo_id ASC;
        ",
            $idDesempExcepcional
        );
    }

    // ============================================================
    // 5) Listar indicadores fijos activos
    // ============================================================
    public static function sql_listar_indicadores(): string {
        return "
            SELECT 
                indicador_id,
                indicador,
                tipo_indicador,
                estado_indicador
            FROM indicadores
            WHERE tipo_indicador = 'Fijo'
              AND estado_indicador = 'Activo'
            ORDER BY indicador_id ASC
            LIMIT 3;
        ";
    }

    // ============================================================
    // 6) Verificar si ya existe planilla excepcional
    // ============================================================
    public static function sql_existe_excepcional(int $idEvalAdmin): string {
        return sprintf("
            SELECT id_desemp_excepcional
            FROM desempeno_excepcional
            WHERE id_eval_admin = %d
            LIMIT 1;
        ",
            $idEvalAdmin
        );
    }

    // ============================================================
    // 7) Obtener datos básicos del desempeño excepcional
    // ============================================================
    public static function sql_datos_excepcional(int $idDesempExcepcional): string {
        return sprintf("
            SELECT 
                id_desemp_excepcional,
                id_eval_admin,
                periodo,
                fecha
            FROM desempeno_excepcional
            WHERE id_desemp_excepcional = %d;
        ",
            $idDesempExcepcional
        );
    }
}
?>