<?php

// Aqui hay que pedir de session la cadena del hotel
$id_cadena = $_SESSION["id_cadena"];

$url_base = $_SESSION["url_comun"];

$url_base_hoteles = $url_base . 'destinos/hoteles/'.$id_cadena.'/';

$url_base_lugares = $url_base . 'destinos/lugares/';

$idioma = $_SESSION['idioma'];

registrarLog("destinos", "zonas", "");

$tpl_destinos = new TemplatePower("plantillas/seccion_zonas_listado.html", T_BYFILE);
$tpl_destinos->prepare();
$tpl_destinos->assign("imagen-fondo", "gran-canaria.png");
$tpl_destinos->assign("selector", LANG_CONOCE_SELECCIONAR_ZONAS);
$tpl_destinos->assign("selector_mapa", LANG_DESTINOS_SELECTOR);
$tpl_destinos->assign("cerrar", LANG_CONOCE_ATRAS);

$tpl_destinos->assign("info_label", LANG_DESTINOS_TITLE_TODOS);


$tpl_destinos->assign("ver_zona", LANG_VERZONA);
$tpl_destinos->assign("ver_nombre", LANG_VERNOMBRE);
$tpl_destinos->assign("id_cadena", $id_cadena);

$tpl_destinos->assign("idioma", $idioma );

 $tpl_destinos->assignGlobal("url_base_hoteles", $url_base_hoteles );


//Pido los destinos que esta en la base de datos COMUN
$destinos = get_destinos($id_cadena,$idioma);


//////////////////////////////////////////////////////////////////
//Lista de las zonas en el mapa

foreach ($destinos as $destino) {

    $tpl_destinos->newBlock("zona");

    //$tpl_destinos->assign("url_base_hoteles", $url_base_hoteles );

    $tpl_destinos->assign( "nombre_zona", $destino['nombre']);
    $tpl_destinos->assign( "href", $destino['id']);
    $tpl_destinos->assign( "imagen", $destino['imagen']);

    $destinos_coordenadas = get_destinos_coordenadas($destino['id']);

    foreach ($destinos_coordenadas as $coordenadas) {
        $tpl_destinos->newBlock("zona_mapa");
        $tpl_destinos->assign( "coordenadas", $coordenadas['coordenadas']);
        $tpl_destinos->assign( "href", $coordenadas['id_destino']);
    }
}

//////////////////////////////////////////////////////////////////////


$datos['get'] = $_GET;
$datos['datos'] = $tpl_destinos->getOutputContent();

echo json_encode($datos);
