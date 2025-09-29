<?php

$tpl_info_general = new TemplatePower("plantillas/seccion_info_consulados.html", T_BYFILE);
$tpl_info_general->prepare();

registrarLog("informacion", "consulados");

$resultado = array();

$tpl_info_general->assign("informacion_title", LANG_INFO_CARNAVAL_TITLE);
$tpl_info_general->assign("lang_atras", LANG_GLOBAL_ATRAS);

$informacion= get_info($_SESSION['idioma'], 5);
$tpl_info_general->assign("informacion", $informacion[0]['contenido']);

//print_r($informacion);

$id_centro = $_SESSION['id_centro'];
$imagen = "carnaval.jpg";
$resultado['banner_superior'] = "../../../contenido_proyectos/pacoche/centro_$id_centro/imagenes/cabecera/$imagen" ;


$resultado['datos'] = $tpl_info_general->getOutputContent();

echo json_encode($resultado);

?>
