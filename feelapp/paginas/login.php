<?php

//if($post = $_POST){
//
//    /* Adaptar con el login de dunas, ahora está con arrray de forma manual */
//
//    $array_datos_reserva['1'] = array('pass' => 'apellido1','usuarios' => array(array('nombre' => 'José Luis','apellido1' => 'Ruano','apellido2' => 'Lemos', 'documento' => '11111111A', 'email' => 'jose_ruano_lemos@gmail.com', 'telefono' => '+34 111 11 11 11', 'pais' => 'España'),
//        array('nombre' => 'Esther','apellido1' => 'Palau','apellido2' => 'Heras', 'documento' => '22222222B', 'email' => 'esther_palau_heras@hotmail.com', 'telefono' => '+34 222 22 22 22', 'pais' => 'España')));
//
//    $array_datos_reserva['2'] = array('pass' => 'apellido2','usuarios' => array(array('nombre' => 'María','apellido1' => 'Torrecillas','apellido2' => 'Valenciano', 'documento' => '33333333C', 'email' => 'maria.torrecillas.valenciano@gmail.com', 'telefono' => '+34 333 33 33 33', 'pais' => 'España'),
//        array('nombre' => 'José Luis','apellido1' => 'Ruano','apellido2' => 'Lemos', 'documento' => '55555555C', 'email' => '', 'telefono' => '', 'pais' => '')));
//
//    $array_datos_reserva['3'] = array('pass' => 'apellido3','usuarios' => array(array('nombre' => 'Julio','apellido1' => 'Camino','apellido2' => 'Mata', 'documento' => '44444444D', 'email' => 'julio_camino@hotmail.com', 'telefono' => '', 'pais' => '')));
//
//
//    $array_datos_reserva['100'] = array('pass' => 'Izquierdo','usuarios' => array(array('nombre' => 'Rafael','apellido1' => 'Izquierdo','apellido2' => '', 'documento' => '44444444D', 'email' => 'rafaelprueba@hotmail.com', 'telefono' => '', 'pais' => '')));
//
//
////    if($array_datos_reserva[$post['numero_habitacion']] && $array_datos_reserva[$post['numero_habitacion']]['pass'] == $post['primer_apellido']){
//    if($array_datos_reserva[$post['numero_habitacion']] && strcasecmp($array_datos_reserva[$post['numero_habitacion']]['pass'],$post['primer_apellido']) == 0){
//
//        $_SESSION['usuario'] = 'ok';
//        //Por ahora ponemos el número de habitación como el id de usuario
//        $_SESSION['usuario_id'] = $post['numero_habitacion'];
//
//        $usuario_real = $array_datos_reserva[$post['numero_habitacion']]['usuarios'][0];
//        $_SESSION['nombre_usuario'] = trim($usuario_real['nombre'] . ' ' . $usuario_real['apellido1'] . ' ' . $usuario_real['apellido2']);
//
//        $texto_correcto = '<div class="mensaje_correcto_checkin">'.LANG_SESION_CORRECTA.'</div>';
//
//        $mensaje_whatsapp =  LANG_MENSAJE_WHATSAPP_1 . ' ' . $_SESSION['nombre_usuario'] . LANG_MENSAJE_WHATSAPP_2 . ' ' . $_SESSION['usuario_id'] . ' ';
//        $mensaje_whatsapp_completo = 'whatsapp://send?text='.$mensaje_whatsapp.'&phone='.LANG_TELEFONO_WHATSAPP.'&abid='.LANG_TELEFONO_WHATSAPP;
//
//        $respuesta = array('error_code' => 0, 'error_texto' => '', 'contenido' => $texto_correcto, 'texto_whatsapp' => $mensaje_whatsapp_completo);
//
//    }else{
//
//        $texto_incorrecto = '<div class="error_formulario">'.LANG_DATOS_NO_CORRECTO.'</div>';
//        $respuesta = array('error_code' => 1, 'error_texto' => $texto_incorrecto, 'contenido' => '');
//
//    }
//
//    echo json_encode($respuesta);
//
//}else{
//
//    $tpl_portada = new TemplatePower("plantillas/login.html", T_BYFILE);
//    $tpl_portada->prepare();
//
//    $tpl_portada->assign('titulo',LANG_INICIAR_SESION);
//    $tpl_portada->assign('primer_apellido',LANG_PRIMER_APELLIDO);
//    $tpl_portada->assign('numero_habitacion',LANG_NUMERO_HABITACION);
//    $tpl_portada->assign('campo_requerido',LANG_COMPLETAR_CAMPOS);
//    $tpl_portada->assign('btn_iniciar',LANG_ENVIAR);
//
//    $tpl_portada->printToScreen();
//
//}


$tpl_portada = new TemplatePower("plantillas/login.html", T_BYFILE);
$tpl_portada->prepare();

$tpl_portada->assign('titulo', LANG_DATOS_HUESPED);
$tpl_portada->assign('numero_habitacion', LANG_NUMERO_HABITACION);
$tpl_portada->assign('documento_identidad', LANG_DOCUMENTO_IDENTIDAD);
$tpl_portada->assign('enviar', LANG_BTN_ENVIAR);

$tpl_portada->assign('guest_explica', LANG_GUEST_EXPLICA);


$tpl_portada->printToScreen();

//if (isset($_GET['contenido_print'])) {
//    $tpl_portada->printToScreen();
//} else {
//    $respuesta = array('error_code' => 0, 'error_texto' => '', 'mensaje' => $tpl_portada->getOutputContent());
//    echo json_encode($respuesta);
//}


return;


?>