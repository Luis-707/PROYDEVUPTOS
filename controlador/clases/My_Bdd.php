<?php
ini_set('display_errors',0);

include_once "Log.php";

/**
 * La clase BDD proporciona una interfaz para conectar a una base de datos MySQL,
 * ejecutar consultas SQL y realizar operaciones de cierre de conexión.
 */
class BDD {
    // Propiedades privadas para almacenar la información de la conexión y la conexión misma.
    private $conexion;
    private $host;
    private $usuario;
    private $contrasena;
    private $basedatos;
    private $puerto;

    /**
     * Constructor de la clase que inicializa los valores necesarios para la conexión y
     * establece la conexión a la base de datos al crear una instancia del objeto.
     *
     * @param string $host       El host de la base de datos.
     * @param string $usuario    El nombre de usuario de la base de datos.
     * @param string $contrasena La contraseña de la base de datos.
     * @param string $basedatos  El nombre de la base de datos.
     * @param int    $puerto     El puerto de la base de datos (opcional, el valor predeterminado es 3306).
     */
    public function __construct($host, $usuario, $contrasena, $basedatos, $puerto = 3306) {
        $this->host = $host;
        $this->usuario = $usuario;
        $this->contrasena = $contrasena;
        $this->basedatos = $basedatos;
        $this->puerto = $puerto;
        //$this->log = new Log();

        $this->conectar(); // Llama al método conectar para establecer la conexión.
    }

    /**
     * Método privado que establece la conexión a la base de datos MySQL.
     * La información de conexión se toma de las propiedades de la instancia.
     */
    private function conectar() {
        $this->conexion = mysqli_connect($this->host, $this->usuario, $this->contrasena, $this->basedatos, $this->puerto);

        if (!$this->conexion) {
             //die("Error de conexión: " . mysqli_connect_error());
             //echo "host={$this->host} dbname={$this->basedatos} user={$this->usuario} password={$this->contrasena} port={$this->puerto}";
             Log::errorPhp();
             $this->conexion = false;
             return false;
        } 
    }

    /**
     * Ejecuta una consulta SQL en la base de datos.
     *
     * @param string $consulta La consulta SQL a ejecutar.
     * @return resource|bool El resultado de la consulta SQL o false en caso de error.
     */
    public function ejecutarSQL($consulta) {

        if(!$this->conexion){ 
            $resultado = $this->conexion;
        }else{
            $resultado = mysqli_query($this->conexion, $consulta);
            if (!$resultado) {
                //Log::errorPhp();
                $msn = "Base de datos: '{$this->basedatos}' ERROR en SQL:\n {$consulta}";
                Log::errorControlado($msn);
            }
        }
            
        return $resultado;
    }

    /**
     * Ejecuta una consulta SQL y devuelve los datos en un arreglo asociativo.
     *
     * @param string $consulta La consulta SQL a ejecutar.
     * @return array|bool Los datos de la consulta en un arreglo asociativo o false en caso de error.
     */
    public function obtenerRespSQL($consulta) {
        $resultado = $this->ejecutarSQL($consulta);

        if(!$this->conexion){
            $datos = $this->conexion;
        }else{
            $datos = mysqli_fetch_all($resultado, MYSQLI_ASSOC);
        }

        return $datos;
    }

    /**
     * Cierra la conexión a la base de datos si está abierta.
     */
    public function cerrarConexion() {
        if ($this->conexion) {
            mysqli_close($this->conexion);
        }
    }

    /** a esta clase le faltan metodos, como iniciar transaccion, un rolback por si falla la transaccion, 
     * un commit para marcar que ha ido bien hasta cierto punto la transaccion, un finalizar transaccion,
     * retornar nuemro de registro afectado de un query, entre otros */
}
?>