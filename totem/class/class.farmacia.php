<?php

class Farmacia{
    
    private $mysqlHandler;
    
    /** clase para la gestion de informacion sobre una farmacia */
    public function __construct($mysqlHandler) {
        $this->mysqlHandler = $mysqlHandler;
    }
    
    /** Esta funcion devuelve las farmacias de guardia de un dia pasado por parametro y de un municipio a su vez
     * 
     * @param type $codigoMunicipio -> Codigo del municipio pertenece a la id_municipios de la tabla municipios
     * @param type $fecha -> Fecha de la que se quiere obtener los datos ("Y-m-d")
     * @return type -> Array() asociativo con los datos de la farmacias
     */
    public function getFarmaciasGuardiaMunicipio( $codigoMunicipio, $fecha = NULL){      
        //Si no existe la fecha pongo por defecto la del servidor
        //Si existe la fecha aseguro el formato necesario para la base de datos
        if( $fecha )
        {
            $fecha = date( "Y-m-d", strtotime($fecha) );
        }else
        {
            $fecha = date( "Y-m-d" );
        }
        
        $query = "SELECT * FROM `guardias` as t1 , `farmacias` as t2 WHERE t1.fecha = '$fecha' AND t2.codigo_municipio = '$codigoMunicipio' AND t1.id_farmacia=t2.n_farmacia ORDER BY t1.tipo";
        //echo $query;
        
        $result = new mysqli_result();
        $result = $this->mysqlHandler->query($query);
                
        while ( $row = $result->fetch_assoc() ) $datos[]=$row; //Estructura similar con un while
        //for ( $datos; $row = $result->fetch_assoc(); $datos[] = $row );
        
        $result->free();
        
        return $datos;
    }
    
    /** Funcion que devuelve todas las farmacias de un municipio
     * 
     * @param type $codigoMunicipio -> Codigo del municipio pertenece a la id_municipios de la tabla municipios
     * @return type -> Array() asociativo con los datos de la farmacias
     */
    public function getTodasFarmaciasMunicipio( $codigoMunicipio ){
        
        $query = "SELECT * FROM `farmacias` WHERE codigo_municipio = '$codigoMunicipio'";
        
        $result = new mysqli_result();
        $result = $this->mysqlHandler->query($query);
                
        while ( $row = $result->fetch_assoc() ) $datos[]=$row; //Estructura similar con un while
        //for ( $datos; $row = $result->fetch_assoc(); $datos[] = $row );
        
        $result->free();
        
        return $datos;

    }
    
    
    public function getTodasFarmaciasMunicipioJson($codigoMunicipio)
    {         
        $query = "SELECT * FROM `farmacias` WHERE codigo_municipio = '$codigoMunicipio'";
        //echo $query;
        $result = new mysqli_result();
        $result = $this->mysqlHandler->query($query);
        
        $datos=array();
        
        while ($row = $result->fetch_assoc())
        { 
            $datos[$row['n_farmacia']]['n_farmacia'] = $row['n_farmacia'];
            $datos[$row['n_farmacia']]['map_latitud'] = $row['map_latitud'];
            $datos[$row['n_farmacia']]['map_longitud'] = $row['map_longitud'];
            $datos[$row['n_farmacia']]['titular'] = utf8_encode($row['titular']);
            $datos[$row['n_farmacia']]['direccion'] = utf8_encode($row['direccion']);

        }
     
        $result->free();
     
        return json_encode($datos);
    }
    
    public function getFarmaciasCercanasMunicipio($lat, $lon)
    {         
       $query = "SELECT * , ABS( `map_latitud` - ('$lat') ) AS latitud, ABS( `map_longitud` - ('$lon') ) AS longitud
                    FROM `farmacias`
                    ORDER BY latitud + longitud
                    LIMIT 7";
        
        //echo $query;
        $result = new mysqli_result();
        $result = $this->mysqlHandler->query($query);
        
        $datos=array();
        
        while ($row = $result->fetch_assoc()) $datos[]=$row;
     
        $result->free();
     
        return $datos;
    }
    
    public function getFarmaciasCercanasMunicipioJson($lat, $lon)
    {         
        $query = "SELECT * , ABS( `map_latitud` - ('$lat') ) AS latitud, ABS( `map_longitud` - ('$lon') ) AS longitud
                    FROM `farmacias`
                    ORDER BY latitud + longitud
                    LIMIT 7";
        
        //echo $query;
        $result = new mysqli_result();
        $result = $this->mysqlHandler->query($query);
        
        $datos=array();
        
        while ($row = $result->fetch_assoc())
        { 
            $datos[$row['n_farmacia']]['n_farmacia'] = $row['n_farmacia'];
            $datos[$row['n_farmacia']]['map_latitud'] = $row['map_latitud'];
            $datos[$row['n_farmacia']]['map_longitud'] = $row['map_longitud'];
            $datos[$row['n_farmacia']]['titular'] = utf8_encode($row['titular']);
            $datos[$row['n_farmacia']]['direccion'] = utf8_encode($row['direccion']);

        }
     
        $result->free();
     
        return json_encode($datos);
    }
    
    
    
    
    
    /** Esta funcion devuelve las farmacias de guardia de un dia pasado por parametro y de un municipio a su vez
    * 
    * @param type $codigoMunicipio -> Codigo del municipio pertenece a la id_municipios de la tabla municipios
    * @param type $fecha -> Fecha de la que se quiere obtener los datos ("Y-m-d")
    * @return type -> Json con los datos de la farmacias
    */
    public function getFarmaciasGuardiaMunicipioJson($codigoMunicipio, $fecha = NULL)
    {      
        //Si no existe la fecha pongo por defecto la del servidor
        //Si existe la fecha aseguro el formato necesario para la base de datos
        if( $fecha )
        {
            $fecha = date( "Y-m-d", strtotime($fecha) );
        }else
        {
            $fecha = date( "Y-m-d" );
        }
        
        $query = "SELECT * FROM `guardias` as t1 , `farmacias` as t2 WHERE t1.fecha = '$fecha' AND t2.codigo_municipio = '$codigoMunicipio' AND t1.id_farmacia=t2.n_farmacia ORDER BY t1.tipo";
        //echo $query;
        $result = new mysqli_result();
        $result = $this->mysqlHandler->query($query);
        
        $datos=array();
        
        while ($row = $result->fetch_assoc())
        { 
            $datos[$row['n_farmacia']]['n_farmacia'] = $row['n_farmacia'];
            $datos[$row['n_farmacia']]['map_latitud'] = $row['map_latitud'];
            $datos[$row['n_farmacia']]['map_longitud'] = $row['map_longitud'];
            $datos[$row['n_farmacia']]['titular'] = utf8_encode($row['titular']);
            $datos[$row['n_farmacia']]['direccion'] = utf8_encode($row['direccion']);

        }
     
        $result->free();
     
        return json_encode($datos);
    }
    
}