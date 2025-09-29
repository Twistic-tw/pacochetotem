<?php


if (!testInternet()) {
    header($_SERVER['SERVER_PROTOCOL'] . ' 500 Internal Server Error', true, 500);
    exit();
}


registrarLog("mundial2018");


$idioma = $_SESSION['idioma'];

$id_centro = $_SESSION['id_centro'];

$url_base = '../../../contenido_proyectos/pacoche/_general/eurocopa/';

$tpl_sostenibilidad = new TemplatePower("plantillas/eurocopa/eurocopa_principal.html", T_BYFILE);
$tpl_sostenibilidad->prepare();

$tpl_sostenibilidad->assignGlobal("url_base", $url_base);
$tpl_sostenibilidad->assignGlobal("idioma", $idioma );

$tpl_sostenibilidad->assignGlobal("grupos_label", LANG_EUROCOPA_GRUPOS );
$tpl_sostenibilidad->assignGlobal("octavos_label", LANG_EUROCOPA_OCTAVOS );
$tpl_sostenibilidad->assignGlobal("cuartos_label", LANG_EUROCOPA_CUARTOS );
$tpl_sostenibilidad->assignGlobal("semifinal_label", LANG_EUROCOPA_SEMIFINAL );
$tpl_sostenibilidad->assignGlobal("final_label", LANG_EUROCOPA_FINAL );
$tpl_sostenibilidad->assignGlobal("medallero", LANG_EUROCOPA_MEDALLERO );
$tpl_sostenibilidad->assignGlobal("eliminatorias_label", LANG_EUROCOPA_ELIMINATORIAS );
$tpl_sostenibilidad->assignGlobal("calendario_label", LANG_EUROCOPA_CALENDARIO );

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
