<?php

$id_idioma = $_SESSION['idioma'];
$id_centro = $_SESSION['id_centro'];

$tipo = $_GET['tipo'];

$url_base = '../../../contenido_proyectos/vistaflor/_general/hoteles/';

switch ($tipo) {

    //Medio Ambiente
    case '1':

        //Contenido 79
        $tpl_sostenibilidad = new TemplatePower("plantillas/seccion_hoteles_solo.html", T_BYFILE);
        $tpl_sostenibilidad->prepare();

        $contenido = totem_getContenidoEspecifico_nuevo(71);
        $tpl_sostenibilidad->assign("content",$contenido[0]['content']);

        break;


    //Compromiso Social    
    case '2':

        //Contenido 79
        $tpl_sostenibilidad = new TemplatePower("plantillas/seccion_hoteles_solo.html", T_BYFILE);
        $tpl_sostenibilidad->prepare();

        $contenido = totem_getContenidoEspecifico_nuevo(72);
        $tpl_sostenibilidad->assign("content",$contenido[0]['content']);

        break;

    case '3':

        //Contenido 79
        $tpl_sostenibilidad = new TemplatePower("plantillas/seccion_hoteles_solo.html", T_BYFILE);
        $tpl_sostenibilidad->prepare();

        $contenido = totem_getContenidoEspecifico_nuevo(73);
        $tpl_sostenibilidad->assign("content",$contenido[0]['content']);

        break;


    case '4':

        //Contenido 79
        $tpl_sostenibilidad = new TemplatePower("plantillas/seccion_hoteles_solo.html", T_BYFILE);
        $tpl_sostenibilidad->prepare();

        $contenido = totem_getContenidoEspecifico_nuevo(74);
        $tpl_sostenibilidad->assign("content",$contenido[0]['content']);

        break;


}


        $tpl_sostenibilidad->assignGlobal("url_base", $url_base);
        $tpl_sostenibilidad->assignGlobal("idioma", $idioma );


        $tpl_sostenibilidad->assign("cerrar", LANG_CONOCE_ATRAS);


//El valor que se pinta
$datos['datos'] = $tpl_sostenibilidad->getOutputContent();

echo json_encode($datos);
