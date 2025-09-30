<?php

    $post = $_POST;

    // [id_reserva_restaurante] => 111
    // [function] => popup_editar_reserva

    if($post){

        $id_reserva = $_COOKIE['id_reserva'];
        $array_datos_usuarios = get_datos_reserva($id_reserva);

        if($post['function'] == 'popup_editar_reserva'){
            
            $id_reserva_bd = $post['id_reserva_restaurante'];
            $datos_reserva = check_reserva_restaurante($array_datos_usuarios,$id_reserva_bd);

            // [id] => 111
            // [id_usuario] => dunasvillas-10320/2022
            // [nombre_usuario] => CIELECKA
            // [num_habitacion] => 138
            // [id_restaurante] => 2
            // [id_franja_turno] => 101
            // [numero_comensales] => 2
            // [tronas] => 
            // [mesas] => 
            // [fecha] => 2022-08-10
            // [hora_inicio] => 18:30
            // [hora_fin] => 19:45
            // [descripcion] => 
            // [alergenos] => 
            // [regimen] => MP
            // [activo] => 1

            $id_restaurante = $datos_reserva['id_restaurante'];
            $fecha = $datos_reserva['fecha'];
            $hora_inicio = $datos_reserva['hora_inicio'];
            $hora_fin = $datos_reserva['hora_fin'];
            $id_franja_turno = $datos_reserva['id_franja_turno'];
            $numero_comensales = $datos_reserva['numero_comensales'];

            $fecha_formateada = date("d-m-Y", strtotime($fecha));

            $datos_franja_real = get_datos_franja_rest($id_franja_turno);
            $id_franja_real = $datos_franja_real['id_restaurante_turno'];

            $datos_turnos = get_turnos_franjas_restaurantes($id_restaurante, $fecha)[$id_franja_real]['horarios_turno'];

            // Array
            //     (
            //         [101] => Array
            //             (
            //                 [id_restaurante_turno_horario] => 101
            //                 [hora_inicio] => 18:30
            //                 [hora_fin] => 19:45
            //                 [release] => 13:00
            //                 [apertura] => 1111111
            //                 [cupo] => 450
            //                 [cupo_actual] => 4
            //                 [cupo_restante] => 446
            //                 [reservas_total] => 2
            //             )

            //         [102] => Array
            //             (
            //                 [id_restaurante_turno_horario] => 102
            //                 [hora_inicio] => 20:30
            //                 [hora_fin] => 21:45
            //                 [release] => 13:00
            //                 [apertura] => 1111111
            //                 [cupo] => 350
            //                 [cupo_actual] => 0
            //                 [cupo_restante] => 350
            //                 [reservas_total] => 0
            //             )

            //     )

            $html = null;
            foreach($datos_turnos as $row){

                if($row['id_restaurante_turno_horario'] == $id_franja_turno){
                    continue;
                }

                $html .= '<div class="content-div-editar-reserva"><div class="content-horarios-editar-reserva">'.$row['hora_inicio'].' - '.$row['hora_fin'].'</div><div class="content-btn-editar-reserva"><a id="'.$row['id_restaurante_turno_horario'].'" data-id_restaurante="'.$id_restaurante.'" data-id_reserva="'.$id_reserva_bd.'" data-fecha="'.$fecha.'" data-hora-inicio="'.$row['hora_inicio'].'" data-hora-fin="'.$row['hora_fin'].'" href="#" class="seleccionar_turno_reserva_restaurate">'.LANG_SELECCIONAR.'</a></div></div>';

            }

            $html_header = '<div class="titulo-editar-panel">'.LANG_CAMBIAR_TURNO.'</div>';
            $html_header .= '<div class="fecha-editar-panel"><b>'.LANG_TEXTO_FECHA.':</b> '.$fecha_formateada.'</div>';
            
            if($html){
                $respuesta = array('error_code' => 0, 'error_texto' => '', 'contenido' => '<div class="mensaje_editar_reserva">' .$html_header.$html. '</div>');
            }else{
                $respuesta = array('error_code' => 0, 'error_texto' => '', 'contenido' => '<div class="mensaje_error_checkin">' .LANG_NO_TURNO. '</div>');
            }

        }elseif($post['function'] == 'popup_editar_reserva_final'){

            $id_reserva = $post['id_reserva'];
            $id_reserva_restaurante = $post['id_reserva_restaurante'];
            $hora_inicio = $post['hora_inicio'];
            $hora_fin = $post['hora_fin'];
            $id_restaurante = $post['id_restaurante'];
            $fecha = $post['fecha'];

            $id_reserva_bd = $post['id_reserva_restaurante'];
            $datos_reserva = check_reserva_restaurante($array_datos_usuarios,$id_reserva_bd);

            $datos_cupo_restante = get_cupo_restaurantes($id_restaurante, $fecha)[$id_reserva_restaurante]['cupo_restante'];
            $datos_reserva = get_reserva_detalle_restaurante($id_reserva);

            $numero_comensales = $datos_reserva['numero_comensales'];

            if(($datos_cupo_restante - $numero_comensales) >= 0){

                $update = update_reserva_restaurante($id_reserva,$id_reserva_restaurante,$hora_inicio,$hora_fin);
                $horas = $hora_inicio .' - '. $hora_fin;

                if($update){
                    $respuesta = array('error_code' => 0,'id_reserva' => $id_reserva, 'horas' => $horas, 'error_texto' => '', 'contenido' => '<div class="mensaje_editar_reserva">' .LANG_MODIFICACION_RESERVA. '</div>');
                }else{
                    $respuesta = array('error_code' => 1, 'error_texto' => '', 'contenido' => '<div class="mensaje_error_checkin">' . LANG_ERROR_GLOBAL . '</div>');
                }

            }else{
                $respuesta = array('error_code' => 1, 'error_texto' => '', 'contenido' => '<div class="mensaje_error_checkin">' . LANG_NUMERO_COMENSALES_MAXIMO . '</div>');
            }

        }else{
            $respuesta = array('error_code' => 1, 'error_texto' => '', 'contenido' => '<div class="mensaje_error_checkin">' . LANG_ERROR_GLOBAL . '</div>');
        }

    }else{
        $respuesta = array('error_code' => 1, 'error_texto' => '', 'contenido' => '<div class="mensaje_error_checkin">' . LANG_ERROR_GLOBAL . '</div>');
    }

    echo json_encode($respuesta);
    return;

?>