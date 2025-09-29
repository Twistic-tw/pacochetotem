<?php

$tpl_info_transportePublico = new TemplatePower("plantillas/seccion_info_medico.html", T_BYFILE);
$tpl_info_transportePublico->prepare();

registrarLog("informacion", " Servicio Medico");

$tpl_info_transportePublico->assign("title", LANG_INFO_MEDICO_TITLE);
$tpl_info_transportePublico->assign("lang_atras", LANG_GLOBAL_ATRAS);


//$tpl_info_transportePublico->newBlock("idioma_".$_SESSION['idioma']);



$informacion= get_info($_SESSION['idioma'], 3);
$tpl_info_transportePublico->assign("informacion", $informacion[0]['contenido']);


$id_centro = $_SESSION['id_centro'];
$imagen = "medico.jpg";
$resultado['banner_superior'] = "../../../contenido_proyectos/vistaflor/centro_$id_centro/imagenes/cabecera/$imagen" ;


$resultado['datos'] = $tpl_info_transportePublico->getOutputContent();

echo json_encode($resultado);

?>
