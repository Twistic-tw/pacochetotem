<?php


/** bloque para la traduccion de titulos de secciones fijas */
$idioma = $_SESSION['idioma'];
$blokeIndex = 1;

$tpl_comerOcio = new TemplatePower("plantillas/pagina_comer_ocio.html", T_BYFILE);
$tpl_comerOcio->prepare();

$tpl_comerOcio->assign("comerOcio_title", LANG_COMEROCIO_TITLE);


$secciones = totem_getSectionsInCategoria(3,true);

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
    $tpl_comerOcio->newBlock("contentBlockLink");
    $tpl_comerOcio->assign("element_id", "");
    $tpl_comerOcio->assign("element_class", "$rowsClass $blockClass");
    $tpl_comerOcio->assign("element_href", "getComerOcio_contenidoDinamico&seccion=".$seccionDetail['id_seccion']);
    $tpl_comerOcio->assign("element_href_class", "loadAjax");
    $tpl_comerOcio->assign("element_icono", $seccionDetail['class_icon']);
    $tpl_comerOcio->assign("element_text", $seccionDetail['nombre_seccion']);
    $tpl_comerOcio->assign("index", "comerOcio".$blokeIndex++);
}


$tpl_index->assign("content_contenido", $tpl_comerOcio->getOutputContent());

?>
