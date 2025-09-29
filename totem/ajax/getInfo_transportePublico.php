<?php

$tpl_info_transportePublico = new TemplatePower("plantillas/seccion_info_guaguas.html", T_BYFILE);
$tpl_info_transportePublico->prepare();

registrarLog("informacion", "transporte publico");

$tpl_info_transportePublico->assign("title", LANG_INFO_TRANSPORTE_TITLE);
$tpl_info_transportePublico->assign("lang_atras", LANG_GLOBAL_ATRAS);

$tpl_info_transportePublico->assign("lang_primeras", LANG_MALLORCA_GUAGUAS_PRIMERAS_SALIDAS);
$tpl_info_transportePublico->assign("lang_ultimas", LANG_MALLORCA_GUAGUAS_ULTIMAS_SALIDAS);
$tpl_info_transportePublico->assign("lang_frecuencia1", LANG_MALLORCA_GUAGUAS_FRECUENCIA1);
$tpl_info_transportePublico->assign("lang_frecuencia2", LANG_MALLORCA_GUAGUAS_FRECUENCIA2);
$tpl_info_transportePublico->assign("lang_frecuencia3", LANG_MALLORCA_GUAGUAS_FRECUENCIA3);


//$tpl_info_transportePublico->newBlock("idioma_".$_SESSION['idioma']);



$informacion= get_info($_SESSION['idioma'], 2); 
$tpl_info_transportePublico->assign("informacion", $informacion[0]['contenido']);


$id_centro = $_SESSION['id_centro'];
$imagen = "como_llegar.jpg";
$resultado['banner_superior'] = "../../../contenido_proyectos/vistaflor/centro_$id_centro/imagenes/cabecera/$imagen" ;


$resultado['datos'] = $tpl_info_transportePublico->getOutputContent();

echo json_encode($resultado);

?>
