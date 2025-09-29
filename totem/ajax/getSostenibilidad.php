<?php
$idioma = $_SESSION['idioma'];

$id_centro = $_SESSION['id_centro'];

$url_base = '../../../contenido_proyectos/vistaflor/_general/sostenibilidad/';

$tpl_sostenibilidad = new TemplatePower("plantillas/seccion_sostenibilidad_principal.html", T_BYFILE);
$tpl_sostenibilidad->prepare();

$tpl_sostenibilidad->assignGlobal("url_base", $url_base);
$tpl_sostenibilidad->assignGlobal("idioma", $idioma );

$tpl_sostenibilidad->assignGlobal("medio_ambiente_label", LANG_MEDIOAMBIENTE_TITLE );
$tpl_sostenibilidad->assignGlobal("compromiso_label", LANG_COMPROMISO_TITLE );
$tpl_sostenibilidad->assignGlobal("sostenibilidad_label", LANG_SOSTENIBILIDAD_TITLE );
$tpl_sostenibilidad->assignGlobal("green_human_label", LANG_GREENHUMAN_TITLE );

$tpl_sostenibilidad->assign("cerrar", LANG_CONOCE_ATRAS);


//Parche para el video de sensimar
if ($id_centro != '275' ){
    $tpl_sostenibilidad->assignGlobal("video_sensimar", '<!--');
    $tpl_sostenibilidad->assignGlobal("video_sensimar2", '-->');
}else{
    $tpl_sostenibilidad->assignGlobal("video_riu", '<!--');
    $tpl_sostenibilidad->assignGlobal("video_riu2", '-->');
}


/*$datos['get'] = $_GET;*/
$datos['datos'] = $tpl_sostenibilidad->getOutputContent();

echo json_encode($datos);
