<?php 

$tpl_portada = new TemplatePower("plantillas/portada.html", T_BYFILE);
$tpl_portada->prepare();

$tpl_portada->assign('nombre_sostenibilidad',LANG_FEELAPP_SOSTENIBILIDAD);
$tpl_portada->assign('nombre_destinos',LANG_FEELAPP_DESTINOS);
$tpl_portada->assign('nombre_entretenimiento',LANG_FEELAPP_ENTRETENIMIENTO);

$tpl_portada->assign('restaurantes',LANG_FEELAPP_RESTAURANTES);
$tpl_portada->assign('sultan',LANG_FEELAPP_SULTAN);
$tpl_portada->assign('excursiones',LANG_FEELAPP_EXCURSIONES);
$tpl_portada->assign('guias',LANG_FEELAPP_GUIAS);
$tpl_portada->assign('spa',LANG_FEELAPP_SPA);
$tpl_portada->assign('gastronomia',LANG_FEELAPP_GASTRONOMIA);
$tpl_portada->assign('nuestros_hoteles',LANG_FEELAPP_NUESTROS_HOTELES);

$datos_hotel = datos_hotel();

$tpl_portada->assign('nombre_hotel',$datos_hotel['nombre']);
$tpl_portada->assign('descripcion_hotel',$datos_hotel['descripcion']);

if($_SESSION['proyecto'] == 'oasis'){
    $tpl_portada->assign('inicio_ocultar_riu','<!--');
    $tpl_portada->assign('fin_ocultar_riu','-->');
}elseif($_SESSION['proyecto'] == 'riucanarias'){
    $tpl_portada->assign('inicio_ocultar_oasis','<!--');
    $tpl_portada->assign('fin_ocultar_oasis','-->');
}

$tpl_portada->printToScreen();

?>
