<?php

// Comprueba si hay internet
/*if (!testInternet()) {
    header($_SERVER['SERVER_PROTOCOL'] . ' 500 Internal Server Error', true, 500);
    exit();
}*/

$id_centro = $_SESSION['id_centro'];
$imagen = "natacion1.jpg";


$tpl_info_piscinas = new TemplatePower("plantillas/seccion_piscinas.html", T_BYFILE);
$tpl_info_piscinas->prepare();

$tpl_info_piscinas->assign("piscinas_title", LANG_PISCINAS_TITLE);
$tpl_info_piscinas->assign("lang_disclaimer", LANG_GLOBAL_DISCLAIMER);
$tpl_info_piscinas->assign("lang_atras", LANG_GLOBAL_ATRAS);


$tpl_info_piscinas->assignGlobal("tph", LANG_PISCINAS_PH);
$tpl_info_piscinas->assignGlobal("ttemperatura", LANG_PISCINAS_TEMPERATURA);

$tpl_info_piscinas->assignGlobal("id_centro", $id_centro);

$piscinas = get_piscinas();

foreach ($piscinas as $piscina){

    $tpl_info_piscinas->newBlock("piscinas");
    $tpl_info_piscinas->assign("nombre", $piscina['nombre']);
    $tpl_info_piscinas->assign("id_piscina", $piscina['id_piscina']);
    $tpl_info_piscinas->assign("ph", $piscina['ph']);
    $tpl_info_piscinas->assign("temperatura", $piscina['temperatura']);
    if ($piscina['id_piscina']=="3"){
        $tpl_info_piscinas->assign("tcloro", "Br");
    }else{
        $tpl_info_piscinas->assign("tcloro", LANG_PISCINAS_CLORO);
    }
    $tpl_info_piscinas->assign("cloro", $piscina['cloro']);


}

$datos['datos'] = $tpl_info_piscinas->getOutputContent();
$datos['banner_superior'] = "../../../contenido_proyectos/pacoche/centro_$id_centro/imagenes/cabecera/$imagen" ;

echo json_encode($datos);


?>