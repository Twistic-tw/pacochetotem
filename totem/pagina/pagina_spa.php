<?php


/** bloque para la traduccion de titulos de secciones fijas */
$idioma = $_SESSION['idioma'];
$blokeIndex = 1;

$tpl_sostenibilidad = new TemplatePower("plantillas/pagina_spa.html", T_BYFILE);
$tpl_sostenibilidad->prepare();

$tpl_sostenibilidad->assign("sostenibilidad_title", LANG_SOSTENIBILIDAD_TITLE);

$tpl_sostenibilidad->newBlock("contentBlockLink");
$tpl_sostenibilidad->assign("element_id","spa_link_id");
$tpl_sostenibilidad->assign("element_class","onerow block_2");
$tpl_sostenibilidad->assign("element_href","getSpa"); //Cambiar la ruta de esto
$tpl_sostenibilidad->assign("element_href_class","loadAjax");
$tpl_sostenibilidad->assign("element_icono","icon-telefonoEmergencia");
$tpl_sostenibilidad->assign("element_text", LANG_SOSTENIBILIDAD_TITLE);
$tpl_sostenibilidad->assign("index", "sostenibilidad".$blokeIndex++);

$tpl_index->assign("content_contenido", $tpl_sostenibilidad->getOutputContent());

?>
