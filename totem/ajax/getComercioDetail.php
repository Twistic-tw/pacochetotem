<?php


$tpl_compras_contenido_dinamico = new TemplatePower("plantillas/seccion_actividad_comercio_detalle.html", T_BYFILE);
$tpl_compras_contenido_dinamico->prepare();

$tpl_compras_contenido_dinamico->assign("lang_atras", LANG_GLOBAL_ATRAS);

//Este es el id_comercio_centro
$comercioId = $_GET['comercioId'];

$idioma = $_SESSION['idioma'];
//$idioma = 1;
$centro = $_SESSION['id_centro'];

//Para el registro del click tengo q insertar el id_comercio
$id_comercio_general = get_comercio_id_general($comercioId, $centro);

//Inserto en alemania SOLO si hay internet para evitar problemas con el totem
//if (testInternet()) {
//    log_quehacer($id_comercio_general['0']['id_comercio'],$centro,$idioma);
//}

$url_comun = $config['url_comun']['url_comun'];
$url_comercios = $url_comun . 'comercios/';

registrarLog("comercio_detail", "", $comercioId);

//$datos_aux = get_comercio_detalles($comercioId, $idioma);
$datos_aux = get_comercio_detalles2($comercioId, $idioma);

//Pasamos del id_comercio_centro al id_comercio, puesto que la carpeta de las fotos es general y no dependiente de hotel
$comercioId = get_comercio_id_general($comercioId,$centro);

$comercioId =  $comercioId[0]['id_comercio'];
$datos = $datos_aux[0];


if ( isset($datos['logo']) )
{
    $logo = $url_comercios.$comercioId.'/'.$datos['logo'];
}
else
{
    $logo = $url_comercios.'/general/logo.png';
}
if ( isset($datos['banner']) )
{
    $fondo_sup = $url_comercios .$comercioId.'/'.$datos['banner'];
}
else
{
    $fondo_sup = $url_comercios.'/general/anunciante_header1.png';
}

if ( isset($datos['fondo']))
{
    $fondo_inf = $url_comercios.$comercioId.'/'.$datos['fondo'];
}
else
{
    $fondo_inf = $url_comercios.'/general/anunciante_bg1.png';
}
if ( isset($datos['color']))
{
    $color = $datos['color'];
}
else
{
    $color = "plum";
}

$tpl_compras_contenido_dinamico->assign("fondo_sup", $fondo_sup);
$tpl_compras_contenido_dinamico->assign("logo", $logo);
$tpl_compras_contenido_dinamico->assign("fondo_inf", $fondo_inf);
$tpl_compras_contenido_dinamico->assign("color", $color);
$tpl_compras_contenido_dinamico->assignGlobal("latitud", $datos['latitud']);
$tpl_compras_contenido_dinamico->assignGlobal("longitud", $datos['longitud']);

$tpl_compras_contenido_dinamico->assign("lang_quienes_somos", LANG_COMERCIO_QUIENESSOMOS);
$tpl_compras_contenido_dinamico->assign("lang_fotos", LANG_COMERCIO_FOTOS);

$tpl_compras_contenido_dinamico->assignGlobal("numero_habitacion", LANG_COMERCIO_CONTACTO_HABITACION);
$tpl_compras_contenido_dinamico->assignGlobal("hora_contacto", LANG_COMERCIO_CONTACTO_HORA);

$tpl_compras_contenido_dinamico->assignGlobal("boton_enviar", LANG_GLOBAL_ENVIAR);


/////////////////////////////////////////////////////////////////////////////////////////Como llegar 
//Comparo la latitud y longitud del comercio y del hotel y si son las misma cambio de tipo de pantalla

//Datos del centro
$configuracion_totem = configuracion_totem($_SESSION['id_centro']);

$tpl_compras_contenido_dinamico->assignGlobal('nombre_hotel',$configuracion_totem['nombre']);


$distancia_totem_comercio = ( abs($configuracion_totem[latitud] - $datos[latitud]) + abs($configuracion_totem[longitud] - $datos[longitud]) ) ;


if ($distancia_totem_comercio != 0){

    $tpl_compras_contenido_dinamico->newBlock("mapa");
    $qr = "http://maps.google.es/maps?saddr=" . $_SESSION['centroLatitud'] . "," . $_SESSION['centroLongitud'] . "&daddr=" . $datos['latitud'] . "," . $datos['longitud'];
    QRcode::png($qr, '../../../contenido_proyectos/vistaflor/centro_' . $_SESSION['id_centro'] . '/comercios_qr/' . $comercioId . '.png', 'L', 5); //generacion de k QR
    $tpl_compras_contenido_dinamico->assign("qr_ruta", '../../../contenido_proyectos/vistaflor/centro_' . $_SESSION['id_centro'] . '/comercios_qr/' . $comercioId . '.png');

    $tpl_compras_contenido_dinamico->assign("qrMsg", LANG_GLOBAL_QRMSG);
}
else {
    $tpl_compras_contenido_dinamico->assignGlobal('displayNone','displayNone');
}

