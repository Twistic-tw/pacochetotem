<?php

$tpl_portada = new TemplatePower("plantillas/calendario.html", T_BYFILE);
$tpl_portada->prepare();

switch ($i) {
    case 1:
        $array_dias_semanas = array(1 => 'L', 2 => 'M', 3 => 'X', 4 => 'J', 5 => 'V', 6 => 'S', 7 => 'D');
        break;
    case 2:
        $array_dias_semanas = array(1 => 'Mon', 2 => 'Tue', 3 => 'Wed', 4 => 'Thu', 5 => 'Fri', 6 => 'Sat', 7 => 'Sun');
        break;
    default:
        $array_dias_semanas = array(1 => 'L', 2 => 'M', 3 => 'X', 4 => 'J', 5 => 'V', 6 => 'S', 7 => 'D');
}

$tpl_portada->assign('titulo_seccion', LANG_FEELAPP_ENTRETENIMIENTO);
$tpl_portada->assign('fecha_actual', fechas_calendario(date('Y-m-d')));

$fechas = fechas_7_dias(date('Y-m-d'));

foreach ($fechas as $fecha) {

    $tpl_portada->newBlock('letras-fechas');
    $tpl_portada->assign('dia_semana', $array_dias_semanas[date('N', strtotime($fecha))]);
    $tpl_portada->newBlock('numero-fechas');
    $tpl_portada->assign('dia_semana', date('j', strtotime($fecha)));
    $tpl_portada->assign('fecha_semana', $fecha);
    $tpl_portada->assign('fecha_texto_calendario', fechas_calendario(date($fecha)));
    if ($fecha == date('Y-m-d')) {
        $tpl_portada->assign('active', 'taken-day');
    } else {
        $tpl_portada->assign('active', 'clear-day');
    }

}
$tpl_portada->gotoBlock('ROOT');


if($_SESSION['id_centro'] == 19029){ //Ubicación: Suites & Villas // LANG_FEELAPP_LOCATION
    $tpl_portada->assignGlobal('customlocation', LANG_FEELAPP_LOCATION . ': Suites & Villas');
} else {
    $tpl_portada->assignGlobal('customlocation', '');
}

foreach ($fechas as $fecha) {

    $tpl_portada->newBlock('contenedor_actividades');
    $tpl_portada->assign('fecha_contenedor_actividades', $fecha);

    if ($fecha == date('Y-m-d')) {
        $tpl_portada->assign('displaynone', '');
    } else {
        $tpl_portada->assign('displaynone', 'displaynone');
    }

    if($_SESSION['id_centro'] == 19029){
        $actividades_hotel = actividades_hotel($fecha, $_SESSION['idioma'], 'bd_feeltourist_dunas_villas');
    } else {
        $actividades_hotel = actividades_hotel($fecha, $_SESSION['idioma']);
    }

    if ($actividades_hotel) {
        foreach ($actividades_hotel as $actividad) {

            $total_filtros = 0;

            $tpl_portada->newBlock('actividades');
            $tpl_portada->assign('nombre_actividad', $actividad['title']);
            $tpl_portada->assign('hora_inicio_actividad', $actividad['hora_ini']);

            if($_SESSION['id_centro'] != 1903){
                $tpl_portada->assign('hora_fin_actividad', $actividad['hora_fin']);
            }

            $tpl_portada->assign('id_actividad', $actividad['id_evento']);

            if (file_exists("../../../contenido_proyectos/dunas/centro_" . $_SESSION['id_centro'] . "/imagenes/agenda/" . $actividad['foto_evento'])) {
                $tpl_portada->assign('img_url', 'https://view.twisticdigital.com/contenido_proyectos/dunas/centro_' . $_SESSION['id_centro'] . '/imagenes/agenda/' . $actividad['foto_evento']);
            } else {
                $tpl_portada->assign("img_url", "https://view.twisticdigital.com/contenido_proyectos/dunas/_general/logo.png");
            }

            if ($actividad['id_lugar'] != 1) {
                $total_filtros++;
                $tpl_portada->assign('nombre_lugar', $actividad['nombre_lugar']);
            }
            $tpl_portada->assign('nombre_categoria', $actividad['nombre_cat']);
            if ($actividad['id_cat'] > 0) {
                $total_filtros++;
            }

            if ($total_filtros == 2) {
                $tpl_portada->assign('span_separation', '<span class="fil_separation"> - </span>');
            }

        }
        $tpl_portada->gotoBlock('ROOT');
    }


}
$tpl_portada->gotoBlock('ROOT');

$tpl_portada->printToScreen();

?>