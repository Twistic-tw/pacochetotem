<?php

$accion = $_GET['accion'];


    
$config = parse_ini_file( "./../../../config/config.ini", true);           
        
$db_host = $config['database']['host'];
$db_name = $config['database']['db_farmacias'];
$db_user = $config['database']['user'];
$db_pass = $config['database']['pwd'];
        
        
        
$_mysqlHandler = new mysqli($db_host, $db_user, $db_pass, $db_name);

if ($accion == "listar_farmacias") {
    $opcion = $_GET['opcion'];

    if ($_mysqlHandler->connect_error) {
        //ocurrio un error al conectar a la BD.
    }

    $farmaciaHandler = new Farmacia($_mysqlHandler);
    $clienteHandler = new Cliente($_mysqlHandler, $_SESSION['id_centro']);
    //a Id de cliente pasará a ser la id de centro, debe ser la misma ID en la base de datos de farmacias
    //como la id de cliente en la base de datos itourism
    if ($opcion == "guardias") {
        $farmaciasJson = $farmaciaHandler->getFarmaciasGuardiaMunicipioJson($clienteHandler->getCodigoMunicipioCliente());
    }
    if ($opcion == "farmacias") {
        $farmaciasJson = $farmaciaHandler->getFarmaciasCercanasMunicipioJson($clienteHandler->getMapLatitudCliente(), $clienteHandler->getMapLongitudCliente());
    }
    echo $farmaciasJson;
}

if ($accion == "dame_cliente") {
    if ($_mysqlHandler->connect_error) {
        //ocurrio un error al conectar a la BD.
    }

    $clienteHandler = new Cliente($_mysqlHandler, $_SESSION['id_centro']);
    $datos_cliente = $clienteHandler->getDatosClienteJson();
    echo $datos_cliente;
}

if ($accion == "dame_datos_google") {
    $opcion = $_GET['opcion'];

    //$farmacias = json_decode($_GET['farmacias'], true); //la variables llega en formato JSON, lo decodificamos
    //echo($farmacias);
    if ($_mysqlHandler->connect_error) {
        //ocurrio un error al conectar a la BD.
    }

    $farmaciaHandler = new Farmacia($_mysqlHandler);
    $clienteHandler = new Cliente($_mysqlHandler, $_SESSION['id_centro']);
    
    $fecha = date("Y-m-d");

    if ($opcion == "guardias") {
        $farmacias = $farmaciaHandler->getFarmaciasGuardiaMunicipio($clienteHandler->getCodigoMunicipioCliente());
    }
    if ($opcion == "farmacias") {
        $farmacias = $farmaciaHandler->getFarmaciasCercanasMunicipio($clienteHandler->getMapLatitudCliente(), $clienteHandler->getMapLongitudCliente());
    }

    if (file_exists("farmacias_txt/". $_SESSION['id_centro'] ."-" . $fecha . "-" . $opcion . ".txt")) {
        $resultado = json_decode(file_get_contents("farmacias_txt/". $_SESSION['id_centro'] ."-" . $fecha . "-" . $opcion . ".txt"),true);
    }else{
        foreach ($farmacias as $farmacia) {
            //echo "n_farmacia: key->".$n_farmacia." value->".$farmacia['map_latitud'];
            //Obtencion de Datos conduciendo de Google
            $url = "http://maps.googleapis.com/maps/api/distancematrix/json?origins=" . $clienteHandler->getMapLatitudCliente() . "," . $clienteHandler->getMapLongitudCliente() . "&destinations=" . $farmacia['map_latitud'] . "," . $farmacia['map_longitud'] . "&language=es-ES&sensor=false&mode=driving";
            $datos = file_get_contents($url);
            $datos_driving = json_decode($datos, true);
            //Obtencion de Datos caminando de Google
            $url = "http://maps.googleapis.com/maps/api/distancematrix/json?origins=" . $clienteHandler->getMapLatitudCliente() . "," . $clienteHandler->getMapLongitudCliente() . "&destinations=" . $farmacia['map_latitud'] . "," . $farmacia['map_longitud'] . "&language=es-ES&sensor=false&mode=walking";
            $datos = file_get_contents($url);
            $datos_walking = json_decode($datos, true);

            $resultado[$farmacia['n_farmacia']]['pie'] = utf8_encode($datos_walking['rows'][0]['elements'][0]['distance']['text']) . "<br>" . utf8_encode($datos_walking['rows'][0]['elements'][0]['duration']['text']);
            $resultado[$farmacia['n_farmacia']]['coche'] = utf8_encode($datos_driving['rows'][0]['elements'][0]['distance']['text']) . "<br>" . utf8_encode($datos_driving['rows'][0]['elements'][0]['duration']['text']);
        }
        file_put_contents("farmacias_txt/". $_SESSION['id_centro'] ."-" . $fecha . "-" . $opcion . ".txt", json_encode($resultado));
    }
   
    echo json_encode($resultado);
}