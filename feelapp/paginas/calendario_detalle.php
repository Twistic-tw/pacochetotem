<?php

$tpl_portada = new TemplatePower("plantillas/calendario_detalle.html", T_BYFILE);
$tpl_portada->prepare();

$tpl_portada->assign('titulo_seccion',LANG_FEELAPP_ENTRETENIMIENTO);

$id_evento = $_POST['id_evento'];

$datos_actividad = get_datos_actividad($id_evento);

$tpl_portada->assign('titulo_actividad',$datos_actividad['title']);
$tpl_portada->assign('hora_ini',$datos_actividad['hora_ini']);
if($_SESSION['id_centro'] != 1903){
    $tpl_portada->assign('hora_fin',' - ' .$datos_actividad['hora_fin']);
}
$tpl_portada->assign('nombre_categoria',$datos_actividad['nombre_categoria']);

if($datos_actividad['nombre_categoria']){
    $seperacion = ' - ';
}else{
    $seperacion = '';
}
$tpl_portada->assign('nombre_lugar',$seperacion.$datos_actividad['nombre_lugar']);

if($datos_actividad['descripcion']){
    $tpl_portada->assign('descripcion',$datos_actividad['descripcion']);
}else{
    $tpl_portada->assign('ocultar_descripcion','displayNone');
}

if($datos_actividad['foto_evento']){
    $tpl_portada->assign('url_imagen','https://view.twisticdigital.com/contenido_proyectos/dunas/centro_'.$_SESSION['id_centro'].'/imagenes/agenda/'.$datos_actividad['foto_evento']);
}else{
    $tpl_portada->assign('ocultar_imagen','displayNone');
}

if($datos_actividad['video_evento']){
    if(is_file('../../../contenido_proyectos/dunas/centro_'.$_SESSION['id_centro'].'/videos/'.$datos_actividad['video_evento'])){
        $tpl_portada->assign('url_video','https://view.twisticdigital.com/contenido_proyectos/dunas/centro_'.$_SESSION['id_centro'].'/videos/'.$datos_actividad['video_evento']);
        $tpl_portada->assign('ocultar_imagen','displayNone');
    }else{
        $tpl_portada->assign('ocultar_video','displayNone');
    }
}else{
    $tpl_portada->assign('ocultar_video','displayNone');
}

$tpl_portada->printToScreen();

?>