<?php 

$tpl_footer = new TemplatePower("plantillas/index_footer.html", T_BYFILE);
$tpl_footer->prepare();

$tpl_footer->printToScreen();

?>