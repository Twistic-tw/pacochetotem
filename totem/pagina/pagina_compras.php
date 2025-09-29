<?php


/** bloque para la traduccion de titulos de secciones fijas */
$idioma = $_SESSION['idioma'];
$blokeIndex = 1;

$tpl_compras = new TemplatePower("plantillas/pagina_compras.html", T_BYFILE);
$tpl_compras->prepare();

$tpl_compras->assign("compras_title", LANG_COMPRAS_TITLE);


$secciones = totem_getSectionsInCategoria(2,true);
$numeroSecciones = count($secciones);

if ($numeroSecciones > 6){
    $rowsClass = "tworow ";
    
    $blockClass = "block_" . ceil($numeroSecciones/2);
}
else {
    $rowsClass = "onerow ";
    $blockClass = "block_" . $numeroSecciones;
}
foreach ($secciones as $seccionDetail) 
    {
    $tpl_compras->newBlock("contentBlockLink");
    $tpl_compras->assign("element_id", "");
    $tpl_compras->assign("element_class", "$rowsClass $blockClass");
    $tpl_compras->assign("element_href", "getCompras_contenidoDinamico&seccion=".$seccionDetail['id_seccion']);
    $tpl_compras->assign("element_href_class", "loadAjax");
    $tpl_compras->assign("element_icono", $seccionDetail['class_icon']);
    $tpl_compras->assign("element_text", $seccionDetail['nombre_seccion']);
    $tpl_compras->assign("index", "compras".$blokeIndex++);
}


$tpl_index->assign("content_contenido", $tpl_compras->getOutputContent());

?>
