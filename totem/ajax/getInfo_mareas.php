<?php

// Comprueba si hay internet
/*if (!testInternet()) {
    header($_SERVER['SERVER_PROTOCOL'] . ' 500 Internal Server Error', true, 500);
    exit();
}*/

$id_centro = $_SESSION['id_centro'];
$imagen = "mareas.png";
$url_imagen_tipo = '../../../contenido_proyectos/vistaflor/_general/iconos/iconos_svg/';

// Obtengo las mareas de la bd bd_feeltourist_comun.mareas_totem
$info_mareas = get_mareas($id_centro);

//Preparo el json para transformarlo a array
$info_mareas_json = str_replace("'",'"', $info_mareas['0']['json']);

//Obtengo la info en array
$array_info_mareas = json_decode($info_mareas_json,true);

//exit();

$tpl_info_mareas = new TemplatePower("plantillas/seccion_mareas.html", T_BYFILE);
$tpl_info_mareas->prepare();

$tpl_info_mareas->assign("mareas_title", LANG_INFO_MAREAS_TITLE);
$tpl_info_mareas->assign("lang_disclaimer", LANG_GLOBAL_DISCLAIMER);
$tpl_info_mareas->assign("lang_atras", LANG_GLOBAL_ATRAS);

$tpl_info_mareas->assign("dia_title", LANG_INFO_MAREAS_DIA);
$tpl_info_mareas->assign("hora_title", LANG_INFO_MAREAS_HORA);
$tpl_info_mareas->assign("altura_title", LANG_INFO_MAREAS_ALTURA);
$tpl_info_mareas->assign("tipo_title", LANG_INFO_MAREAS_TIPO);


$datos['extra'] = $array_info_mareas['extremes'];

//print_r($array_info_mareas['extremes']);

//Extremes (las altas y bajas)

foreach ($array_info_mareas['extremes'] as $marea){

    $tpl_info_mareas->newBlock("marea");

    //$dia =utf8_encode( getFechaFormato($marea['dt']) );
    $dia = getFechaFormato($marea['dt']);
    $tpl_info_mareas->assign("dia", $dia );


    $tpl_info_mareas->assign("hora", date('H:i', $marea['dt']) );

    $tpl_info_mareas->assign("altura", round($marea['height'],3) );

    if ($marea['type'] == 'High'){
        $tpl_info_mareas->assign("tipo",  LANG_INFO_MAREAS_ALTA );
        $tpl_info_mareas->assign("tipo_icono", $url_imagen_tipo . 'marea_alta.svg');
    }else{
        $tpl_info_mareas->assign("tipo",  LANG_INFO_MAREAS_BAJA );
        $tpl_info_mareas->assign("tipo_icono",  $url_imagen_tipo . 'marea_baja.svg' );
    }

}


$datos['datos'] = $tpl_info_mareas->getOutputContent();
$datos['banner_superior'] = "../../../contenido_proyectos/vistaflor/centro_$id_centro/imagenes/cabecera/$imagen" ;

echo json_encode($datos);


?>