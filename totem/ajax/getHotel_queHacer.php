<?php

$tpl_cuestionario = new TemplatePower("plantillas/seccion_hotel_queHacer.html", T_BYFILE);
$tpl_cuestionario->prepare();

$db = new MySQL();

$tpl_cuestionario->assign("title", "Que hacer");


$resultado['datos'] = $tpl_cuestionario->getOutputContent();
echo json_encode($resultado);

?>
