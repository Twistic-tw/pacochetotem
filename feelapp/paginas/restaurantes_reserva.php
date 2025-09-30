<?php

//$_SESSION['id_reserva'] = '7006/2021';
//$_SESSION['id_reserva'] = null;

// $_COOKIE['id_reserva'] = '7006/2021';
// setcookie("id_reserva", $_COOKIE['id_reserva'], time()+60000);

if ($_COOKIE['id_reserva']) {

    $ocultar_habitacion = false; 

    $array_alergenos = [
        'frutos_secos' => ['img' => 'frutos_secos.svg', 'nombre' => LANG_ALERGENO_FRUTOS_SECOS],
        'lacteos' => ['img' => 'lactosa.svg', 'nombre' => LANG_ALERGENO_LACTEOS],
        'huevos' => ['img' => 'huevina.svg', 'nombre' => LANG_ALERGENO_HUEVOS],
        'gluten' => ['img' => 'gluten2.svg', 'nombre' => LANG_ALERGENO_GLUTEN],
        'crustaceo' => ['img' => 'crustaceo.svg', 'nombre' => LANG_ALERGENO_CRUSTACEO],
        'pescado' => ['img' => 'pescado.svg', 'nombre' => LANG_ALERGENO_PESCADO],
        'cacahuetes' => ['img' => 'cacahuetes.svg', 'nombre' => LANG_ALERGENO_CACAHUETES],
        'soja' => ['img' => 'soja.svg', 'nombre' => LANG_ALERGENO_SOJA],
        'apio' => ['img' => 'apio.svg', 'nombre' => LANG_ALERGENO_APIO],
        'mostaza' => ['img' => 'mostaza.svg', 'nombre' => LANG_ALERGENO_MOSTAZA],
        'sesamo' => ['img' => 'sesamo.svg', 'nombre' => LANG_ALERGENO_SESAMO],
        'sulfito' => ['img' => 'sulfitos.svg', 'nombre' => LANG_ALERGENO_SULFITO],
        'altramuces' => ['img' => 'altramuces.svg', 'nombre' => LANG_ALERGENO_ALTRAMUCES],
        'molusco' => ['img' => 'moluscos.svg', 'nombre' => LANG_ALERGENO_MOLUSCO],
    ];

    //LANG_CONTACTE_CON_RECEPCION

    $id_centro = $_SESSION['id_centro'];
    $id_restaurante = $_POST['id_restaurante'];

    $total_comensales = $_POST['total_comensales'];
    $fecha_activa = $_POST['fecha_activa'];
    $horario_hora = $_POST['horario_hora'];

    $array_grafica = [];

    /* Por cookie o sacarlo del localStorage*/
//    $id_reserva = $_SESSION['id_reserva'];
    $id_reserva = $_COOKIE['id_reserva'];
    //Esto hay que ponerlo en una cookie o en localStorage
    $array_datos_usuarios = get_datos_reserva($id_reserva);

    if (! $array_datos_usuarios) {
        //Esto saca el login de prueba de la demo de twistic, hay que adaptarlo a lo que tenemos en dunas
        $respuesta = ['error_code' => 1, 'error_texto' => '', 'contenido' => ''];
        echo json_encode($respuesta);

        return;
    }

    $tipo = 1;
    $restaurante = get_restaurante($id_restaurante, $tipo);
    $tipo_reserva = $restaurante['tipo_reserva']; //1 - por cupo, 2 - por mesas (cada reserva es una mesa)
    if(!$tipo_reserva){
        $tipo_reserva = 1;
    }

    if ($restaurante) {

        $restricciones = get_restaurante_restricciones($id_restaurante);

        if($restricciones){

            if($restricciones['tipo_restriccion'] == 1){

                $numero_dias_semanas_res = $restricciones['dias_semana'];
                $numero_reservas_res = $restricciones['numero_reservas'];
                $maximo_comensales_res = $restricciones['maximo_comensales'];

                if($numero_reservas_res > 0){

                    $all_reservas_activas = get_total_reservas_restaurantes($id_restaurante,$id_reserva);
                    $total_reservas_res = count($all_reservas_activas);
    
                    $diferencia_dias_reserva_total = get_diferencia_dias_fechas($array_datos_usuarios['checkin'],$array_datos_usuarios['checkout']);

                    /* Si el número de reservas es igual al número máximo de reservas */

                    if($total_reservas_res >= $numero_reservas_res){

                        /* Si el número de días de la reserva es mayor que los días del filtro (default 1 semana) */
                        if($diferencia_dias_reserva_total  > $numero_dias_semanas_res){
                            $respuesta = ['error_code' => 5, 'contenido' => '<div class="fondo_popup"><div class="content_popup"><div class="mensaje_error_checkin">' . LANG_CONTACTE_CON_RECEPCION . '</div></div></div>'];
                            echo json_encode($respuesta);
                            return;
                        }else{
                            /* El total de días de la reserva es menor al del filtro */
                            $respuesta = ['error_code' => 5, 'contenido' => '<div class="fondo_popup"><div class="content_popup"><div class="mensaje_error_checkin">' . LANG_NUMERO_RESERVAS_EN_RESTAURANTE . '</div></div></div>'];
                            echo json_encode($respuesta);
                            return;
                        }



                    }

                }

            }elseif($restricciones['tipo_restriccion'] == 2){
                
                if($array_datos_usuarios['regimen'] != 'TI'){
                    $respuesta = ['error_code' => 5, 'contenido' => '<div class="fondo_popup"><div class="content_popup"><div class="mensaje_error_checkin">' . LANG_NUMERO_RESERVAS_EN_RESTAURANTE_GRATUITAS . '</div></div></div>'];
                    echo json_encode($respuesta);
                    return;
                }

                $ocultar_habitacion = true;

                $restaurantes_para_reservas = get_restaurantes_restricciones(2);
                $ids_restaurantes_restricciones = null;
                foreach($restaurantes_para_reservas as $rr){
                    $ids_restaurantes_restricciones .= $rr['id_restaurante'].',';
                }

                $ids_restaurantes_restricciones = trim($ids_restaurantes_restricciones,',');
                $diferencia_dias_reserva_total = get_diferencia_dias_fechas($array_datos_usuarios['checkin'],$array_datos_usuarios['checkout']);

                if($ids_restaurantes_restricciones && $diferencia_dias_reserva_total){

                    $reservas_gratuitas = get_reservas_gratuitas($id_reserva,$ids_restaurantes_restricciones);
                    $total_reservas_gratuitas = count($reservas_gratuitas);

                    $dias_r = $restricciones['dias_semana'];
                    $n_reservas_r = $restricciones['numero_reservas'];

                    $numero_total_disponible = $diferencia_dias_reserva_total / $dias_r * $n_reservas_r;
                    $numero_total_disponible = floor($numero_total_disponible);
                    
                    //hacer if

                    if($total_reservas_gratuitas >=  $numero_total_disponible){
                        $respuesta = ['error_code' => 5, 'contenido' => '<div class="fondo_popup"><div class="content_popup"><div class="mensaje_error_checkin">' . LANG_NUMERO_RESERVAS_EN_RESTAURANTE_GRATUITAS . '</div></div></div>'];
                        echo json_encode($respuesta);
                        return;
                    }

                    //si no pasa el filtro $es_gratuito = 2 o 0 y tenemos que poner esto al guardar la reserva, modificar todas las bases de datos;
                    //Cuidado con el tipo que está en manual a 1
                    //hay que hacer un if para tener encuenta si es todo incluido, en caso contrario que contacte con recepción

                }

            }else{

            }

        }
    

        $tpl_portada = new TemplatePower("plantillas/restaurantes_reserva.html", T_BYFILE);
        $tpl_portada->prepare();

        switch ($i) {
            case 1:
                $array_dias_semanas = [1 => 'L', 2 => 'M', 3 => 'X', 4 => 'J', 5 => 'V', 6 => 'S', 7 => 'D'];
                break;
            case 2:
                $array_dias_semanas = [
                    1 => 'Mon',
                    2 => 'Tue',
                    3 => 'Wed',
                    4 => 'Thu',
                    5 => 'Fri',
                    6 => 'Sat',
                    7 => 'Sun',
                ];
                break;
            default:
                $array_dias_semanas = [1 => 'L', 2 => 'M', 3 => 'X', 4 => 'J', 5 => 'V', 6 => 'S', 7 => 'D'];
        }

        $fechas_reservas = get_dias_reservas($id_restaurante);

//        echo '<pre>';
//        print_r($fechas_reservas); die;

        $tpl_portada->assign('nombre_restaurante', $restaurante['nombre']);
        $tpl_portada->assign('imagen', 'https://view.twisticdigital.com/contenido_proyectos/dunas/centro_'.$id_centro.'/imagenes/contenidos/'.$restaurante['imagen']);
        $tpl_portada->assign('seleccionar_turno', LANG_TURNO);
        $tpl_portada->assign('id_restaurante_global', $id_restaurante);
        $tpl_portada->assign('seleccionar_comensales', LANG_NUMERO_COMENSALES);
        $tpl_portada->assignGlobal('seleccionar_general', LANG_SELECCIONAR);
        $tpl_portada->assignGlobal('texto_seleccionar_turno', LANG_HAY_QUE_SELECCIONAR_TURNO);
        $tpl_portada->assignGlobal('cancelar_general', LANG_CANCELAR_RESERVA);
        $tpl_portada->assignGlobal('numero_comensales_texto', LANG_NUMERO_COMENSALES);
        $tpl_portada->assignGlobal('seleccionar_alergenos', LANG_SELECCIONA_ALERGENOS);
        $tpl_portada->assignGlobal('mensaje_superior', LANG_TIEMPO_RESERVA);
        $tpl_portada->assign('texto_fecha', LANG_DIA_RESERVA);
        $tpl_portada->assign('fecha_actual', date('Y-m-d'));

        $dia_inicial = 1;
        $turnos_activos = 0;
        foreach ($fechas_reservas as $key => $fecha_reserva) {

            if($fecha_reserva['fecha'] == date('Y-m-d')){
                unset($fechas_reservas[$key]);
                continue;
            }

            $tpl_portada->newBlock('fechas_reservas_content');
            $tpl_portada->assign('fecha_reserva_id', $fecha_reserva['fecha']);
            $tpl_portada->assign('id_reserva_gerenal',str_replace('/','-',$_COOKIE['id_reserva']));
            $tpl_portada->assign('id_restaurante', $id_restaurante);
            $tpl_portada->assign('numero_habitacion_r', $array_datos_usuarios['numero_habitacion']);

            $tpl_portada->assignGlobal('campo_requerido', LANG_COMPLETAR_CAMPOS);
            $tpl_portada->assign('reservar', LANG_RESERVAR);
            $tpl_portada->assign('texto_agregar_habitacion', LANG_AGREGAR_HABITACION);

            if($ocultar_habitacion){
                $tpl_portada->assign('ocultar_add_habitacion','displayNone');
            }

            $numero_comensales_format = intval($array_datos_usuarios['adultos']) + intval($array_datos_usuarios['ninos']); 
            $id_reserva_format = str_replace('/','-',$array_datos_usuarios['id_reserva']);

            $tpl_portada->assign('numero_comensales_r', $numero_comensales_format);

            $tpl_portada->assign('input_reserva','<input class="reservas_r_'.$fecha_reserva['fecha'].'" type="hidden" id="comensales_'.$fecha_reserva['fecha'].'_'.$id_reserva_format.'" name="nueva_habitacion_comensales[]" value="'.$id_reserva_format.'_'.$numero_comensales_format.'_'.$array_datos_usuarios['numero_habitacion'].'">');

            /*if($total_comensales > 1){
                $tpl_portada->assign('total_comensales', $total_comensales);
            }else{
                $tpl_portada->assign('total_comensales', 1);
            }*/

            if ($fecha_activa) {
                $tpl_portada->assign('fecha_activa', $fecha_activa);
            }

            //$turnos = $restaurante['turnos'];
            $turnos = get_turnos_franjas_restaurantes($id_restaurante, $fecha_reserva['fecha']);
            $reservas_activas_usuarios = get_reservas_restaurates($id_restaurante, $fecha_reserva['fecha']);

//            echo '<pre>';
//            print_r($reservas_activas_usuarios);

            $total_horarios = 0;
            foreach ($turnos as $key_turno => $turno) {

                if (($array_datos_usuarios['checkout'] == $fecha_reserva['fecha']) && ($turno['id_restaurante_turno'] != '1')) {
                    continue;
                }

                $turnos_activos++;

                $tpl_portada->newBlock('turnos_restaurantes');
                $tpl_portada->assign('id_restaurante_turno', $turno['id_restaurante_turno']);
                $tpl_portada->assign('icono', 'https://view.twisticdigital.com/dunas/feelapp/images/iconos/'.$turno['icono']);
                $tpl_portada->assign('nombre_turno', $turno['nombre_turno']);

                $turno_activo = $turno['activo_turno'];
                $horarios_turno = $turno['horarios_turno'];

//                $turno_con_reserva = array_search($turno['id_restaurante_turno'], array_column($reservas_activas_usuarios, 'id_restaurante_turno'));
                $turno_con_reserva = array_count_values(array_column($reservas_activas_usuarios, 'id_restaurante_turno'))[$turno['id_restaurante_turno']];

                //echo $turno_con_reserva . '<br>';

                foreach ($horarios_turno AS $key_horario => $horario_turno) {

                    //                    [2] => Array
                    //                        (
                    //                            [id_restaurante_turno_horario] => 2
                    //                            [hora_inicio] => 13:00
                    //                            [hora_fin] => 15:30
                    //                            [release] => 0
                    //                            [cupo] => 60
                    //                            [cupo_actual] => 0
                    //                            [cupo_restante] => 60
                    //                        )
                    //echo "<pre>";
                    //print_r($horario_turno);
                    //echo "<br>\n";

                    // $array_grafica[$fecha_reserva['fecha']][$turno['nombre_turno']."_".$horario_turno['id_restaurante_turno_horario']] = [
                    //     "cupo_total" => intval($horario_turno['cupo']),
                    //     "cupo_reservado" => intval($horario_turno['cupo_actual']),
                    // ];

                    if($tipo_reserva == 2){
                        $array_grafica[$fecha_reserva['fecha']][$turno['nombre_turno']."_".$horario_turno['id_restaurante_turno_horario']] = [
                            "cupo_total" => intval($horario_turno['cupo']),
                            "cupo_reservado" => intval($horario_turno['reservas_total']),
                        ];
                    }else{
                        $array_grafica[$fecha_reserva['fecha']][$turno['nombre_turno']."_".$horario_turno['id_restaurante_turno_horario']] = [
                            "cupo_total" => intval($horario_turno['cupo']),
                            "cupo_reservado" => intval($horario_turno['cupo_actual']),
                        ];
                    }

                    //sumamos todos los horarios, si solo hay uno se le pondrá la clase active
                    $total_horarios++;

                    $tpl_portada->newBlock('horario_turnos');
                    $tpl_portada->assign('hora_inicio', $horario_turno['hora_inicio']);
                    $tpl_portada->assign('hora_fin', $horario_turno['hora_fin']);
                    $tpl_portada->assign('turno', $turno['nombre_turno']."_".$horario_turno['id_restaurante_turno_horario']."_".$fecha_reserva['fecha']);

                    $error_texto = null;

//                     if ($reservas_activas_usuarios[$horario_turno['id_restaurante_turno_horario']]) {
//                         $error_texto = 1;
//                         $tpl_portada->assign('existe_reserva', LANG_RESERVA_ACTIVA);
//                         $tpl_portada->assign('class_desactivado', 'class_desactivado');
//                         $tpl_portada->assign('ocultar_btn_reserva', 'displayNone');
//                         $tpl_portada->assign('class_color_rojo', 'class_color_rojo');
//                         $tpl_portada->assign('btn_cancelar', '<div id="reserva-r_'.$reservas_activas_usuarios[$horario_turno['id_restaurante_turno_horario']]['id'].'" class="cancelar_turno_reserva_restaurate">'.LANG_CANCELAR_RESERVA.'</div>');
//                     }

//                     if ($horario_turno['cupo_restante'] < $array_datos_usuarios['numero_huespedes']) {

//                         $error_texto = 1;
//                         $tpl_portada->assign('class_color_rojo', 'class_color_rojo');
//                         $tpl_portada->assign('existe_reserva', LANG_NO_HAY_CUPO_DISPONIBLE);
// //                        $tpl_portada->assign('class_desactivado', 'class_desactivado');
//                         $tpl_portada->assign('class_desactivado', 'class_desactivado');
//                         $tpl_portada->assign('class_oculto', 'displayNone');

//                     }

//                     if (! $error_texto) {
//                         $tpl_portada->assign('existe_reserva', LANG_AFORO.': '.$horario_turno['cupo_restante']);

//                         if($turno_con_reserva > 0){
//                             $tpl_portada->assign('class_oculto', 'displayNone');
//                         }

//                     }


                    $error_texto = null;
                        
                    if ($reservas_activas_usuarios[$horario_turno['id_restaurante_turno_horario']]) {
                        $error_texto = 1;
                        $tpl_portada->assign('existe_reserva', LANG_RESERVA_ACTIVA);
                        $tpl_portada->assign('class_desactivado', 'class_desactivado');
                        $tpl_portada->assign('ocultar_btn_reserva', 'displayNone');
                        $tpl_portada->assign('class_color_rojo', 'class_color_rojo');
                        $tpl_portada->assign('btn_cancelar', '<div id="reserva-r_'.$reservas_activas_usuarios[$horario_turno['id_restaurante_turno_horario']]['id'].'" class="cancelar_turno_reserva_restaurate">'.LANG_CANCELAR_RESERVA.'</div>');
                    }

                    // if ($horario_turno['cupo_restante'] < $array_datos_usuarios['numero_huespedes']) {

                    if(!$reservas_activas_usuarios[$horario_turno['id_restaurante_turno_horario']]){

                        if($tipo_reserva == 2){//Para la reserva por mesas
                        
                            $total_mesas_disponibles = $horario_turno['cupo'] - $horario_turno['reservas_total'];
                            if ($total_mesas_disponibles < 1) {

                                $error_texto = 1;
                                $tpl_portada->assign('class_color_rojo', 'class_color_rojo');
                                $tpl_portada->assign('existe_reserva', LANG_NO_HAY_CUPO_DISPONIBLE);
                    //                        $tpl_portada->assign('class_desactivado', 'class_desactivado');
                                $tpl_portada->assign('class_desactivado', 'class_desactivado');
                                $tpl_portada->assign('class_oculto', 'displayNone');

                            }

                        }else{

                            if ($horario_turno['cupo_restante'] < 1) {

                                $error_texto = 1;
                                $tpl_portada->assign('class_color_rojo', 'class_color_rojo');
                                $tpl_portada->assign('existe_reserva', LANG_NO_HAY_CUPO_DISPONIBLE);
                    //                        $tpl_portada->assign('class_desactivado', 'class_desactivado');
                                $tpl_portada->assign('class_desactivado', 'class_desactivado');
                                $tpl_portada->assign('class_oculto', 'displayNone');

                            }

                        }

                    }


                    if (!$error_texto) {

                        if($tipo_reserva == 2){
                            $total_mesas_disponibles = $horario_turno['cupo'] - $horario_turno['reservas_total'];
                            if($total_mesas_disponibles < 0){
                                $total_mesas_disponibles = 0;
                            }
                            $tpl_portada->assign('existe_reserva', LANG_MESAS_DISPONIBLES.': '.$total_mesas_disponibles);
                        }else{
                            $tpl_portada->assign('existe_reserva', LANG_AFORO.': '.$horario_turno['cupo_restante']);
                        }

                        if($turno_con_reserva > 0){
                            $tpl_portada->assign('class_oculto', 'displayNone');
                        }

                    }

                    $id_horario = $turno['id_restaurante_turno'].'_'.$horario_turno['id_restaurante_turno_horario'];
                    $tpl_portada->assign('id_horario', $id_horario);

                    //activamos hora hora si se ha posteado
                    if ($horario_hora == $horario_turno['hora_inicio']) {
                        $tpl_portada->assign('activate_hora', 'active');
                    }

                    $hora_inicio = $horario_turno['hora_inicio'];
                    $activo_turno_horario = $hora_inicio;

                    $tpl_portada->assign('name_input', $fecha_reserva['fecha'].'_'.$turno['id_restaurante_turno'].'_'.$horario_turno['id_restaurante_turno_horario'].'_'.$horario_turno['hora_inicio'].'-'.$horario_turno['hora_fin']);
                }
                $tpl_portada->gotoBlock('turnos_restaurantes');
            }
            //die;
            $tpl_portada->gotoBlock('fechas_reservas_content');

            /* Si no hay turnos, lo eliminamos del array para no crear el día*/
            if ($total_horarios == 0) {
                unset($fechas_reservas[$key]);
                $tpl_portada->assign('ocultar_dia_restaurante', 'displayNone');
            } else {
                if ($dia_inicial != 1) {
                    $tpl_portada->assign('ocultar_fecha_reserva', 'display: none');
                }

                $dia_inicial++;
            }

            //si se ha pasado por post la hora, se la añadimos al global
            if ($horario_hora) {
                $tpl_portada->assignGlobal('horario_hora_global', $horario_hora);
            }

            $tpl_portada->assignGlobal('observaciones_texto', LANG_FEELAPP_OBSERVACIONES);
            $tpl_portada->assignGlobal('alergenos_texto', LANG_FEELAPP_ALERGENOS);

           if($array_datos_usuarios['bebes'] > 0){
                //$tpl_portada->newBlock('contant_tronas');
                for ($i = 1; $i <= $array_datos_usuarios['bebes']; $i++) {
                    $tpl_portada->newBlock('tronas');
                    $tpl_portada->assign('solicitar_trona',LANG_FEELAPP_SOLICITAR_TRONA);
                    $tpl_portada->assign('id_reserva_gerenal',str_replace('/','-',$_COOKIE['id_reserva']));
                }
                $tpl_portada->gotoBlock('fechas_reservas_content');
           }

           $tpl_portada->gotoBlock('fechas_reservas_content');
           foreach($array_alergenos as $key_alergeno => $alergeno){
                $tpl_portada->newBlock('alergenos');
                $tpl_portada->assign('img','https://view.twisticdigital.com/contenido_proyectos/dunas/_general/feelapp/alergenos/'.$alergeno['img']);
                $tpl_portada->assign('identificador',$key_alergeno);
                $tpl_portada->assign('nombre',$alergeno['nombre']);
           }$tpl_portada->gotoBlock('fechas_reservas_content');


        }
        $tpl_portada->gotoBlock('_ROOT');

        $dia_inicial = 1;

        if ($fechas_reservas) {

            foreach ($fechas_reservas as $fecha_reserva) {

                if($fecha_reserva['fecha'] == date('Y-m-d')){
                    continue;
                }

                $tpl_portada->newBlock('fechas_reservas');
                $tpl_portada->assign('fecha', $fecha_reserva['fecha']);
                $tpl_portada->assign('fecha_formateada', $fecha_reserva['fecha_formateada']);

                if ($dia_inicial == 1) {
                    $tpl_portada->assign('selected', 'selected="selected"');
                }

                $dia_inicial++;
            }
            $tpl_portada->gotoBlock('_ROOT');
        } else {
            $tpl_portada->assign('no_fechas_disponibles', '<div class="no_fechas">'.LANG_NO_HAY_RESERVAS_DISPONIBLES.'</div>');
            $tpl_portada->assign('ocultar_selector', 'displayNone');
        }

        $respuesta = [
            'error_code' => 0,
            'error_texto' => '',
            'contenido' => $tpl_portada->getOutputContent(),
            'datos_grafica' => $array_grafica,
        ];

        //echo "<pre>";
        //print_r($respuesta);
        //die;
        echo json_encode($respuesta);
    } else {
        $respuesta = ['error_code' => 2, 'error_texto' => '', 'contenido' => ''];
        echo json_encode($respuesta);
    }
} else {

    $respuesta = ['error_code' => 1, 'error_texto' => '', 'contenido' => ''];
    echo json_encode($respuesta);
}

return;

?>
