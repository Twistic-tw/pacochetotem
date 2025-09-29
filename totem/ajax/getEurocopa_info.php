<?php

$id_idioma = $_SESSION['idioma'];
$id_centro = $_SESSION['id_centro'];

$tipo = $_GET['tipo'];

$url_base = '../../../contenido_proyectos/vistaflor/_general/sostenibilidad/';

switch ($tipo) {

    //Calendario
    case '1':

        $tpl_sostenibilidad = new TemplatePower("plantillas/eurocopa/calendario.html", T_BYFILE);
        $tpl_sostenibilidad->prepare();

        break;

    //Grupos
    case '2':

        $tpl_sostenibilidad = new TemplatePower("plantillas/eurocopa/grupos.html", T_BYFILE);
        $tpl_sostenibilidad->prepare();

        break;

    //Eliminatorias
    case '3':

        $tpl_sostenibilidad = new TemplatePower("plantillas/eurocopa/eliminatorias.html", T_BYFILE);
        $tpl_sostenibilidad->prepare();

        break;

}

$tpl_sostenibilidad->assignGlobal("url_base", $url_base);
$tpl_sostenibilidad->assignGlobal("idioma", $idioma );

/*$tpl_sostenibilidad->assignGlobal("medio_ambiente_label", LANG_MEDIOAMBIENTE_TITLE );
$tpl_sostenibilidad->assignGlobal("compromiso_label", LANG_COMPROMISO_TITLE );
$tpl_sostenibilidad->assignGlobal("sostenibilidad_label", LANG_SOSTENIBILIDAD_TITLE );
*/
$tpl_sostenibilidad->assign("cerrar", LANG_CONOCE_ATRAS);


//El valor que se pinta
$datos['datos'] = $tpl_sostenibilidad->getOutputContent();

echo json_encode($datos);
