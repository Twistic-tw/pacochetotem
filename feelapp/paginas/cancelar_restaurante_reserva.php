<?php

    /*
     * Error_code 0 - Todo correcto
     * Error_code 1 - No hay post
     * Error_code 2 - No hay reserva
     * Error_code 3 - No se ha podido borrar la reserva
     * Error_code 4 - No se ha podido calcular el cupo
     * */

    if($id_reserva_bd = $_POST['id_reserva_restaurante']){

//        $id_reserva = $_SESSION['id_reserva'];
        $id_reserva = $_COOKIE['id_reserva'];
        $array_datos_usuarios = get_datos_reserva($id_reserva);

        //    [id_reserva] => 7006/2021
        //    [numero_habitacion] => 706
        //    [checkout] => 2021-10-30
        //    [checkin] => 2021-10-14
        //    [numero_huespedes] => 2
        //    [regimen] => TI

        $datos_reserva = check_reserva_restaurante($array_datos_usuarios,$id_reserva_bd);

        if($datos_reserva){

            $id_restaurante = $datos_reserva['id_restaurante'];
            $id_franja_turno = $datos_reserva['id_franja_turno'];
            $fecha = $datos_reserva['fecha'];

            if(delete_reserva_restaurante($id_reserva_bd)){

                $cupos = get_cupo_restaurantes($id_restaurante,$fecha);
                $datos_cupo = $cupos[$id_franja_turno];

                //    [4] => Array
                //        (
                //            [id_restaurante] => 1
                //            [id_franja_turno] => 4
                //            [cupo] => 60
                //            [fecha_inicio] => 2021-01-01
                //            [fecha_fin] =>
                //            [cupo_actual] => 2
                //            [cupo_restante] => 58
                //        )


                if($datos_cupo){

                    $respuesta = array('error_code' => 0, 'texto' => LANG_AFORO. ': '.$datos_cupo['cupo_restante'] ,'id_reserva' => $id_reserva_bd, 'contenido' => '<div class="mensaje_correcto_checkin">' . LANG_RESERVA_CANCELADA. '</div>');

                }else{

                    $respuesta = array('error_code' => 4, 'error_texto' => '', 'contenido' => '<div class="mensaje_error_checkin">' . LANG_ERROR_GLOBAL . '</div>');

                }

            }else{
                $respuesta = array('error_code' => 3, 'error_texto' => '', 'contenido' => '<div class="mensaje_error_checkin">' . LANG_ERROR_GLOBAL . '</div>');

            }


        }else{
            $respuesta = array('error_code' => 2, 'error_texto' => '', 'contenido' => '<div class="mensaje_error_checkin">' . LANG_ERROR_GLOBAL . '</div>');

        }


    }else{

        $respuesta = array('error_code' => 1, 'error_texto' => '', 'contenido' => '<div class="mensaje_error_checkin">' . LANG_ERROR_GLOBAL . '</div>');

    }

    echo json_encode($respuesta);
    return;

