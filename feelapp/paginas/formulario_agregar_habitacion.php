<?php

    $tpl_portada = new TemplatePower("plantillas/formulario_agregar_habitacion.html", T_BYFILE);
    $tpl_portada->prepare();

    $tpl_portada->assign('numero_habitacion',LANG_NUMERO_HABITACION);
    $tpl_portada->assign('agregar',LANG_AGREGAR);
    $tpl_portada->assign('cancelar',LANG_CANCELAR_RESERVA);
    $tpl_portada->assign('fecha_reserva',$_POST['fecha_reserva_habitacion']);
    $tpl_portada->assign('id_restaurante_global',$_POST['id_restaurante_global']);
    $tpl_portada->assign('numero_total_comensales',$_POST['numero_total_comensales']);

    $respuesta = array('error_code' => 0, 'error_texto' => '', 'mensaje' => $tpl_portada->getOutputContent(), 'id_identificador_usuario' => $reserva['GuestIdentifier']);
    echo json_encode($respuesta);

?>