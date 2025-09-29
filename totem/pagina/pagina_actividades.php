<?php


/** bloque para la traduccion de titulos de secciones fijas */
$idioma = $_SESSION['idioma'];
$blokeIndex = 1;

$tpl_actividades = new TemplatePower("plantillas/pagina_actividades.html", T_BYFILE);
$tpl_actividades->prepare();

$tpl_actividades->assign("actividades_title", LANG_ACTIVIDAD_TITLE);


$secciones = totem_getSectionsInCategoria(1,true);
$numeroSecciones = count($secciones);

if ($numeroSecciones > 6){
    $rowsClass = "tworow ";
    
    $blockClass = "block_" . ceil($numeroSecciones/2);
}
else {
    $rowsClass = "onerow ";
    $blockClass = "block_" . $numeroSecciones;
}
foreach ($secciones as $seccionDetail) {
    $tpl_actividades->newBlock("contentBlockLink");
    $tpl_actividades->assign("element_id", "");
    $tpl_actividades->assign("element_class", "$rowsClass $blockClass");
    $tpl_actividades->assign("element_href", "getActividad_contenidoDinamico&seccion=".$seccionDetail['id_seccion']);
    $tpl_actividades->assign("element_href_class", "loadAjax");
    $tpl_actividades->assign("element_icono", $seccionDetail['class_icon']);
    $tpl_actividades->assign("element_text", $seccionDetail['nombre_seccion']);
    $tpl_actividades->assign("index", "actividades".$blokeIndex++);
}


$tpl_index->assign("content_contenido", $tpl_actividades->getOutputContent());

?>
