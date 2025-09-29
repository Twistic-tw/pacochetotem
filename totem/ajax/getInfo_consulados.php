<?php

$tpl_info_general = new TemplatePower("plantillas/seccion_info_consulados.html", T_BYFILE);
$tpl_info_general->prepare();

registrarLog("informacion", "consulados");

$resultado = array();

$tpl_info_general->assign("informacion_title", LANG_INFO_CONSULADOS_TITLE);
$tpl_info_general->assign("lang_atras", LANG_GLOBAL_ATRAS);

$informacion= get_info($_SESSION['idioma'], 4);
$tpl_info_general->assign("informacion", $informacion[0]['contenido']);

//print_r($informacion);

$id_centro = $_SESSION['id_centro'];
$imagen = "telefonos_de_interes.jpg";
$resultado['banner_superior'] = "../../../contenido_proyectos/vistaflor/centro_$id_centro/imagenes/cabecera/$imagen" ;


$resultado['datos'] = $tpl_info_general->getOutputContent();

echo json_encode($resultado);

?>
