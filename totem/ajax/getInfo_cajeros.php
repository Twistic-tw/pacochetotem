<?php


// error_reporting(E_ALL);
// ini_set('display_errors', 1);

// Comprueba si hay internet
if (!testInternet()) {
    header($_SERVER['SERVER_PROTOCOL'] . ' 500 Internal Server Error', true, 500);
    exit();
}

$id_centro = $_SESSION['id_centro'];
$id_lugar =  $_SESSION['id_lugar'];

// Obtengo las coordenadas del hotel (funcion en lib_totem)
$coordenadas_hotel = coordenadas_hotel($id_centro);



$tpl_info_cajero = new TemplatePower("plantillas/seccion_cajeros.html", T_BYFILE);
$tpl_info_cajero->prepare();

$tpl_info_cajero->assign(hotel_latitud, $coordenadas_hotel['0']['latitud']);
$tpl_info_cajero->assign(hotel_longitud, $coordenadas_hotel['0']['longitud']);

$tpl_info_cajero->assign("cajeros_title", LANG_INFO_CAJEROS_TITLE);
$tpl_info_cajero->assign("lang_disclaimer", LANG_GLOBAL_DISCLAIMER);

//Leyenda de cajero en el hotel
switch ($id_centro) {
    case 248:
        $tpl_info_cajero-> assign('leyenda_cajero',LANG_INFO_CAJERO_SANLUCAS_LEYENDA);
        break;
    case 225:
        $tpl_info_cajero-> assign('leyenda_cajero',LANG_INFO_CAJERO_AMERICAS_LEYENDA);
        break;
    case 267:
        $tpl_info_cajero-> assign('leyenda_cajero',LANG_INFO_CAJERO_ROMANTICA_LEYENDA);
        break;
    case 247:
        $tpl_info_cajero-> assign('leyenda_cajero',LANG_INFO_CAJERO_GUANACASTE_LEYENDA);
        break;
    default:
        $tpl_info_cajero-> assign('a_comentario','<!--');
        $tpl_info_cajero-> assign('c_comentario','-->');
        break;
}

//registrarLog("informacion", "cajeros");

$tpl_info_cajero->assign("lang_farmacias_text", LANG_INFO_GASOLINERAS_TITLE);
$tpl_info_cajero->assignGlobal("lang_atras", LANG_GLOBAL_ATRAS);
$tpl_info_cajero->assignGlobal("qr_mensaje", LANG_GLOBAL_QRMSG);

//    $resultado['banner_superior'] = "../../../contenido_proyectos/vistaflor/centro_$id_centro/imagenes/cabecera/$imagen" ;


    $resultado['datos'] = $tpl_info_cajero->getOutputContent();

    echo json_encode($resultado);


?>