<?php

$id_idioma = $_SESSION['idioma'];
$id_centro = $_SESSION['id_centro'];

$tipo = $_GET['tipo'];

$url_base = '../../../contenido_proyectos/pacoche/_general/spa/';

switch ($tipo) {

    //Medio Ambiente
    case '1':
        
        $tpl_sostenibilidad = new TemplatePower("plantillas/seccion_eco_1.html", T_BYFILE);
        $tpl_sostenibilidad->prepare();



        break;

    //Compromiso Social    
    case '2':

        $tpl_sostenibilidad = new TemplatePower("plantillas/seccion_eco_2.html", T_BYFILE);
        $tpl_sostenibilidad->prepare();



        break;


}


        $tpl_sostenibilidad->assignGlobal("url_base", $url_base);
        $tpl_sostenibilidad->assignGlobal("idioma", $idioma );

$tpl_sostenibilidad->assignGlobal("galeria_label", LANG_SPA_GALERIA );
        $tpl_sostenibilidad->assignGlobal("menu_cena_label", LANG_SULTAN_CENA );
        $tpl_sostenibilidad->assignGlobal("bebidas_label", LANG_SULTAN_BEBIDAS );

        $tpl_sostenibilidad->assign("cerrar", LANG_CONOCE_ATRAS);


//El valor que se pinta
$datos['datos'] = $tpl_sostenibilidad->getOutputContent();

echo json_encode($datos);
