<?php

$tpl_portada = new TemplatePower("plantillas/newsletter_formulario.html", T_BYFILE);
$tpl_portada->prepare();

$tpl_portada->assign('titulo_seccion',LANG_NEWSLETTER);
$tpl_portada->assign('enviar',LANG_ENVIAR);
$tpl_portada->assign('escribe_mail',LANG_ESCRIBE_EMAIl);
$tpl_portada->assign('acepto',LANG_ACEPTO_NEWSLETTER);
$tpl_portada->assign('adicional',LANG_NEWSLETTER_ADICIONAL);
$tpl_portada->assign('correcto',LANG_NEWSLETTER_CORRECTO);

$tpl_portada->assign('id_cache',date('YmsHis'));

$tpl_portada->printToScreen();

?>