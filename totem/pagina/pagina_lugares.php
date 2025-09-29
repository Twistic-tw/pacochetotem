<?php


/** bloque para la traduccion de titulos de secciones fijas */
$idioma = $_SESSION['idioma'];
$blokeIndex = 1;

$tpl_destinos = new TemplatePower("plantillas/pagina_lugares.html", T_BYFILE);
$tpl_destinos->prepare();

$tpl_destinos->assign("destinos_title", LANG_DESTINOS_TITLE);

$tpl_destinos->newBlock("contentBlockLink");
$tpl_destinos->assign("element_id","lugar_link_id");
$tpl_destinos->assign("element_class","onerow block_2");
$tpl_destinos->assign("element_href","getLugar"); //Cambiar la ruta de esto
$tpl_destinos->assign("element_href_class","loadAjax");
$tpl_destinos->assign("element_icono","icon-telefonoEmergencia");
$tpl_destinos->assign("element_text", LANG_DESTINOS_TITLE);
$tpl_destinos->assign("index", "destino".$blokeIndex++);

$tpl_index->assign("content_contenido", $tpl_destinos->getOutputContent());

?>
