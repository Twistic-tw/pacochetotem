<?php


$tpl_portada = new TemplatePower("plantillas/restaurantes.html", T_BYFILE);
$tpl_portada->prepare();

$id_centro = $_SESSION['id_centro'];

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

$tpl_portada->assign('titulo_seccion', LANG_FEELAPP_RESTAURANTES);
$tpl_portada->assignGlobal('id_centro',$_SESSION['id_centro']);

//$tpl_portada->assign('fecha_actual', fechas_calendario(date('Y-m-d')));

$tipo = 1;
$restaurantes = get_restaurantes($tipo);

foreach($restaurantes as $key => $restaurante){

    $tpl_portada->newBlock('restaurantes');
    $tpl_portada->assign('id_restaurante',$restaurante['id_restaurante']);
    $tpl_portada->assign('imagen','https://view.twisticdigital.com/contenido_proyectos/dunas/centro_'.$id_centro.'/imagenes/contenidos/'.$restaurante['imagen']);
    $tpl_portada->assign('release',$restaurante['release']);
    $tpl_portada->assign('nombre',$restaurante['nombre']);
    $tpl_portada->assign('subtitulo',$restaurante['subtitulo']);
    $tpl_portada->assign('descripcion',$restaurante['descripcion']);
    $tpl_portada->assign('observaciones',$restaurante['observaciones']);

    $dias_apertura = $restaurante['dias_apertura'];
    $reserva_permitida = $restaurante['reserva'];

    if($reserva_permitida == 1){
        $tpl_portada->assign('btn_reserva','<div data-id_restaurante="'.$restaurante['id_restaurante'].'" class="btn_reserva_restaurante">'.LANG_RESERVAR_MESA.'</div>');
    }

    $turnos = $restaurante['turnos'];

    foreach($turnos as $key_turno => $turno){

        $tpl_portada->newBlock('turnos_restaurantes');
        $tpl_portada->assign('id_restaurante_turno',$turno['id_restaurante_turno']);
        $tpl_portada->assign('icono','https://view.twisticdigital.com/dunas/feelapp/images/iconos/'.$turno['icono']);
        $tpl_portada->assign('nombre_turno',$turno['nombre_turno']);

        $turno_activo = $turno['activo_turno'];
        $horarios_turno = $turno['horarios_turno'];

        $array_horario_turnos = null;
        $index_horario_turnos = 0;

        foreach($horarios_turno AS $key_horario => $horario_turno){

            $activo_turno_horario = $horario_turno['activo_turno_horario'];

            if(!$array_horario_turnos[$index_horario_turnos]['hora_inicio']){
                $array_horario_turnos[$index_horario_turnos]['hora_inicio'] = $horario_turno['hora_inicio'];
                $array_horario_turnos[$index_horario_turnos]['hora_fin'] = $horario_turno['hora_fin'];
            }else{

                if($array_horario_turnos[$index_horario_turnos]['hora_fin'] == $horario_turno['hora_inicio']){
                    $array_horario_turnos[$index_horario_turnos]['hora_fin'] = $horario_turno['hora_fin'];
                }else{
                    $index_horario_turnos++;
                    $array_horario_turnos[$index_horario_turnos]['hora_inicio'] = $horario_turno['hora_inicio'];
                    $array_horario_turnos[$index_horario_turnos]['hora_fin'] = $horario_turno['hora_fin'];

                }

            }


        }


        $texto_horario_turnos = null;
        foreach($array_horario_turnos as $ht){
            $texto_horario_turnos.= $ht['hora_inicio'] . ' - ' . $ht['hora_fin'] . ' | ';
        }
        $texto_horario_turnos = substr($texto_horario_turnos,0,-3);

        $tpl_portada->assign('texto_horario_turno',$texto_horario_turnos);


    }$tpl_portada->gotoBlock('restaurantes');

}$tpl_portada->gotoBlock('_ROOT');

$tpl_portada->printToScreen();

//return;

?>