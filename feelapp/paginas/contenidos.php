<?php

$tpl_portada = new TemplatePower("plantillas/contenidos.html", T_BYFILE);
$tpl_portada->prepare();

$restaurantes = get_contenidos($_GET['id_contenido']);

$texto_restaurantes = str_replace('!important','',$restaurantes[0]['content']);

//$texto_restaurantes = $restaurantes[0]['content'];

if($_SESSION['proyecto'] == 'oasis'){
    //$texto_restaurantes = str_replace('iconos_svg','iconos_svg/azules',$texto_restaurantes);
}else{
    $texto_restaurantes = str_replace('iconos_svg','iconos_svg/',$texto_restaurantes);
}


//<img src="../../../contenido_proyectos/dunas/centro_21/imagenes/cabecera/mapa_hd.jpg">

if( $restaurantes[0]['foto_cabecera'] == 'mapa_hd.jpg' || $restaurantes[0]['foto_cabecera'] == 'mapa.jpg' || $restaurantes[0]['foto_cabecera'] == 'MAPA_ALTAMAR.jpg' || $restaurantes[0]['foto_cabecera'] == 'mapa_suites.jpg'){
    $tpl_portada->assign('include_img',"<img class='zoom_imagen' style='width: 100%; margin-bottom: 30px' src='https://view.twisticdigital.com/contenido_proyectos/dunas/centro_" . $_SESSION['id_centro'] . "/imagenes/cabecera/" . $restaurantes[0]['foto_cabecera'] . "'>");
}

if($_SESSION['proyecto'] == 'oasis' && $_GET['id_contenido'] == 102){
    $texto_restaurantes = str_replace('img','img style="width:100% !important; heigth: auto"',$texto_restaurantes);
}

$texto_restaurantes = str_replace('../../../contenido_proyectos/dunas','https://view.twisticdigital.com/contenido_proyectos/dunas',$texto_restaurantes);

/* Fin del temporal */

if(strpos($texto_restaurantes, 'safe_planet.jpg')){
    $texto_restaurantes = str_replace('style="','style="width:100%;',$texto_restaurantes);
}


if($restaurantes[0]['foto_cabecera']){
    $tpl_portada->assign('foto_cabecera','../../../contenido_proyectos/dunas/centro_' . $_SESSION['id_centro'] . '/imagenes/cabecera/' . $restaurantes[0]['foto_cabecera']);
}else{
    $tpl_portada->assign('foto_cabecera','../images/cabecera/calendario.jpg');
}

$array_centros_piscinas = [1901,1902,19029];
if(in_array($_SESSION['id_centro'],$array_centros_piscinas) && $_GET['id_contenido'] == 42){


    $tpl_portada->assignGlobal('nombre_cloro',LANG_PISCINAS_CLORO);
    $tpl_portada->assignGlobal('nombre_ph',LANG_PISCINAS_PH);
    $tpl_portada->assignGlobal('nombre_temperatura',LANG_PISCINAS_TEMPERATURA);

    $piscinas = get_piscinas();

    foreach($piscinas as $piscina){

        $tpl_portada->newBlock('piscinas');
        $tpl_portada->assign('id_piscina',$piscina['id_piscina']);
        $tpl_portada->assign('nombre',$piscina['nombre']);
        $tpl_portada->assign('cloro',$piscina['cloro']);
        $tpl_portada->assign('ph',$piscina['ph']);
        $tpl_portada->assign('temperatura',$piscina['temperatura']);
        $tpl_portada->assign('top',$piscina['top']);
        $tpl_portada->assign('left',$piscina['left']);
        $tpl_portada->assign('imagen','https://view.twisticdigital.com/contenido_proyectos/dunas/centro_'.$_SESSION['id_centro'].'/imagenes/contenidos/piscina_'.$piscina['id_piscina'].'.jpg?v=1');

    }$tpl_portada->gotoBlock('_ROOT');
}


$tpl_portada->assign('prueba_texto',$texto_restaurantes);
$tpl_portada->assign('titulo_seccion',$restaurantes[0]['title']);

$tpl_portada->printToScreen();

?>
