<?php

class Cliente{
    
    /** handler para la conexion a la BD.
     * @var mysqli     */
    private $mysqlHandler;
    private $idCliente;
    private $datosCliente;
    
    /** instanciamos la clase cliente que se encarga de la comunicacion con el servidor para 
     * manejo de datos sobre los clientes.
     * 
     * @param mysqli $mysqlHandler puntero al objeto que va a utilizar esta clase para la interacion con 
     * la base de datos.
     * @param int $idCliente IdentificadorCliente
     */
    public function __construct($mysqlHandler, $idCliente ) {
        $this->mysqlHandler = $mysqlHandler;
        $this->idCliente = $this->mysqlHandler->real_escape_string($idCliente);
        $this->datosCliente = false;
    }
    
    
    /** funcion privada para initializar los datos del cliente ya previamente inicalizado.
     * Esta funcion se llama desde los distintos metodos para los casos en los cuales
     * aun no se hubiese cargado los datos del cliente
     */
    private function initializeDatosCliente(){
        
        if ( $this->datosCliente ){
            return;
        }
        
        $query = "SELECT * FROM cliente
                    WHERE id = '".$this->idCliente."'";
        
        $result = new mysqli_result();
        $result = $this->mysqlHandler->query($query);
        
        
        $this->datosCliente = $result->fetch_assoc();
        $result->free();
    }
    
    /** funcion para obtener los datos del cliente, que ya fue identificado en la instanciacion dle objeto
     */
    public function getDatosCliente(){
        $this->initializeDatosCliente();
        return $this->datosCliente;
    }
    /** funcion para obtener los datos del cliente en formato JSON, que ya fue identificado en la instanciacion del objeto 
     */
    public function getDatosClienteJson(){
        $this->initializeDatosCliente();
        
        $datos = ($this->datosCliente);
        $datos['nombre'] = utf8_encode($datos['nombre']);
        return json_encode($datos);
    }
    
    /** funcion para obtener el id del cliente. */
    public function getIdCliente(){
        $this->initializeDatosCliente();
        return $this->datosCliente['id'];
    }
    
    
    /** funcion para obtener los datos del codigo del municipio en el que esta el cliente. */
    public function getCodigoMunicipioCliente(){
        $this->initializeDatosCliente();
        return $this->datosCliente['cod_municipio'];
    }
    
    /** funcion para obtener el nombre del cliente. */
    public function getNombreCliente(){
        $this->initializeDatosCliente();
        return $this->datosCliente['nombre'];
    }
    
    /** funcion para obtener el codigo postal del cliente. */
    public function getCodigoPostalCliente(){
        $this->initializeDatosCliente();
        return $this->datosCliente['codigo_postal'];
    }
    
    /** funcion para obtener los datos la latitud del cliente. */
    public function getMapLatitudCliente(){
        $this->initializeDatosCliente();
        return $this->datosCliente['map_latitud'];
    }
    
    /** funcion para obtener los datos la longitud del cliente. */
    public function getMapLongitudCliente(){
        $this->initializeDatosCliente();
        return $this->datosCliente['map_longitud'];
    }
    
    /** funcion para obtener los datos de si el cliente esta activo o no. */
    public function getActivoCliente(){
        $this->initializeDatosCliente();
        return $this->datosCliente['activo'];
    }
    
    /** 
    * funcion para añadir un nuevo cliente. Es estatica porque no depende de que
    * este instanaciada la clase para su correcto funcionamiento. Es necesario
    * pasarle un handler del objeto para la comunicacion con la BD.
    * 
    * @param mysqli $mysqlHandler 
    * @param array $datosDelCliente
    */
    public static function addNewCliente($mysqlHandler, $datosDelCliente){
        
        $query = "INSERT INTO clientes (id,nombre,codigo_postal,cod_municipio,map_latitud,map_longitud,activo) 
            VALUES (
            NULL,
            '".$mysqlHandler->real_escape_string( $datosDelCliente['nombre'])."',
            '".$mysqlHandler->real_escape_string( $datosDelCliente['codigo_postal'])."',
            '".$mysqlHandler->real_escape_string( $datosDelCliente['cod_municipio'])."',
            '".$mysqlHandler->real_escape_string( $datosDelCliente['map_latitud'])."',
            '".$mysqlHandler->real_escape_string( $datosDelCliente['map_longitud'])."',
            '".$mysqlHandler->real_escape_string( $datosDelCliente['activo'])."',                
            )";
        
        $mysqlHandler->query($query);
    }
}
?>
