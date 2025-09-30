<?php

$tpl_portada = new TemplatePower("plantillas/mapa.html", T_BYFILE);
$tpl_portada->prepare();

$tpl_portada->assign('titulo_seccion',LANG_FEELAPP_MAPA_HOTEL);
$tpl_portada->assign('url_mapa','https://view.twisticdigital.com/contenido_proyectos/dunas/centro_'.$_SESSION['id_centro'].'/feelapp/mapas/mapa.jpg?v='.time());

$tpl_portada->printToScreen();

?>