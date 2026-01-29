<?php
class UbicacionFisica {
    public static function sql_listar(): string {
        return "SELECT id_uf, nombre_uf FROM ubicacion_fisica ORDER BY nombre_uf;";
    }
}
?>