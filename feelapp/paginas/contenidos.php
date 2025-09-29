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


//<img src="../../../contenido_proyectos/pacoche/centro_21/imagenes/cabecera/mapa_hd.jpg">

if( $restaurantes[0]['foto_cabecera'] == 'mapa_hd.jpg' || $restaurantes[0]['foto_cabecera'] == 'mapa.jpg' || $restaurantes[0]['foto_cabecera'] == 'MAPA_ALTAMAR.jpg'){
    $tpl_portada->assign('include_img',"<img style='width: 100%; margin-bottom: 30px' src='http://admin.twisticdigital.com/contenido_proyectos/dunas/contenido/centro_" . $_SESSION['id_centro'] . "/imagenes/cabecera/" . $restaurantes[0]['foto_cabecera'] . "'>");
}

/* Temporal */
$texto_restaurantes = str_replace('riu_class_es.jpg','riu_class_1.png',$texto_restaurantes);
$texto_restaurantes = str_replace('riu_class_en.jpg','riu_class_2.png',$texto_restaurantes);
$texto_restaurantes = str_replace('riu_class_de.jpg','riu_class_3.png',$texto_restaurantes);
if($_GET['id_contenido'] == 73){
    $texto_restaurantes = str_replace('style="','style="width:100% !important;',$texto_restaurantes);
    $tpl_portada->assign('btn_alta','<a target="_blank" href="https://www.riu.com/es/riu-class/alta/alta.jsp"><div class="boton-alta">'.LANG_FEELAPP_DATE_ALTA.'</div></a>');
}

if($_SESSION['proyecto'] == 'oasis' && $_GET['id_contenido'] == 102){
    $texto_restaurantes = str_replace('img','img style="width:100% !important; heigth: auto"',$texto_restaurantes);
}

$texto_restaurantes = str_replace('../contenido','http://admin.twisticdigital.com/contenido_proyectos/dunas/contenido',$texto_restaurantes);

/* Fin del temporal */

if(strpos($texto_restaurantes, 'safe_planet.jpg')){
    $texto_restaurantes = str_replace('style="','style="width:100%;',$texto_restaurantes);
}


if($restaurantes[0]['foto_cabecera']){
    $tpl_portada->assign('foto_cabecera','../../../contenido_proyectos/pacoche/centro_' . $_SESSION['id_centro'] . '/imagenes/cabecera/' . $restaurantes[0]['foto_cabecera']);
}else{
    $tpl_portada->assign('foto_cabecera','../images/cabecera/calendario.jpg');
}




$tpl_portada->assign('prueba_texto',$texto_restaurantes);
$tpl_portada->assign('titulo_seccion',$restaurantes[0]['title']);

$tpl_portada->printToScreen();

?>
