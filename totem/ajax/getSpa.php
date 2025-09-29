<?php
$idioma = $_SESSION['idioma'];

$id_centro = $_SESSION['id_centro'];

$url_base = '../../../contenido_proyectos/pacoche/_general/spa/';

$tpl_sostenibilidad = new TemplatePower("plantillas/seccion_spa_principal.html", T_BYFILE);
$tpl_sostenibilidad->prepare();

$tpl_sostenibilidad->assignGlobal("url_base", $url_base);
$tpl_sostenibilidad->assignGlobal("idioma", $idioma );

$tpl_sostenibilidad->assignGlobal("1_label", LANG_PSC1_TITLE );
$tpl_sostenibilidad->assignGlobal("2_label", LANG_PSC2_TITLE );


$tpl_sostenibilidad->assign("cerrar", LANG_CONOCE_ATRAS);



/*$datos['get'] = $_GET;*/
$datos['datos'] = $tpl_sostenibilidad->getOutputContent();

echo json_encode($datos);
