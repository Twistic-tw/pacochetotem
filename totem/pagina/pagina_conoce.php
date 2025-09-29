<?php

$tpl_conoce = new TemplatePower("plantillas/pagina_conoce.html", T_BYFILE);
$tpl_conoce->prepare();
$tpl_conoce->assign("conoce_title", LANG_CONOCE_TITLE);

//Recojo datos para asociar el mapa al cliente
$map = conoce_dame_datos_cliente($_SESSION['id_centro']);

//Listo los minicipios 
$municipios = conoce_listado_municipios( $map['id_mapa'] );
foreach ($municipios as $municipio) {
    $tpl_conoce->newBlock("listado_municipios");
    $tpl_conoce->assign( "nombre_municipio", $municipio['nombre']);
    $tpl_conoce->assign( "id_municipio", $municipio['cod_local']);
}

$tpl_conoce->assignGlobal("lang_atras", LANG_GLOBAL_ATRAS);

//Asignacion de lo bloques de forma estatica Municipios y rutas

$tpl_conoce->newBlock("contentBlockLink");
$tpl_conoce->assign("element_id","");
$tpl_conoce->assign("element_class","onerow block_2");
$tpl_conoce->assign("element_href","getConoce_Municipios"); //Cambiar la ruta de esto
$tpl_conoce->assign("element_href_class","loadAjax");
$tpl_conoce->assign("element_icono","icon-telefonoEmergencia");
$tpl_conoce->assign("element_text", LANG_CONOCE_MUNICIPIOS);
$tpl_conoce->assign("index", "conoce".$blokeIndex++);

$tpl_conoce->newBlock("contentBlockLink");
$tpl_conoce->assign("element_id","");
$tpl_conoce->assign("element_class","onerow block_2");
$tpl_conoce->assign("element_href","getConoce_Municipios?section=sitios"); //Cambiar la ruta de esto 
$tpl_conoce->assign("element_href_class","loadAjax");
$tpl_conoce->assign("element_icono","icon-telefonoEmergencia");
$tpl_conoce->assign("element_text", LANG_CONOCE_RUTAS);
$tpl_conoce->assign("index", "conoce".$blokeIndex++);

/*
$datos_region = conoce_dame_datos_region();
$descripcion = $datos_region['descripcion'];

$tpl_conoce->assign("descripcion", $descripcion);
$tpl_conoce->assign("lang_municipio", LANG_GLOBAL_MUNICIPIOS);
*/

$tpl_index->assign("content_contenido", $tpl_conoce->getOutputContent());