//////////////////////////////////////////////////////////////////////////////////////////////
//    ../../../contenido_proyectos/vistaflor/_comercios/".$comercioId.'/'.$datos['banner']

$tpl_compras_contenido_dinamico->gotoBlock(TP_ROOTBLOCK);
$tpl_compras_contenido_dinamico->assign("comercio_fachada", "$url_comercios$comercioId/$datos[imagen_fachada]");


$tpl_compras_contenido_dinamico->assign("lang_como_llegar", LANG_COMERCIO_COMOLLEGAR);

$telefono = $datos['telefono'];
$horario =  $datos['horario'];
$horario_en = $datos['horario_en'];
$web = $datos['web'];
$mail = $datos['mail'];
$facebook = $datos['facebook'];
$twitter = $datos['twitter'];
$instagram = $datos['instagram'];


$contenido = array("quienes_somos" => "", "foto" => "", 'video' => "", 'oferta'=>"", 'contacto'=>"");


foreach ($datos_aux as $datos) {
    $contenido[ $datos['tipo_contenido'] ] = $datos['contenido'];
}


$tpl_compras_contenido_dinamico->assign("contenido_quienes_somos", isset($contenido['quienes_somos']) ? $contenido['quienes_somos'] : $contenido['proximamente']);
$tpl_compras_contenido_dinamico->assign("contenido_fotos", isset($contenido['foto']) ? $contenido['foto'] : $contenido['proximamente']);
$tpl_compras_contenido_dinamico->assign("contenido_video", isset($contenido['video']) ? $contenido['video'] : $contenido['proximamente']);
$tpl_compras_contenido_dinamico->assign("contenido_ofertas", isset($contenido['oferta']) ? $contenido['oferta'] : $contenido['proximamente']);
$tpl_compras_contenido_dinamico->assign("contenido_contacto",  isset($contenido['contacto']) ? $contenido['contacto'] : $contenido['proximamente']);



$tpl_compras_contenido_dinamico->assign("lang_telefono", LANG_GLOBAL_TELEFONO);
$tpl_compras_contenido_dinamico->assign("lang_horario", LANG_GLOBAL_HORARIO);
$tpl_compras_contenido_dinamico->assign("lang_url", LANG_GLOBAL_WEB);
if ($mail!="")
{
    $tpl_compras_contenido_dinamico->assign("lang_email", LANG_GLOBAL_EMAIl);
}
else  $tpl_compras_contenido_dinamico->assign("display_email", "display:none");
if ($facebook!="")
{
    $tpl_compras_contenido_dinamico->assign("lang_facebook", "Facebook");
}
else  $tpl_compras_contenido_dinamico->assign("display_facebook", "display:none");
if ($twitter!="")
{
    $tpl_compras_contenido_dinamico->assign("lang_twitter", "Twitter");
}
else  $tpl_compras_contenido_dinamico->assign("display_twitter", "display:none");
if ($instagram!="")
{
    $tpl_compras_contenido_dinamico->assign("lang_instagram", "Instagram");
}
else  $tpl_compras_contenido_dinamico->assign("display_instagram", "display:none");

$tpl_compras_contenido_dinamico->assign("telefono", $telefono);

if ($idioma == 1) $tpl_compras_contenido_dinamico->assign("horario", $horario);
else $tpl_compras_contenido_dinamico->assign("horario", $horario_en);

$tpl_compras_contenido_dinamico->assign("url", $web);
$tpl_compras_contenido_dinamico->assignGlobal("email", $mail);
$tpl_compras_contenido_dinamico->assign("facebook", $facebook);
$tpl_compras_contenido_dinamico->assign("twitter", $twitter);
$tpl_compras_contenido_dinamico->assign("instagram", $instagram);

if ( isset($contenido['contacto']) && !empty( $contenido['contacto'] ) ) {
    $tpl_compras_contenido_dinamico->newBlock("contacto");
    $tpl_compras_contenido_dinamico->assignGlobal("lang_contacto", LANG_COMERCIO_CONTACTO);
}

if ( isset($contenido['oferta']) && !empty( $contenido['oferta'] ) ) {
    $tpl_compras_contenido_dinamico->newBlock("ofertas");
    $tpl_compras_contenido_dinamico->assign("lang_ofertas", LANG_COMERCIO_PROMOCIONES);
}

if ( isset($contenido['video']) && !empty( $contenido['video'] ) ) {
    $tpl_compras_contenido_dinamico->newBlock("videos");
    if ( $comercioId == "84" )
    {
        $tpl_compras_contenido_dinamico->assign("lang_video", LANG_COMERCIO_CARTA);
    }
    else
    {
        $tpl_compras_contenido_dinamico->assign("lang_video", LANG_COMERCIO_VIDEOS);
    }
}

//$resultado['sesion'] = $_SESSION;
//$resultado['query'] = $query;
$resultado['comercio'] = $tpl_compras_contenido_dinamico->getOutputContent();

echo json_encode($resultado);
?>
