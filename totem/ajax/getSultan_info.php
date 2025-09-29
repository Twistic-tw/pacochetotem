<?php

$id_idioma = $_SESSION['idioma'];
$id_centro = $_SESSION['id_centro'];

$tipo = $_GET['tipo'];

$url_base = '../../../contenido_proyectos/pacoche/_general/sultan/';

switch ($tipo) {

    //Medio Ambiente
    case '1':
        
        $tpl_sostenibilidad = new TemplatePower("plantillas/seccion_clubdunas_solo.html", T_BYFILE);
        $tpl_sostenibilidad->prepare();

        $contenido = totem_getContenidoEspecifico_nuevo(75);
        $tpl_sostenibilidad->assign("content",$contenido[0]['content']);



        break;

    //Compromiso Social    
    case '2':

        $tpl_sostenibilidad = new TemplatePower("plantillas/seccion_clubdunas_solo.html", T_BYFILE);
        $tpl_sostenibilidad->prepare();

        $contenido = totem_getContenidoEspecifico_nuevo(76);
        $tpl_sostenibilidad->assign("content",$contenido[0]['content']);



        break;



    case '3':

        $tpl_sostenibilidad = new TemplatePower("plantillas/seccion_clubdunas_solo.html", T_BYFILE);
        $tpl_sostenibilidad->prepare();
        $contenido = totem_getContenidoEspecifico_nuevo(77);
        $tpl_sostenibilidad->assign("content",$contenido[0]['content']);


        break;


    case '4':

        $tpl_sostenibilidad = new TemplatePower("plantillas/seccion_clubdunas_solo.html", T_BYFILE);
        $tpl_sostenibilidad->prepare();
        $contenido = totem_getContenidoEspecifico_nuevo(78);
        $tpl_sostenibilidad->assign("content",$contenido[0]['content']);


        break;

    case '5':

        $tpl_sostenibilidad = new TemplatePower("plantillas/seccion_clubdunas_solo.html", T_BYFILE);
        $tpl_sostenibilidad->prepare();
        $contenido = totem_getContenidoEspecifico_nuevo(79);
        $tpl_sostenibilidad->assign("content",$contenido[0]['content']);


        break;
}


        $tpl_sostenibilidad->assignGlobal("url_base", $url_base);
        $tpl_sostenibilidad->assignGlobal("idioma", $idioma );

        $tpl_sostenibilidad->assignGlobal("menu_almuerzo_label", LANG_SULTAN_ALMUERZO );
        $tpl_sostenibilidad->assignGlobal("menu_cena_label", LANG_SULTAN_CENA );
        $tpl_sostenibilidad->assignGlobal("bebidas_label", LANG_SULTAN_BEBIDAS );

        $tpl_sostenibilidad->assign("cerrar", LANG_CONOCE_ATRAS);


//El valor que se pinta
$datos['datos'] = $tpl_sostenibilidad->getOutputContent();

echo json_encode($datos);
