<?php

if (!testInternet()) {

    header($_SERVER['SERVER_PROTOCOL'] . ' 500 Internal Server Error', true, 500);
    exit();
}


$tplVuelos = new TemplatePower("plantillas/seccion_aeropuertos_new.html", T_BYFILE);
$tplVuelos->prepare();


registrarLog("informacion", "vuelos");

$array_idiomas_ae = [
    1 => [
        'nombre' => 'aeropuerto',
        'salidas' => 'salidas',
        'llegadas' => 'llegadas',
        'url' => 'https://es.airports-worldwide.info/'
    ],
    2 => [
        'nombre' => 'airport',
        'salidas' => 'departures',
        'llegadas' => 'arrivals',
        'url' => 'https://www.airports-worldwide.info/'
    ],
    3 => [
        'nombre' => 'flughafen',
        'salidas' => 'abfluge',
        'llegadas' => 'ankunfte',
        'url' => 'https://de.airports-worldwide.info/'
    ],
    4 => [
        'nombre' => 'aeroport',
        'salidas' => 'departs',
        'llegadas' => 'arrivees',
        'url' => 'https://fr.airports-worldwide.info/'
    ]
];

$url = $_GET['url_aeropuerto'];

$cod_ae = $_GET['aeropuerto_doble'];

if(($datos_aeropuertos = $array_idiomas_ae[$_SESSION['idioma']])){

    $url_salidas = $datos_aeropuertos['url'] . $datos_aeropuertos['nombre'] . '/LPA/' . $datos_aeropuertos['salidas'];
    $url_llegadas = $datos_aeropuertos['url'] . $datos_aeropuertos['nombre'] . '/LPA/' . $datos_aeropuertos['llegadas'];

    $tplVuelos->assign('url_salidas',$url_salidas);
    $tplVuelos->assign('url_llegadas',$url_llegadas);

}

$datos = get_nombre_aeropuerto($cod_ae);

if(($datos['cod'] == "LPA" && $_SESSION['id_lugar'] != 47) || $datos['nombre'] == ""){
    $nombre_aero = LANG_INFO_VUELOS_TITLE;
}else{
    $nombre_aero = $datos['nombre'];
}

$tplVuelos->assign("title", $nombre_aero);
$tplVuelos->assign("llegadas", LANG_VUELOS_LLEGADASTEXT);
$tplVuelos->assign("salidas", LANG_VUELOS_SALIDASTEXT);

$tplVuelos->assign("lang_atras", LANG_GLOBAL_ATRAS);
$tplVuelos->assign("double_size", "double_size");

$id_centro = $_SESSION['id_centro'];

$imagen = "aeropuerto.jpg";
$resultado['banner_superior'] = "../../../contenido_proyectos/pacoche/centro_$id_centro/imagenes/cabecera/$imagen" ;


$resultado['datos'] = $tplVuelos->getOutputContent();

echo json_encode($resultado);

?>
