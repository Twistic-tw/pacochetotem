<?php


if (!testInternet()) {
    header($_SERVER['SERVER_PROTOCOL'] . ' 500 Internal Server Error', true, 500);
    exit();
}


$db = new MySQL();

$tplVuelos = new TemplatePower('plantillas/seccion_vuelos.html', T_BYFILE);
$tplVuelos->prepare();

$tplVuelos->assign("vuelos_title", LANG_INFO_VUELOS_TITLE);
$tplVuelos->assign("lang_llegadasText", LANG_VUELOS_LLEGADASTEXT);
$tplVuelos->assign("lang_salidasText", LANG_VUELOS_SALIDASTEXT);
$tplVuelos->assign("lang_hora", LANG_VUELOS_HORA);
$tplVuelos->assign("lang_vuelo", LANG_VUELOS_VUELO);
$tplVuelos->assign("lang_destino", LANG_VUELOS_DESTINO);
$tplVuelos->assign("lang_origen", LANG_VUELOS_ORIGEN);
$tplVuelos->assign("lang_compañía", LANG_VUELOS_COMPAÑIA);
$tplVuelos->assign("lang_estado", LANG_VUELOS_ESTADO);
$tplVuelos->assign("lang_atras", LANG_GLOBAL_ATRAS);

$tplVuelos->assign("lang_disclaimer", LANG_GLOBAL_DISCLAIMER);

//Obtenemos la fecha y hora actual
$date = date('Y-m-d H:i:s');

//Esto se debe cambiar por el cliente actual en la sesion para obtener su aeropuerto
$id_cliente = 2;

//Calculamos la hora actual, consultamos la tabla conexiones y realizamos las gestiones correspondientes y realizamos las peticiones de vuelos a la appi

$hora = substr($date, 11, -6);
$year = substr($date, 0, -15);
$mes = substr($date, 5, -12);
$dia = substr($date, 8, -9);


//obtenemos el aeropuerto a partir del cliente
$queryA = "SELECT vuelos_aeropuertos.cod, vuelos_aeropuertos.id
		   FROM vuelos_aeropuertos, cliente_aeropuerto
		   WHERE vuelos_aeropuertos.id=cliente_aeropuerto.id_aeropuerto
		   AND cliente_aeropuerto.id_cliente='$id_cliente'";
$rec = $db->consulta($queryA);
$result = $db->fetch_array($rec);

$aeropuerto_cod = $result['cod'];
$aeropuerto_id = $result['id'];


//obtenemos la ultima conexion registrada para el id del aeropuerto del cliente
$queryL = "SELECT * FROM vuelos_conexiones WHERE id_aero='$aeropuerto_id' ORDER BY id DESC LIMIT 1";
$rec = $db->consulta($queryL);
$result_con = $db->fetch_array($rec);
$id_conexion = $result_con['id'];

//obtenemos los datos que se han de mostrar.
$query = "SELECT *  FROM cliente_aeropuerto, vuelos_estados, vuelos_aeropuertos, vuelos_conexiones
            WHERE cliente_aeropuerto.id_cliente='$id_cliente'
            AND cliente_aeropuerto.id_aeropuerto=vuelos_aeropuertos.id
            AND vuelos_conexiones.id = '$id_conexion'
            AND vuelos_conexiones.id_aero = vuelos_aeropuertos.id
            AND vuelos_conexiones.id = vuelos_estados.id_con
            ORDER BY hora";

$rec = $db->consulta($query);


if ( !$rec ) {
    //print_r();
    
    echo $query;
    
} 
else {
    $vuelos = array();
    
    while ($dato = $db->fetch_assoc($rec)) {
        
//        print_r($dato);
        
        $vuelos[$dato['tipo']][$dato['vuelo']] = $dato;
        
        if ($dato['tipo'] == "salida") {

            $tplVuelos->newBlock("salidas");
            $tplVuelos->assign('hora', substr($dato['hora'], 0, -3));
            $tplVuelos->assign('vuelo', $dato['vuelo']);
            $tplVuelos->assign('trayecto', $dato["origen_destino"]);
            $tplVuelos->assign('company', $dato["company"]);
            $tplVuelos->assign('estado', $dato["estado"]);
        } 
        elseif ($dato['tipo'] == "llegada") {

            $tplVuelos->newBlock("llegadas");
            $tplVuelos->assign('hora', substr($dato['hora'], 0, -3));
            $tplVuelos->assign('vuelo', $dato["vuelo"]);
            $tplVuelos->assign('trayecto', $dato["origen_destino"]);
            $tplVuelos->assign('company', $dato["company"]);
            $tplVuelos->assign('estado', $dato["estado"]);
        } 
        else {
            echo "Error no es ni salida ni llegada";
        }
    }
    
    $vuelos_json['datos'] = $tplVuelos->getOutputContent();
    
    echo json_encode( $vuelos_json );
    
}

?>
