<?php
$idioma = $_SESSION['idioma'];

$id_centro = $_SESSION['id_centro'];

$url_base = '../../../contenido_proyectos/vistaflor/_general/hoteles/';

$tpl_sostenibilidad = new TemplatePower("plantillas/seccion_hoteles_principal.html", T_BYFILE);
$tpl_sostenibilidad->prepare();

$tpl_sostenibilidad->assignGlobal("url_base", $url_base);
$tpl_sostenibilidad->assignGlobal("idioma", $idioma );

$tpl_sostenibilidad->assignGlobal("1_label", LANG_HOTEL1_TITLE );
$tpl_sostenibilidad->assignGlobal("2_label", LANG_HOTEL2_TITLE );
$tpl_sostenibilidad->assignGlobal("3_label", LANG_HOTEL3_TITLE );
$tpl_sostenibilidad->assignGlobal("4_label", LANG_HOTEL4_TITLE );

$tpl_sostenibilidad->assign("cerrar", LANG_CONOCE_ATRAS);

//Pedimos el del primer hotel:
$contenido = totem_getContenidoEspecifico_nuevo(71);
$tpl_sostenibilidad->assign("content",$contenido[0]['content']);



/*$datos['get'] = $_GET;*/
$datos['datos'] = $tpl_sostenibilidad->getOutputContent();

echo json_encode($datos);
