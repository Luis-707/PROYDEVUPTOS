<?php

class Usuario {
    // Propiedades privadas en minúsculas
        private $conexion;

        private $usuario = "";
        private $clave = "";
        private $cedula_usu = "";
        private $nombres_usu = "";
        private $apellidos_usu = "";
        private $correo_usu = "";
        private $foto_usu = "";
        private $estatus_usu = "";
        private $otro_datos = "";

        /*[login] => josea
            [passw] => b4b14cabace7c83adf7106f283b194cb
            [cedula] => 1000111
            [nombres] => jose
            [apellidos] => perez
            [correo] => jose@gmail.com
            [foto] => 
            [estatus] => ACTIVO
            [otros_datos] =>  */
    
        public function __construct($dataCliente = array(''),$conexion = NULL) {

            if (isset($dataCliente['login'])) {
                //asdie(print_r($dataCliente));

                $this->usuario = $dataCliente['login'];
                $this->clave = $dataCliente['passw'];
                $this->cedula_usu = $dataCliente['cedula'];
                $this->nombres_usu = $dataCliente['nombres'];
                $this->apellidos_usu = $dataCliente['apellidos'];
                $this->correo_usu = $dataCliente['correo'];
                $this->foto_usu = $dataCliente['foto'];
                $this->estatus_usu = $dataCliente['estatus'];
                $this->otro_datos = $dataCliente['otros_datos'];
            }else{
                //echo 'No Existe';
            }

            if($conexion != NULL){
                $this->conexion = $conexion;
            }

           // die(print_r($dataCliente));
        }
    

    // Destructor
    public function __destruct() {
        // No es necesario implementación específica
    }

    // Método para guardar (INSERT)
    public function sql_guardar(): string {
         return sprintf("Insert Into usuario (usuario,clave,cedula_usu,nombres_usu,apellidos_usu,correo_usu,foto_usu,estatus_usu) values('%s','%s','%s','%s','%s','%s','%s','%s');",
                $this->usuario,$this->clave,$this->cedula_usu,$this->nombres_usu,$this->apellidos_usu,$this->correo_usu,$this->foto_usu,$this->estatus_usu);
    }

    // Método para eliminar (DELETE)
    public function sql_Eliminar(): string {
        return sprintf("delete from usuario where usuario = '%s'; ",$this->usuario);
    }

    // Método para actualizar (UPDATE)
    public function sql_actualizar(): string {

       return sprintf("UPDATE usuario set clave='%s',cedula_usu='%s',nombres_usu='%s',apellidos_usu='%s',correo_usu='%s',foto_usu='%s',estatus_usu='%s' where usuario='%s';",
                    $this->clave,$this->cedula_usu,$this->nombres_usu,$this->apellidos_usu,$this->correo_usu,$this->foto_usu,$this->estatus_usu,$this->usuario);
 
    }
    

    // Método para listar todos (SELECT)
    public static function Sql_listar() {
        return sprintf("SELECT * from usuario");
    }

    // Método para listar todos (SELECT)
    public function listar() {
        //if($this->conexion != NULL){
            //echo $this->Sql_listar(); 
            return $this->conexion->ejecutarConsultaBdds($this->Sql_listar());
        //}
        return "No se ha definido la conexion ";
    }

    // Método para buscar por clave primaria
    public function sql_buscar(): string {
        return sprintf("SELECT * from usuario where usuario = '%s'; ",$this->usuario);
    }

    // Métodos getter para acceder a los atributos privados (opcional)
    public function getUsuario(): string {
        return $this->usuario;
    }

}
?>