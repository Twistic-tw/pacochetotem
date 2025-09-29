<?php

$tpl_conoce = new TemplatePower("plantillas/seccion_conoce_listadoMunicipios.html", T_BYFILE);
$tpl_conoce->prepare();
$tpl_conoce->assign("conoce_title", LANG_CONOCE_TITLE);

//Recojo datos para asociar el mapa al cliente
$map = conoce_dame_datos_cliente($_SESSION['id_centro']);


//Listo los minicipios 
$municipios = conoce_listado_municipios( $map['id_mapa'] );

$conoce_json['map'] = print_r($municipios, true);

foreach ($municipios as $municipio) {
    $tpl_conoce->newBlock("listado_municipios");
    $tpl_conoce->assign( "nombre_municipio", $municipio['nombre']);
    $tpl_conoce->assign( "id_municipio", $municipio['cod_local']);
}

$tpl_conoce->assignGlobal("lang_atras", LANG_GLOBAL_ATRAS);
$conoce_json['datos'] = $tpl_conoce->getOutputContent();
echo json_encode($conoce_json);

exit();

?>