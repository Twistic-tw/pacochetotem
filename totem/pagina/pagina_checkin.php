<?php

$tpl_cheking = new TemplatePower("plantillas/pagina_checkin.html", T_BYFILE);
$tpl_cheking->prepare();
$tpl_cheking->assign("checkin_title", LANG_CHECKIN_TITLE);

$tpl_cheking->assign("tratamiento", LANG_CHEKIN_TRATAMIENTO);
$tpl_cheking->assign("nombre", LANG_CHEKIN_NOMBRE);
$tpl_cheking->assign("apellidos", LANG_CHEKIN_APELLIDO);
$tpl_cheking->assign("dni", LANG_CHEKIN_DNI);
$tpl_cheking->assign("email", LANG_CHEKIN_EMAIL);
$tpl_cheking->assign("telefono", LANG_CHEKIN_TELEFONO);
$tpl_cheking->assign("pais", LANG_CHEKIN_PAIS);
$tpl_cheking->assign("provincia", LANG_CHEKIN_PROVINCA);
$tpl_cheking->assign("localidad", LANG_CHEKIN_LOCALIDAD);
$tpl_cheking->assign("cod_postal", LANG_CHEKIN_CP);
$tpl_cheking->assign("direccion", LANG_CHEKIN_DIRECION);
$tpl_cheking->assign("sr", LANG_CHEKIN_SR);
$tpl_cheking->assign("sra", LANG_CHEKIN_SRA);
$tpl_cheking->assign("respuesta", LANG_CHEKIN_RESPUESTA);
$tpl_cheking->assign("enviar", LANG_GLOBAL_ENVIAR);

$tpl_cheking->assign("titulo_fecha", LANG_CHEKIN_FECHA);
$tpl_cheking->assign("titulo_localizador", LANG_CHEKIN_LOCALIZADOR);
$tpl_cheking->assign("icono_localizador", LANG_CHEKIN_LOCALIZADOR_ICONO);

$tpl_index->assign("content_contenido", $tpl_cheking->getOutputContent());
?>
