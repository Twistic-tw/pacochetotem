<?php
$idioma = $_SESSION['idioma'];

$id_centro = $_SESSION['id_centro'];

$url_base = '../../../contenido_proyectos/vistaflor/_general/dunasclub/';

$tpl_sostenibilidad = new TemplatePower("plantillas/seccion_sultan_principal.html", T_BYFILE);
$tpl_sostenibilidad->prepare();

$tpl_sostenibilidad->assignGlobal("url_base", $url_base);
$tpl_sostenibilidad->assignGlobal("idioma", $idioma );

$tpl_sostenibilidad->assignGlobal("seccion1_dunasclub", LANG_CLUBDUNAS_SECC1 );
$tpl_sostenibilidad->assignGlobal("seccion2_dunasclub", LANG_CLUBDUNAS_SECC2 );
$tpl_sostenibilidad->assignGlobal("seccion3_dunasclub", LANG_CLUBDUNAS_SECC3 );
$tpl_sostenibilidad->assignGlobal("seccion4_dunasclub", LANG_CLUBDUNAS_SECC4 );
$tpl_sostenibilidad->assignGlobal("seccion5_dunasclub", LANG_CLUBDUNAS_SECC5 );


$tpl_sostenibilidad->assign("cerrar", LANG_CONOCE_ATRAS);

//Pedimos el del primer hotel:
$contenido = totem_getContenidoEspecifico_nuevo(75);
$tpl_sostenibilidad->assign("content",$contenido[0]['content']);


$datos['datos'] = $tpl_sostenibilidad->getOutputContent();

echo json_encode($datos);
