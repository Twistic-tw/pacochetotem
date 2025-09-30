<?php

class MySQL_v2 {
    
    private $conn;

    public function __construct() {
        session_start();
        $config = parse_ini_file( dirname(__FILE__) . "/" . $_SESSION['fichero_config'], true);

        $_SESSION['url_proyecto'] = $config['url_proyecto']['url_proyecto'];
        $_SESSION['url_gestor_multimedia'] = $config['url_gestor_multimedia']['url_gestor_multimedia'];
        $_SESSION['id_cadena'] = $config['cadena']['id_cadena'];

        $db_host = $config['database']['host'];
        $db_name = $config['database']['db'];
        $db_user = $config['database']['user'];
        $db_pass = $config['database']['pwd'];

        $this->conn = new mysqli($db_host, $db_user, $db_pass, $db_name);
        // $this->conn = new mysqli('db', 'root', '', 'bd_twistic_demo');
        $this->conn->set_charset('utf8_bin');

        if ($this->conn->connect_error) {
            die("Error en la conexión: " . $this->conn->connect_error);
        }
    }

    public function __destruct() {
        $this->conn->close();
    }

    public function executeQuery($sql, $params = []) {

        $stmt = $this->conn->prepare($sql);
        
        if (!$stmt) die("Error en la consulta: " . $this->conn->error);
        
        if (!empty($params)) {

            $types = '';

            foreach ($params as $param) {
                switch (gettype($param)) {
                    case 'integer': $types .= 'i';break;
                    case 'double': $types .= 'd';break;
                    case 'string': $types .= 's';break;
                    case 'string': $types .= 's';break;
                    default: $types .= 's';break;
                }
            }

            $stmt->bind_param($types, ...$params);
            
        }
        
        if (!$stmt->execute()) die("Error en la ejecución de la consulta: " . $stmt->error);
        
        if ($stmt->affected_rows == -1) { // Esto es para los select
            return $stmt->get_result();
        }

        return $stmt;

    }
}

class MySQL {

    public $conexion;
    private $total_consultas;

    public function __construct() {
        global $connectedID;
        
       // $config = parse_ini_file( dirname(__FILE__) . "/config.ini", true);        

        session_start();
        $config = parse_ini_file( dirname(__FILE__) . "/".$_SESSION['fichero_config'], true);

        //print_r($config);die;

        $_SESSION['url_proyecto'] = $config['url_proyecto']['url_proyecto'];
        $_SESSION['url_gestor_multimedia'] = $config['url_gestor_multimedia']['url_gestor_multimedia'];
        $_SESSION['id_cadena'] = $config['cadena']['id_cadena'];


        $db_host = $config['database']['host'];
        $db_name = $config['database']['db'];
        $db_user = $config['database']['user'];
        $db_pass = $config['database']['pwd'];			        	
                
        if (!$connectedID) {
            if (!isset($this->conexion)) {	
                $this->conexion = (mysql_connect($db_host, $db_user, $db_pass)) or die(mysql_error());
                mysql_select_db($db_name, $this->conexion) or die(mysql_error());				
                //esto hace que todo lo que salga de la base de datos venga como UTF-8 hasta que se demuestre lo contrario
                mysql_query("SET CHARACTER SET 'utf8'", $this->conexion);
				mysql_set_charset('UTF8'); //clave para el chino
            }
        } else {
            $this->conexion = $connectedID;
        }
    }

    public function getConexionID(){
        return $this->conexion;
    }
    
    public function consulta( $consulta ) {
        $this->total_consultas++;
        $resultado = mysql_query($consulta, $this->conexion);
        if (!$resultado) {
            echo 'MySQL Error: ' . mysql_error() . '<br>' . $consulta;
            exit;
        }
        
        return $resultado;
        
    }

    public function fetch_array($consulta) {
        return mysql_fetch_array($consulta);
    }

    public function query ($consulta)
    {
        return mysql_query($consulta);
    }

    public function fetch_assoc($consulta){
        return mysql_fetch_assoc($consulta);
    }

    public function mysql_fetch_row($consulta){
        return mysql_fetch_row($consulta);
    }
    
    public function num_rows($consulta) {
        return mysql_num_rows($consulta);
    }

    public function getTotalConsultas() {
        return $this->total_consultas;
    }
    
    public function getAllArray($consulta){
        while ($row = mysql_fetch_assoc($consulta)) {
            $datos[] = $row;
        }
        return $datos;
    }

    
    public static function getLastInsertedId(){
        global $connectedID;
        
        $t = mysql_fetch_array( mysql_query("SELECT LAST_INSERT_ID()") );
        return $t[0];
    }
}

?>
