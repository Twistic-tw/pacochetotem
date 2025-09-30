<?php

if($post = $_POST) {

    $es_gratuito = '0';

    $array_alergenos = [
        'frutos_secos' => ['img' => 'frutos_secos.svg', 'nombre' => 'Frutos Secos'],
        'lacteos' => ['img' => 'lactosa.svg', 'nombre' => 'Lácteos'],
        'huevos' => ['img' => 'huevina.svg', 'nombre' => 'Huevos'],
        'gluten' => ['img' => 'gluten2.svg', 'nombre' => 'Gluten'],
        'crustaceo' => ['img' => 'crustaceo.svg', 'nombre' => 'Crustáceo'],
        'pescado' => ['img' => 'pescado.svg', 'nombre' => 'Pescado'],
        'cacahuetes' => ['img' => 'cacahuetes.svg', 'nombre' => 'Cacahuetes'],
        'soja' => ['img' => 'soja.svg', 'nombre' => 'Soja'],
        'apio' => ['img' => 'apio.svg', 'nombre' => 'Apio'],
        'mostaza' => ['img' => 'mostaza.svg', 'nombre' => 'Mostaza'],
        'sesamo' => ['img' => 'sesamo.svg', 'nombre' => 'Séasmo'],
        'sulfito' => ['img' => 'sulfitos.svg', 'nombre' => 'Sulfito'],
        'altramuces' => ['img' => 'altramuces.svg', 'nombre' => 'Altramuces'],
        'molusco' => ['img' => 'moluscos.svg', 'nombre' => 'Molusco'],
    ];

    $lista_alergenos = null;

    foreach($post['alergenos'] as $key_alergeno => $alergeno){
        if($alergeno == 1){
            $lista_alergenos .= $array_alergenos[$key_alergeno]['nombre'].', ';
        }
    }

    $lista_alergenos = trim($lista_alergenos,', ');

    /* Por cookie o sacarlo del localStorage*/
//    $id_reserva = $_SESSION['id_reserva'];
    $id_reserva = $_COOKIE['id_reserva'];
    $array_datos_usuarios = get_datos_reserva($id_reserva);

    $id_restaurante = $post['id_restaurante'];
    $fecha_reserva = $post['fecha_global'];

    $observaciones_reservas = $post['observaciones_reserva'];
    //$alergenos_reservas = $post['alergenos_reserva'];
    $alergenos_reservas = $lista_alergenos;
    $tronas = count($post['tronas']);

    $turnos = get_turnos_franjas_restaurantes($id_restaurante, $fecha_reserva);
    $reservas_activas_usuarios = get_reservas_restaurates($id_restaurante, $fecha_reserva);

    // $numero_comensales = $array_datos_usuarios['numero_huespedes'];
    $numero_comensales = null;

    $tipo = 1;
    $restaurante = get_restaurante($id_restaurante, $tipo);
    $tipo_reserva = $restaurante['tipo_reserva']; //1 - por cupo, 2 - por mesas (cada reserva es una mesa)


    $datos_habitaciones_extras = $post['nueva_habitacion_comensales'];
    $reservas_extras_list = null;
    $habitaciones_extras = null;

    //if($datos_habitaciones_extras){

        foreach($datos_habitaciones_extras as $row_extra){
            
            $explode_h_extra = explode('_',$row_extra);
            $id_reserva_extra = str_replace('-','/',$explode_h_extra[0]);
            $comensales_extra = $explode_h_extra[1];
            $room_extra = $explode_h_extra[2];

            $numero_comensales = $numero_comensales + $comensales_extra;
            $reservas_extras_list .= ' | '.$id_reserva_extra;
            $habitaciones_extras .= ' | '.$room_extra;

            $reservas_activas_a = get_reservas_restaurates_franjas($id_restaurante,$fecha_reserva,$id_reserva_extra);

            if($reservas_activas_a){
                $respuesta = array('error_code' => 10, 'error_texto' => '', 'contenido' => '<div class="mensaje_error_checkin">' . LANG_RESTAURANTE_CON_RESERVA . '</div>');
                echo json_encode($respuesta);
                return;
            }

        }

        $reservas_extras_list = trim($reservas_extras_list,' | ');
        $habitaciones_extras = trim($habitaciones_extras,' | ');

    //}

    /*
    
        [nueva_habitacion_comensales] => Array
        (
            [0] => 1705-2022_4_820
            [1] => 56-2022_2_407
        )
    
    */


//    echo '<pre>';
//    print_r($post);
//    print_r($turnos);
//    print_r($array_datos_usuarios);

    /* primero comprobamos que todos los datos mandados por post son correctos */

    //Array
    //(
    //    [id_restaurante] => 1
    //    [fecha_global] => 2021-10-15
    //    [comensales] => 4
    //    [franjas] => Array
    //        (
    //            [2021-10-15_1_1_07:00-11:00] => 1
    //            [2021-10-15_2_2_13:00-15:30] => 1
    //            [2021-10-15_3_3_18:00-19:00] =>
    //            [2021-10-15_3_4_19:00-23:30] => 1
    //        )

    //error_code 1 - Intento de reserva, en franja que ya tiene una reserva
    //error_code 2 - Las horas de la reserva no coinciden con las de la base de datos
    //error_code 3 - Los comensales superan el cupo del restaurante
    //error_code 4 - Error en la query
    //error_code 5 - No se ha podido ejecutar la query
    //error_code 6 - No se ha seleccionado turno
    //error_code 7 - Ya existe una reserva en el mismo turno


    $restricciones = get_restaurante_restricciones($id_restaurante);

    if($restricciones){

        if($restricciones['tipo_restriccion'] == 1){

            if($restricciones['maximo_comensales'] > 0){

                if($numero_comensales > $restricciones['maximo_comensales']){
                    $respuesta = array('error_code' => 4, 'error_texto' => '', 'contenido' => '<div class="mensaje_error_checkin">' . LANG_NUMERO_MAXIMO_COMENSALES_RESERVA . '</div>');
                    echo json_encode($respuesta);
                    return;
                }

            }

        }elseif($restricciones['tipo_restriccion'] == 2){

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
                    $respuesta = ['error_code' => 4, 'error_texto' => '', 'contenido' => '<div class="fondo_popup"><div class="content_popup"><div class="mensaje_error_checkin">' . LANG_NUMERO_RESERVAS_EN_RESTAURANTE_GRATUITAS . '</div></div></div>'];
                    echo json_encode($respuesta);
                    return;
                }else{
                    $es_gratuito = '2';
                }

                //si no pasa el filtro $es_gratuito = 2 o 0 y tenemos que poner esto al guardar la reserva, modificar todas las bases de datos;
                //Cuidado con el tipo que está en manual a 1
                //hay que hacer un if para tener encuenta si es todo incluido, en caso contrario que contacte con recepción

            }

        }else{

        }

    }

    $no_ti = 0;
    $regimen_recetas = null;
    if($post['nueva_habitacion_comensales']){

        $total_habitacines_reserva = count($post['nueva_habitacion_comensales']);

        foreach($post['nueva_habitacion_comensales'] as $new_h){

            $explode_id_r = explode('_',$new_h);
            $explode_id_r = str_replace('-','/',$explode_id_r[0]);

            $datos_reservas_franjas = get_franjas_reservas_usuarios($explode_id_r);
            $datos_usuario_reserva_g = get_datos_reserva($explode_id_r);

            if($datos_usuario_reserva_g['regimen'] != 'TI'){
                $no_ti = 1;
            }

            $regimen_recetas .= $datos_usuario_reserva_g['regimen'] . ' | ';

            if($datos_reservas_franjas && $post['franjas']){
                foreach($post['franjas'] as $key_pf => $pf){
                    if($pf == 1){
                        $explode_pf = explode('_',$key_pf); //2022-05-04_3_1_18:00-19:20
                        $fecha_pf = $explode_pf[0];
                        $id_franja_pf = $explode_pf[1];
                        if($datos_reservas_franjas[$fecha_pf][$id_franja_pf]){

                            if($total_habitacines_reserva > 1){
                                $texto_error_franja = LANG_MISMA_FRANJA_HABITACION;
                            }else{
                                $texto_error_franja = LANG_MISMA_FRANJA;
                            }

                            $respuesta = array('error_code' => 4, 'error_texto' => '', 'contenido' => '<div class="mensaje_error_checkin">' . $texto_error_franja . '</div>');
                            echo json_encode($respuesta);
                            return;
                        }
                    }
                }
            }

        }
    }

    $regimen_recetas = trim($regimen_recetas, ' | ');

    $query_insert_reserva_restaurante = 'INSERT INTO restaurantes_reservas (id_usuario,nombre_usuario,num_habitacion,id_restaurante,id_franja_turno,numero_comensales,fecha,hora_inicio,hora_fin,activo,descripcion,alergenos,tronas,regimen,gratuito) VALUES ';
    $respuesta = null;
    foreach ($post['franjas'] as $key_reserva => $dato_reserva) {

        if ($dato_reserva == 1) {

            $explode_dr = explode('_', $key_reserva);
            $fecha = $explode_dr[0];
            $id_franja = $explode_dr[1];
            $id_franja_turno = $explode_dr[2];
            $explode_horas = explode('-', $explode_dr[3]);

            $hora_inicio = $explode_horas[0];
            $hora_fin = $explode_horas[1];

            $turno_con_reserva = array_count_values(array_column($reservas_activas_usuarios, 'id_restaurante_turno'))[$id_franja_turno];

            if($turno_con_reserva > 0){
                $respuesta = array('error_code' => 7, 'error_texto' => '', 'contenido' => '<div class="mensaje_error_checkin">' . LANG_ERROR_EXISTE_RESERVA_MISMO_TURNO . '</div>');
                break;
            }

            if ($reservas_activas_usuarios[$id_franja_turno]) {
                $respuesta = array('error_code' => 1, 'error_texto' => '', 'contenido' => '<div class="mensaje_error_checkin">' . LANG_ERROR_EXISTE_RESERVA . '</div>');
                break;
            }

            if (($turnos[$id_franja]['horarios_turno'][$id_franja_turno]['hora_inicio'] == $hora_inicio) &&
                ($turnos[$id_franja]['horarios_turno'][$id_franja_turno]['hora_fin'] == $hora_fin)) {

                // if ($turnos[$id_franja]['horarios_turno'][$id_franja_turno]['cupo_restante'] >= $numero_comensales) {
                //     $query_insert_reserva_restaurante .= '("' . $array_datos_usuarios['id_reserva'] . '","' . $array_datos_usuarios['numero_habitacion'] . '","' . $id_restaurante . '","' . $id_franja_turno . '","' . $numero_comensales . '","' . $fecha_reserva . '","' . $hora_inicio . '","' . $hora_fin . '","1","'.$observaciones_reservas.'","'.$alergenos_reservas.'","'.$tronas.'"),';
                // } else {
                //     $respuesta = array('error_code' => 3, 'error_texto' => '', 'contenido' => '<div class="mensaje_error_checkin">' . LANG_NO_HAY_CUPO_SUFICIENTE . '</div>');
                //     break;
                // }

                $error_cupo = true;
                if($tipo_reserva == 2){
                    $cupo_restante = $turnos[$id_franja]['horarios_turno'][$id_franja_turno]['cupo'] - $turnos[$id_franja]['horarios_turno'][$id_franja_turno]['reservas_total'];
                    if ($cupo_restante >= 1) {
                        $error_cupo = false;
                        // $query_insert_reserva_restaurante .= '("' . $array_datos_usuarios['id_reserva'].$reservas_extras_list. '","' . $array_datos_usuarios['numero_habitacion'].$habitaciones_extras. '","' . $id_restaurante . '","' . $id_franja_turno . '","' . $numero_comensales . '","' . $fecha_reserva . '","' . $hora_inicio . '","' . $hora_fin . '","1","'.$observaciones_reservas.'","'.$alergenos_reservas.'","'.$tronas.'"),'; 
                        $query_insert_reserva_restaurante .= '("' . $reservas_extras_list. '","'.$array_datos_usuarios['nombre_cliente'].'","' . $habitaciones_extras. '","' . $id_restaurante . '","' . $id_franja_turno . '","' . $numero_comensales . '","' . $fecha_reserva . '","' . $hora_inicio . '","' . $hora_fin . '","1","'.$observaciones_reservas.'","'.$alergenos_reservas.'","'.$tronas.'","'.$regimen_recetas.'","'.$es_gratuito.'"),'; 
                    }
                }else{
                    if ($turnos[$id_franja]['horarios_turno'][$id_franja_turno]['cupo_restante'] >= $numero_comensales) {
                        $error_cupo = false;
                        // $query_insert_reserva_restaurante .= '("' . $array_datos_usuarios['id_reserva'].$reservas_extras_list. '","' . $array_datos_usuarios['numero_habitacion'].$habitaciones_extras. '","' . $id_restaurante . '","' . $id_franja_turno . '","' . $numero_comensales . '","' . $fecha_reserva . '","' . $hora_inicio . '","' . $hora_fin . '","1","'.$observaciones_reservas.'","'.$alergenos_reservas.'","'.$tronas.'"),';
                        $query_insert_reserva_restaurante .= '("' . $reservas_extras_list. '","'.$array_datos_usuarios['nombre_cliente'].'","' .$habitaciones_extras. '","' . $id_restaurante . '","' . $id_franja_turno . '","' . $numero_comensales . '","' . $fecha_reserva . '","' . $hora_inicio . '","' . $hora_fin . '","1","'.$observaciones_reservas.'","'.$alergenos_reservas.'","'.$tronas.'","'.$regimen_recetas.'","'.$es_gratuito.'"),';
                    }
                }

                if($error_cupo){
                    $respuesta = array('error_code' => 3, 'error_texto' => '', 'contenido' => '<div class="mensaje_error_checkin">' . LANG_NO_HAY_CUPO_SUFICIENTE . '</div>');
                break;
                }

            } else {
                $respuesta = array('error_code' => 2, 'error_texto' => '', 'contenido' => '<div class="mensaje_error_checkin">' . LANG_ERROR_GLOBAL . '</div>');
                break;
            }

        }

    }

    if (!$respuesta) {

        if (substr($query_insert_reserva_restaurante, -1) == ',') {

            $query_insert_reserva_restaurante = substr($query_insert_reserva_restaurante, 0, -1) . ";";

            $consulta = get_insert_reserva_restaurante($query_insert_reserva_restaurante);

            if ($consulta) {
                //Correcto

                $mensaje_final_pago = null;
                if($_SESSION['id_centro'] == 1904 && $id_restaurante == 1){

                    // if($array_datos_usuarios['regimen'] != 'TI'){
                    //     $mensaje_final_pago = '. '.LANG_RESERVA_PAGO;
                    // }

                    if($no_ti == 1){
                        if($total_habitacines_reserva > 1){
                            $mensaje_final_pago = '. '.LANG_RESERVA_PAGO_INCLUIDA;
                        }else{
                            $mensaje_final_pago = '. '.LANG_RESERVA_PAGO;
                        }
                        
                    }

                }

                if($numero_comensales > 8){
                    $respuesta = array('error_code' => 0, 'error_texto' => '', 'contenido' => '<div class="mensaje_correcto_checkin">' .LANG_RESERVA_RESTAURANTE_COMPLETADA. '. '.LANG_RESERVA_MAS8.$mensaje_final_pago.'</div>');
                }else{
                    $respuesta = array('error_code' => 0, 'error_texto' => '', 'contenido' => '<div class="mensaje_correcto_checkin">' . LANG_RESERVA_RESTAURANTE_COMPLETADA .$mensaje_final_pago.'</div>');
                }

            } else {
                $respuesta = array('error_code' => 5, 'error_texto' => '', 'contenido' => '<div class="mensaje_error_checkin">' . LANG_ERROR_GLOBAL . '</div>');
            }

        } else {
            $query_insert_reserva_restaurante = null;
            $respuesta = array('error_code' => 6, 'error_texto' => '', 'contenido' => '<div class="mensaje_error_checkin">' . LANG_NO_TURNOS . '</div>');
        }

    }

}else{
    $respuesta = array('error_code' => 4, 'error_texto' => '', 'contenido' => '<div class="mensaje_error_checkin">' . LANG_ERROR_GLOBAL . '</div>');
}

echo json_encode($respuesta);
return;

?>