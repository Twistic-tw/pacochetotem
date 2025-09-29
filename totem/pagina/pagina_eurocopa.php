<?php


/** bloque para la traduccion de titulos de secciones fijas */
$idioma = $_SESSION['idioma'];
$blokeIndex = 1;

$tpl_eurocopa = new TemplatePower("plantillas/pagina_eurocopa.html", T_BYFILE);
$tpl_eurocopa->prepare();

$tpl_eurocopa->assign("sostenibilidad_title", LANG_SOSTENIBILIDAD_TITLE);

$tpl_eurocopa->newBlock("contentBlockLink");
$tpl_eurocopa->assign("element_id","eurocopa_link_id");
$tpl_eurocopa->assign("element_class","onerow block_2");
$tpl_eurocopa->assign("element_href","getEurocopa"); //Cambiar la ruta de esto
$tpl_eurocopa->assign("element_href_class","loadAjax");
$tpl_eurocopa->assign("element_icono","icon-telefonoEmergencia");
$tpl_eurocopa->assign("element_text", LANG_SOSTENIBILIDAD_TITLE);
$tpl_eurocopa->assign("index", "sostenibilidad".$blokeIndex++);

$tpl_index->assign("content_contenido", $tpl_eurocopa->getOutputContent());

?>