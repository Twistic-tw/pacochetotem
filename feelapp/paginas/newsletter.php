<?php

$tpl_portada = new TemplatePower("plantillas/newsletter.html", T_BYFILE);
$tpl_portada->prepare();

$tpl_portada->assign('titulo_seccion',LANG_NEWSLETTER);

$tpl_portada->printToScreen();

?>